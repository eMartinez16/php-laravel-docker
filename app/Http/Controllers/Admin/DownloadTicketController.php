<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\LogsTrait;
use App\Models\CartaPorte;
use App\Models\CategoriesGrains;
use App\Models\DownloadTicket;
use App\Models\Grain;
use App\Models\GrainCategory;
use App\Models\GrainPercentage;
use App\Models\TicketCategory;
use App\Utils\Constants;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DownloadTicketController extends Controller
{
    use LogsTrait;

    public function index()
    {
        return view('user.ticket.create', [
            'categories' => GrainCategory::pluck('name','id'),
            'conditions' => Constants::getConditions(),
            'ports'      => Constants::getPorts()
        ]);
    }


    public function store(Request $request)
    {
        if ($request->isMethod('POST')) {
            $cartaPorte = CartaPorte::find($request->nro_carta_porte);
            
            if(!is_null($cartaPorte) && !is_null($cartaPorte->ticket)){
                notify()->error('Esta carta de porte ya tiene un ticket asociado.', 'Error');
                
                return redirect()->route('admin.ticket.index'); 
            }
            
            if(!$request->port){
                notify()->error('Debe seleccionar un puerto.', 'Error');

                return redirect()->route('admin.ticket.index');
            }

            $newTicket = new DownloadTicket();
            
            if (!$newTicket->create($request->all())) {
                notify()->error('Error al crear el ticket.', 'Error');

                return redirect()->route('admin.ticket.index');
            }
            if ($request->rubros) {
                $rubros = json_decode($request->rubros, true);
                foreach ($rubros as $rubro) {
                    $newTicketCategory  = new TicketCategory();
                    $rubro['ticket_id'] = $newTicket::latest('id')->first()->id;

                    if (!$newTicketCategory->create($rubro)) {
                        notify()->error('Error al crear rubro para el ticket. Rubro id:'.$rubro->grain_category_id, 'Error');
                        return redirect()->route('admin.ticket.index');
                    }
                }
            }

            $cartaPorte = CartaPorte::findByNumber($request->nro_carta_porte)->first();
            
            if (!is_null($cartaPorte)) {
                $cartaPorte->status = 'cerrada';
                if (!$cartaPorte->update()) {
                    notify()->error('No se pudo actualizar estado de la carta de porte.', 'Error');
                    return redirect()->route('admin.ticket.index');  
                }
            } else {
                notify()->error('No se encontró carta de porte.', 'Error');
            }

            $this->creates('Ticket de descarga', $newTicket::latest('id')->first()->id, 'Ticket creado.');
            notify()->success('Ticket creado correctamente', 'Éxito');
            return redirect()->route('admin.ticket.index');  
        }
    }

    /**
     * Search the `grain` (percentage) of the `carta_porte`
     *  
     * @param integer $cpNumber  
     * @param float $value  
     */
    public function searchCP(int $cpNumber, float $value): JsonResponse
    {                   
        $cartaPorte      = CartaPorte::findByNumber($cpNumber)->first();
        $grainCategoryId = request('grain_cat');
        
        if (!is_null($cartaPorte)) {            
            $decodificado = json_decode($cartaPorte->json);
            $grain_id     = $decodificado->datosCarga->codGrano;
            
            if (!is_null($grain_id) && $grainCategoryId) {
                // get all the percentages of the grain
                $percentages = GrainPercentage::select('max_value')
                                               ->where(['grain_id' => $grain_id, 'grain_category_id' => $grainCategoryId])
                                               ->where(['grain_id' => $grain_id])
                                               ->orderBy('max_value', 'desc')
                                               ->get();
                            
                if (is_null($percentages)) return response()->json(['error' => 'No se encuentra descuento con ese valor.'], 404);
                else {
                    $maxRange = $minRange = 0;                       
                    /** 
                     * search the percentage between a range of two values  
                     * for example: we have 4 max_values for 1 grain: 70(10%), 50(90%), 30(15%), 20(5%)
                     *              if the value that cames from the form is 35, the discount will be 90%
                     *              because 35 is between 30 and 50 (30 < 35 <= 50)
                     **/
                    $percentages->map(function($p) use ($value, &$maxRange, &$minRange) {
                        if ($value <= $p->max_value){
                            $maxRange = $p->max_value;
                        }
                        if ($value > $p->max_value) {
                            if ($minRange > $p->max_value) return;
                            $minRange = $p->max_value;
                        }
                    });

                    // find the percentage between these two ranges
                    $percentage = GrainPercentage::select('percentage')
                                                  ->where(['grain_id' => $grain_id, 'grain_category_id' => $grainCategoryId])
                                                  ->where('max_value', '>', $minRange)
                                                  ->where('max_value', '<=', $maxRange)
                                                  ->first();

                    if(is_null($percentage)) $percentage = 0;
                    else $percentage = $percentage->percentage;
                }
                
                return response()->json(['discount' => $percentage, 'test' => request('grain_cat')] , 200);
            } else {
                return response()->json(['error' => 'El grano no se encuentra registrado en el sistema.'],404);
            }            
        } else {
            return response()->json(['error' => 'No se encontró carta de porte.'], 404);
        }  
    }

    /**
     * Check if the `carta_porte` exists
     */
    public function checkExistCP(int $cp_id): JsonResponse 
    {
        $response = false;
        $status   = 404;
        $exist    = CartaPorte::findByNumber($cp_id)->first();

        if(!is_null($exist)) {
            $decodificado = json_decode($exist->json);
            $grain_id     = $decodificado->datosCarga->codGrano;
            $response = ['response'=> true, 'grain_id' => $grain_id];
            $status =200;
        }

        return response()->json($response, $status);
    }

    /**
     * Return all the categories for one grain
     *
     * @param integer $grain_id
     * @return CategoriesGrains[]
     */
    public function getGrainCategories(int $grain_id)
    {
        $exist =CategoriesGrains::where('grain_id', $grain_id)->first();
        $categories = [];

        if ($exist) 
            $categories = Grain::with('categories')
                                ->where('id', $grain_id)
                                ->first();

        return response()->json(
           $categories,
            200
        );
    }

    public function show(Request $request)
    {
        if($request->id){
            $ticket = DownloadTicket::find($request->id);

            if (is_null($ticket)) {
                
                notify()->error('No existe el ticket', 'Error');
                return redirect()->back();
            }

            $conditions = Constants::getConditions();
            $ports      = Constants::getPorts();
            
            return view('user.ticket.show', compact('ticket', 'conditions','ports'));
        }
    }

    public function update(Request $request)
    {
        if ($request->isMethod('patch')) {
            if ($request->id) {
                $ticket = DownloadTicket::find($request->id);
                
                $newCategories     = json_decode($request->rubros, true);
                $deletedCategories = json_decode($request->deletedCategories, true);

                // dd($newCategories, $deletedCategories);

                if (count($deletedCategories)) {
                    foreach($deletedCategories as $category){
                        $existCategory = $this->hasCategory($request->id, intval($category['category_id']), floatval($category['value']), floatval($category['discount']));
                        
                        if (!is_null($existCategory)) {
                            try {
                                $existCategory->delete();                                
                            } catch (\Exception $th) {
                                throw $th;                            
                            }
                        }
                    }
                }
                if (count($newCategories)) {
                    foreach($newCategories as $category){  
                        $existCategory = $this->hasCategory($request->id, intval($category['grain_category_id']), $category['value'], $category['discount']);

                        if (is_null($existCategory)) {
                            try {
                                $this->addCategory($request->id, $category);                            
                            } catch (\Exception $th) {
                                throw $th;
                            }                                                    
                        } else {
                            try {
                                $this->updateCategory($existCategory, $category);                                
                            } catch(\Exception $th) {
                                throw $th;
                            }
                        }
                    }
                }

                if ($ticket->update($request->all())) {
                    $this->updates('Ticket de descarga', $ticket->id, 'Ticket actualizado');
                    notify()->success('Ticket actualizado correctamente', 'Éxito');
                    return redirect()->back();
                }

                notify()->error('Error al actualizar ticket.', 'Error');
                return redirect()->back();
            }
            
            notify()->error('Error ID.', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Destroy a ticket and update the CP that have that related ticket
     *
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        if ($request->isMethod('delete')) {
            if ($request->id) {
                $ticket = DownloadTicket::find($request->id);

                if (!is_null($ticket)) {
                    // Here delete the ticket and update the status of the CP 
                    $cp = CartaPorte::findByNumber($ticket->nro_carta_porte)->first();
                    $cp->status = false;
                    if ($ticket->delete() && $cp->save()) {
                        $this->deletes('Ticket de descarga', $request->id, 'Ticket eliminado');
                        
                        notify()->success('Ticket eliminado correctamente', 'Éxito');
                        return redirect()->back();
                    }

                    notify()->error('Ha ocurrido un error.', 'Error');
                    return redirect()->back();
                }

                notify()->error('No se encuentra el ticket.', 'Error');
                return redirect()->back();
            }

            notify()->error('Error ID.', 'Error');
            return redirect()->back();
        }
    }

    /**
     * function to check if one ticket has one category related
     * @todo check if the ticket will have many records of the same category (if so, need to add max_value and discount/percentage in the query)
     *
     * @param integer $ticket_id
     * @param integer $category_id
     * @return TicketCategory|null
     */
    public function hasCategory(int $ticket_id,  int $category_id, float $value, float $discount)
    {
        return TicketCategory::where([
            'ticket_id'         => $ticket_id, 
            'grain_category_id' => $category_id,
            'value'             => $value,
            'discount'          => $discount,
        ])
        ->first();
    }

    /**
     * create the relation between ticket and one category
     *
     * @param integer $ticket_id
     * @param array $category
     * @return true|\Exception
     */
    public function addCategory(int $ticket_id, array $category)
    {
        return TicketCategory::create([
            'ticket_id'         => $ticket_id,
            'grain_category_id' => $category['grain_category_id'],
            'discount'          => $category['discount'],
            'discount_kg'       => $category['discount_kg'],
            'value'             => $category['value'],
        ]);
    }

    /**
     * Update the value for one category of one ticket
     *
     * @param TicketCategory $category
     * @param array $newData
     */
    public function updateCategory(TicketCategory $category, array $newData)
    {
        return $category->update($newData);
    }
}
