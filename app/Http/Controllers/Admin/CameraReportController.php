<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\LogsTrait;
use App\Models\CameraReport;
use App\Models\CartaPorte;
use App\Models\Grain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CameraReportController extends Controller
{
    use LogsTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.camera_reports.index');
    }

    public function showReport(int $id)
    {
        $cartaPorte     = CartaPorte::find($id);
        $cartaPorteType = request('type') && +request('type') === 74 
                            ? 'Automotor' 
                            : 'Ferroviarias';
        $grain          = Grain::find($cartaPorte->cameraReport[$cartaPorte->id]['grain_id']);

        return view('user.camera_reports.show', [
            'cartaPorte' => $cartaPorte,
            'cameraReport' => $cartaPorte->cameraReport[$cartaPorte->id],
            'cartaPorteType' => $cartaPorteType,
            'grain' => $grain
        ]);
    }
    

    /**
     * Function to import Camera Report TXT
     * 
     * @param Request $req
     * @return RedirectResponse
     */
    public function importCameraReportTXT(Request $req)
    {
        try {
            ini_set('max_execution_time', 0);
            ini_set("memory_limit", "-1");

            if ($req->isMethod('POST') && $req->hasFile('archivo')) {
                $bulkdata = [];
                $file     = $req->file('archivo');
                $types    = ['text/plain'];

                if (in_array($file->getClientMimeType(), $types)) {
                    $txtFile = fopen($file, 'r');
                    if ($txtFile) {
                        $i = 1;
                        while (!feof($txtFile)) {
                            $line = fgets($txtFile);
                            /**
                             * Checks if the current line is empty
                             */
                            if (strval($line) === "") {
                                $i++;
                                continue;
                            }
                            /**
                             * Remove the "\r\n" part from the line.
                             */
                            $data = preg_split("~\s+~", $line)[0];
                            $i++;
                            array_push($bulkdata, $data);
                        }
                        
                        $messages = $this->storeBulkData($bulkdata);

                        if (empty($messages)) {

                            $this->general('Reporte de cámara', 0, 'Importación de txt', 'Importación masiva');
                            notify()->success("La importación se realizó con éxito.", "Éxito: ", "topRight");
                        } else {
                            if (count($messages) === count($bulkdata)) {
                                notify()->error("La información contenida en el archivo es errónea.", "Error: ", "topRight");
                            } else {
                                notify()->warning("La importación se realizó de forma parcial.", "Aviso: ", "topRight");
                            }
                        }            
                    } else {
                        throw new \Exception('No se pudo leer el archivo. Vuelva a intentarlo.');
                    }
                } else {
                    throw new \Exception('El archivo tiene un formato incompatible. Solo se admite ".txt"');
                }
            } else {
                throw new \Exception('No se ha enviado archivo, recuerde que solo se admite ".csv"');
            }
        } catch (\Throwable $th) {
            notify()->error($th->getMessage(), "Error: ", "topRight");
        } finally {
            if (!empty($txtFile)) {
                fclose($txtFile);
                unset($txtFile);
            }
        }

        return redirect()->route('admin.cameraReport.index');
    }

    /**
     * Function to store all the data coming from txt
     * 
     * @param string[] $data
     */
    public function storeBulkData($data)
    {
        $messages = [];
        foreach ($data as $camRepPayload) {
            $values = [
                'download_date'           => substr($camRepPayload, 0, 6),
                'carta_porte_id'          => substr($camRepPayload, 6, 13),
                'result_number'           => substr($camRepPayload, 19, 2),
                'essay_code'              => substr($camRepPayload, 21, 5),
                'essay_result'            => substr($camRepPayload, 26, 7),
                'kg'                      => substr($camRepPayload, 33, 7),
                'grain_id'                => substr($camRepPayload, 40, 2),
                'wagon_number'            => substr($camRepPayload, 42, 8),
                'fee_amount'              => substr($camRepPayload, 50, 9),
                'bonus_or_discount'       => substr($camRepPayload, 59, 1),
                'total_bonus_or_discount' => substr($camRepPayload, 60, 5),
                'out_of_standard'         => substr($camRepPayload, 65, 1),
                'certificate_number'      => substr($camRepPayload, 66, 7),
                'ctg_number'              => substr($camRepPayload, 73, 12),
            ];

            $hasFailed = $this->storeCameraReport($values);
            if ($hasFailed) array_push($messages, 'error');
        }
        return $messages;
    }

    /**
     * Function that store a new `Camera Report`.
     * Returns `true` if the `Camera report` has been created successfully, `false` if there was has been an error.
     * 
     * @param array $values
     * @return bool
     */
    public function storeCameraReport(array $values)
    {
        $hasError    = false;
        
        // Este array podría ir aumentando según lo que requieran los clientes.
        // "ids" de los granos según el cvs que nos pasaron de los códigos de ensayos.
        $grains = [
            1  => 'TRIGO',
            2  => 'MAIZ',
            3  => 'LINO',
            4  => 'AVENA',
            6  => 'CENTENO',
            7  => 'ALPISTE',
            8  => 'GIRASOL',
            9  => 'MIJO',
            11 => 'SORGO GRANIFERO',
            12 => 'SOJA',
            13 => 'CEBADA FORRAJERA',
            15 => 'TRIGO FIDEO',
            16 => 'CEBADA CERVECERA',
            17 => 'COLZA',
            18 => 'GIRASOL OLEICO',
            21 => 'CANOLA',
            25 => 'CARTAMO'
        ];

        /**
         * @var string|null $reportGrain Se obtiene el grano en base al id que se usa en el archivo que nos pasó el cliente. 
         * Si no lo encuentra en el arreglo `$grains`, entonces es "null"
         */
        $reportGrain = array_key_exists(+$values["grain_id"], $grains) 
                           ? $grains[+$values["grain_id"]] 
                           :  null; 
                           
        $grainExists = $reportGrain 
                           ? Grain::findByName($reportGrain)->first()
                           : Grain::find($values["grain_id"]);

        $cpExists = CartaPorte::findByCTG($values["ctg_number"])
                               ->first();

        if (!$cpExists || !$grainExists) $hasError = true;
        
        if (!$hasError) {
            $newCameraReport = new CameraReport();

            $downloadDate = substr($values["download_date"], 0, 2)."-".substr($values["download_date"], 2, 2)."-".substr($values["download_date"], 4, 2);

            $values = array_merge($values, [
                'carta_porte_id' => $cpExists->id,
                'essay_result'   => +number_format(($values['essay_result'] / 100), 2, '.', ','),
                'fee_amount'     => +number_format(($values['fee_amount'] / 100), 2, '.', ''),
                'grain_id'       => $grainExists->id,
                'download_date'  => $downloadDate,
                'ctg_number'     => +$values["ctg_number"]
            ]);

            if (!$newCameraReport->create($values)) $hasError = true;
        }

        return $hasError;
    }
}