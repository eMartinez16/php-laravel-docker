<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GrainPercentageRequest;
use App\Http\Traits\LogsTrait;
use App\Models\CategoriesGrains;
use App\Models\Grain;
use App\Models\GrainCategory;
use App\Models\GrainPercentage;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GrainPercentageController extends Controller
{
    use LogsTrait;
    private Collection $grains, $grainCategories;
    private const FAILED_MESSAGE = "FAILED";

    public function __construct()
    {
        $this->grains          = Grain::pluck('name', 'id');
        $this->grainCategories = GrainCategory::pluck('name', 'id');
    }

    public function index(Request $req)
    {
        $grainPercentages = GrainPercentage::orderBy('grain_id', 'asc')
                                            ->orderBy('max_value', 'asc');

        if ($req->grain && $req->grain !== 'todos') {
            $grainPercentages = $grainPercentages->where('grain_id', $req->grain);
        }

        if($req->category){
            $grainPercentages = $grainPercentages->where('grain_category_id', $req->category);
        }

        $grains           = $this->grains;
        $categories       = $this->grainCategories;
        $grainPercentages = $grainPercentages->paginate(20);

        return view('user.grains.percentages.index', compact('grains', 'grainPercentages', 'categories'));
    }
    
    public function edit(GrainPercentageRequest $req)
    {
        $grains = Grain::all();
        $grainPercentage = GrainPercentage::findOrFail($req->id);
        $categories = $this->grainCategories;

        return view('user.grains.percentages.edit', compact('grains', 'grainPercentage','categories'));
    }

    public function store(Request $req)
    {
        if ($req->isMethod('POST')) {   
            if ($req->grain_id == 'todos') {
                notify()->error('Debe seleccionar un tipo de grano', 'Error');
                return redirect()->route('admin.grainPercentages.index');  
            }
           
            $options = ['maxValue' => $req->max_value, 'percentage' => $req->percentage];

            $hasRelation = $this->checkRelation($req->grain_id, $req->grain_category_id, $options);

            if ($hasRelation == self::FAILED_MESSAGE) {
                notify()->error('Error al crear relación', 'Error');
                return redirect()->route('admin.grainPercentages.index');  
            }

            $hasPercentage = $this->checkPercentage($req->grain_id, $req->grain_category_id, $options);

            if (!is_null($hasPercentage)) {
                if ($this->updatePercentage($hasPercentage, $options)) {
                    
                    $this->general('Porcentaje de rebaja', $hasPercentage->id, 'Actualizado', 'Creacion de porcentaje');
                    notify()->success('Porcentaje de rebaja actualizado correctamente', 'Éxito');
                } else {
                    notify()->error('Error al actualizar porcentaje de rebaja', 'Error');
                }
            } else {
                $newGrainPercentage = new GrainPercentage();
    
                if (!$newGrainPercentage->create($req->all())) {
                    notify()->error('Error al crear porcentaje de rebaja', 'Error');
                }
                
                $this->creates('Porcentaje de rebaja', $newGrainPercentage::latest('id')->first()->id, 'Creado');
                notify()->success('Porcentaje de rebaja creado correctamente', 'Éxito');     
            }

            return redirect()->route('admin.grainPercentages.index');  
        }
    }

    public function show(Request $req)
    {
        $grainPercentage = GrainPercentage::findOrFail($req->id);

        $grains = $this->grains;
        $categories = $this->grainCategories;
        return view('user.grains.percentages.edit', compact('grainPercentage', 'grains', 'categories'));
    }

    public function update(Request $req)
    {
        if ($req->id) {            
            $grainPercentage = GrainPercentage::findOrFail($req->id);

            if (!$grainPercentage->update($req->all())) {
                notify()->error('Error al actualizar porcentaje de rebaja', 'Error');
            }

            $this->updates('Porcentaje de rebaja', $grainPercentage->id ,'Actualizado');
            notify()->success('Porcentaje de rebaja actualizado correctamente', 'Éxito');     
            return redirect()->route('admin.grainPercentages.index');  
        }
    }

    public function destroy(Request $req)
    {
        if ($req->isMethod('delete')) {
            $grainPercentage = GrainPercentage::findOrFail($req->id);

            if (!$grainPercentage->delete()) {
                notify()->error('Error al eliminar porcentaje de rebaja', 'Error');
            }

            $this->deletes('Porcentaje de rebaja', $req->id, 'Eliminado');
            notify()->success('Porcentaje de rebaja eliminado correctamente', 'Éxito');
            return redirect()->route('admin.grainPercentages.index');
        }
    }

    public function downloadCsvExample() 
    {
        $headers = ['Content-Type' => 'text/csv'];

        $fields = [
            'Codigo grano (Formato numerico (obligatorio))',
            'Codigo rubro (Formato numerico (obligatorio))',
            'Valor maximo (Formato numerico (obligatorio))',
            'Porcentaje (Formato numerico (obligatorio))',     
        ];

        return response()->streamDownload(function () use ($fields) {
            $line  = implode(';', $fields);
            echo $line;
        }, 'ejemplo_importador_porcgranos.csv', $headers);
    }

    public function importPercentages(Request $req)
    {        
        try {
            ini_set('max_execution_time', 0);
            ini_set("memory_limit", "-1");

            if ($req->isMethod('POST') && $req->hasFile('archivo')) {      
                $messages  = [];
                $delimiter = ';';
                $file      = $req->file('archivo');
                $types     = ['application/vnd.ms-excel', 'text/csv', 'text/plain'];

                if (in_array($file->getClientMimeType(), $types)) {
                    if (($csvFile = fopen($file, 'r')) !== FALSE) {     
                        $row  = 0;
                        $data = [];

                        while (($line = fgetcsv($csvFile, 1000, $delimiter)) !== FALSE) {                           
                            $number = count($line);
                            $row++;
                            $data[] = $line;

                            if ($row == 1) continue;                            
                            if ($number <= 1) {
                                throw new \Exception('El archivo no posee un formato valido, corrobore que el delimitador del archivo sea el seleccionado y vuelva a intentarlo');
                            }                            
                            if ($number < 3) {
                                throw new \Exception("A la fila nro. $row le faltan columnas, por favor verifique la que la cantidad sea la misma que en el ejemplo y vuelva a intentarlo");
                            }
                        }
                        unset($data[0]); 
                        
                        /**
                         * @var array $gp - Grain Percentage that comes from the .csv file [grain_id, grain_category_id, max_value, percetange]
                         */
                        foreach ($data as $gp) {
                            $grainId         = $gp[0];
                            $grainCategoryId = $gp[1];
                            $maxValue        = str_replace('%', '', $gp[2]); // contemplates that the data has the symbol "%"
                            $percentage      = str_replace('%', '', $gp[3]); // "

                            $options['maxValue']   = doubleval(str_replace(',', '.', $maxValue)); // contemplates that the data has "." as decimal separator
                            $options['percentage'] = doubleval(str_replace(',', '.', $percentage)); // "

                            $grainExists        = Grain::find($grainId);
                            $grainCategoryExist = GrainCategory::find($grainCategoryId);
                            
                            if ($grainExists && $grainCategoryExist) {
                                $hasRelationAndPercentage = $this->checkRelation($grainId, $grainCategoryId, $options);
                                
                                if ($failed = $hasRelationAndPercentage == self::FAILED_MESSAGE) 
                                    $messages[] = ['Error al crear relación entre grano: '.$grainId.', y rubro: '.$grainCategoryId];
                                
                                
                                if (!is_null($hasRelationAndPercentage) && !isset($failed)) {
                                    $savedPercentage = $this->updatePercentage($hasRelationAndPercentage, $options);

                                    if (!$savedPercentage) {
                                        $messages[] = ['No se pudo actualizar el porcentaje. Valor hasta: '.$options['maxValue'].' , porcentaje: '.$options['percentage']];
                                    }
                                    $this->general('Porcentaje de rebaja', $hasRelationAndPercentage->id, 'Actualizado', 'Importación masiva');

                                } else {
                                    $created = $this->createPercentage($grainId, $grainCategoryId, $options);                      
                                   
                                    if (!$created) {
                                        $messages[] = ['grano' => $data['grano'], 'errores' => 'No se pudo crear la rebaja del grano.'];
                                    }
                                    $this->general('Porcentaje de rebaja', GrainPercentage::latest('id')->first()->id, 'Creado', 'Importación masiva');
                                }
                                                                
                            } else {
                                $messages[] = ['grano' => $grainId, 'errores' => 'El grano no está cargado en el sistema'];
                            }
                        }

                        if (empty($messages)) {

                            $this->general('Porcentaje de rebaja', 0, 'Importado correctamente', 'Importación masiva');
                            notify()->success("La importación se realizó con éxito.", "Éxito: ", "topRight");
                        } else {
                            if (count($messages) == count($data)) {
                                notify()->error("La información contenida en el archivo es errónea.", "Error: ", "topRight");
                            } else {
                                notify()->warning("La importación se realizó de forma parcial.", "Aviso: ", "topRight");
                            }
                        }            
                    } else {
                        throw new \Exception('No se pudo leer el archivo. Vuelva a intentarlo.');
                    }
                } else {
                    throw new \Exception('El archivo tiene un formato incompatible. Solo se admite ".csv"');
                }
            } else {
                throw new \Exception('No se ha enviado archivo, recuerde que solo se admite ".csv"');
            }
        } catch (\Throwable $th) {
            notify()->error($th->getMessage(), "Error: ", "topRight");
        } finally {
            if (!empty($csvFile)) {
                fclose($csvFile);
                unset($csvFile);
            }
        }

        return redirect()->route('admin.grainPercentages.index');
    }

    /** 
     * Check relation between grain, grain category and categories grains
     * 
     * Check if a relation exists between a grain and a grain category. If exists, verify if already has a percentage and return that object or null if not. 
     * When it doens\'t have a relationship, it creates it and performs a recursive operation on the same function. 
     * If the relationship creation fails, it returns the string `FAILED`.
     * 
     * @param int $grainId
     * @param int $grainCategoryId
     * @return GrainPercentage | null | string
     */
    public function checkRelation(int $grainId, int $grainCategoryId, ?array $options) 
    {
        $hasRelation = CategoriesGrains::where(['grain_id'=> $grainId, 'grain_category_id' => $grainCategoryId])->first();

        if($hasRelation) {
            if (sizeof($options)) {
                /**
                * Check if the max value or the percentage coming from the csv is 
                * already in the system and is to update
                */
                return $this->checkPercentage($grainId, $grainCategoryId, $options);
            } else {
                return 'FAILED';
            }
        } else {
            $newRelation = new CategoriesGrains();

            if (!$newRelation->create(['grain_id' => $grainId, 'grain_category_id' => $grainCategoryId])) 
                return 'FAILED';
            
            $this->general('Rubro/grano', $newRelation::latest('id')->first()->id, 'Relacion creada', 'Creacion de porcentaje');
            $this->checkRelation($grainId, $grainCategoryId, $options);
        }
    }

    /**
     * Updates a Grain Percentage
     * 
     * Try to update a percentage. Returns false if it fails.
     * 
     * @param GrainPercentage $percentage
     * @param null|array $values
     * @return bool
     */
    public function updatePercentage(GrainPercentage $percentage, ?array $values): bool
    {
        $percentage->max_value  = $values['maxValue'];
        $percentage->percentage = $values['percentage'];
        return ($percentage->save()) ? true : false;
    }
    
    /**
     * Creates a Grain Percentage
     * 
     * Try to create a percentage. Returns false if it fails.
     * 
     * @param int $grainId
     * @param int $grainCategoryId
     * @param null|array $options
     * @return bool
     */
    public function createPercentage(int $grainId, int $grainCategoryId, ?array $options): bool
    {
        $newPercentage = [
            'grain_id'          => $grainId, 
            'grain_category_id' => $grainCategoryId, 
            'max_value'         => $options['maxValue'],
            'percentage'        => $options['percentage']
        ];
        return (GrainPercentage::create($newPercentage)) ? true : false;
    }

    /**
     * Check if grain percentage already exists
     * 
     * @param int $grainId
     * @param int $grainCategoryId
     * @param array $options maxValue and percentage
     * @return GrainPercentage|null
     */
    public function checkPercentage(int $grainId, int $grainCategoryId, array $options)
    {
        return GrainPercentage::where(['grain_id' => $grainId, 'grain_category_id' => $grainCategoryId])
                               ->where(function($query) use($options){
                                   $query->orWhere(['max_value'  => $options['maxValue']])
                                       ->orWhere(['percentage' => $options['percentage']]);
                               })
                               ->first();
    }
}
