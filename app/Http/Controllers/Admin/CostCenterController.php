<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\LogsTrait;
use App\Models\CostCenter;
use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    use LogsTrait;

    protected const TYPES = [
        'FIJO'     => 'fijo',
        'VARIABLE' => 'variable'
    ];

    public function index()
    {
        $costCenters = CostCenter::orderBy('id', 'desc')
                                  ->paginate(5);
        return view('user.cost_centers.index', [
            'costCenters' => $costCenters, 
            'types'       => self::TYPES
        ]);
    }

    public function store(Request $req)
    {
        if($req->isMethod('POST')){
            $newCostCenter = new CostCenter();

            if (!$newCostCenter->create($req->all())) {
                notify()->error('Error al crear centro de costo', 'Error');     
            }

            $this->creates('Centro de costos', $newCostCenter::latest('id')->first()->id, 'Creado');
            notify()->success('Centro de costo creado correctamente', 'Éxito');     
            return redirect()->route('admin.cost-centers.index');  
        }
    }

    public function show(Request $req)
    {
        $costCenter = CostCenter::findOrFail($req->id);
        return view('user.cost_centers.edit', [
            'costCenter' => $costCenter, 
            'types'      => self::TYPES
        ]);
    }

    public function update(Request $req)
    {
        if ($req->id) {
            $costCenter = CostCenter::findOrFail($req->id);

            if (!$costCenter->update($req->all())) {
                notify()->error('Error al actualizar centro de costo', 'Error');
            }

            $this->updates('Centro de costos', $costCenter->id, 'Actualizado');
            notify()->success('Centro de costo actualizado correctamente', 'Éxito');     
            return redirect()->route('admin.cost-centers.index');  
        }
    }

    public function destroy(Request $req)
    {
        if ($req->isMethod('DELETE')) {
            $costCenter = CostCenter::findOrFail($req->id);

            if (!$costCenter->delete()) {
                notify()->error('Error al eliminar centro de costo.', 'Error');
            }

            $this->deletes('Centro de costos', $req->id, 'Eliminado');
            notify()->success('Centro de costo eliminado correctamente', 'Éxito');
            return redirect()->route('admin.cost-centers.index');  
        }
    }
}
