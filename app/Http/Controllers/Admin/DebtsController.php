<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CurrentAccount;
use App\Models\Client;
use Carbon\Carbon;

class DebtsController extends Controller
{
    public function index(Request $request)
    {
        $total_deuda = 0;
        $saldo = 0;
        $inbox = [];

        $clients = Client::orderBy('id', 'desc')->get();
        if (!$request->date_start) {
            $request->date_start = Carbon::now()->startOfMonth()->format('Y-m-d');
        }
        if (!$request->date_end) {
            $request->date_end = Carbon::now()->format('Y-m-d');
        }
        $start = $request->date_start;
        $end = $request->date_end;
        //Si ingreso un cliente especifico busca sus movimientos en un rango de fechas, sino lista todos los clientes con las deudas x cada uno
        if ($request->client && $request->client != 'todos') {
            $deudas = CurrentAccount::where('client_id', $request->client)->whereDate('created_at','>=',$request->date_start)->whereDate('created_at','<=',$request->date_end)->get();
            foreach ($deudas as $key => $ca) {
                if ($ca->debe>=0) {
                    $total_deuda += $ca->debe;
                    $saldo += $ca->debe;
                }
                if ($ca->haber>=0) {
                    $total_deuda -= $ca->haber;
                    $saldo -= $ca->haber;
                }
                $fecha_ult = $ca->created_at;
                if ($ca->client) {
                    $client = $ca->client;
                }
            }
            if (count($deudas) != 0) {
                $inbox[$request->client] = ['client' => $client, 'saldo' => $saldo, 'fecha_ult'=> $fecha_ult];
            }
        } else {
            $deudas = CurrentAccount::whereDate('created_at','>=',$request->date_start)->get();
            foreach ($deudas as $key => $d) {
                if (isset($inbox[$d->client->id]) == true) {
                    $saldo = $inbox[$d->client->id]['saldo'];
                } else {
                    $saldo = 0;
                }
                if ($d->debe>0) {
                    $saldo += $d->debe;
                }
                if ($d->haber>0) {
                    $saldo -= $d->haber;
                }
                $fecha_ult = $d->created_at->format('d/m/Y');
                //guardo todo en un array para recorrerlo en la vista
                $inbox[$d->client->id] = ['client' => $d->client, 'saldo' => $saldo, 'fecha_ult'=> $fecha_ult];
            }
            //recorro todo el array para sumar la deuda total
            foreach ($inbox as $key => $i) {
                $total_deuda += $i['saldo'];
            }
        }
        return view('user.debts.index', compact('inbox', 'clients', 'total_deuda', 'start', 'end'));
    }
}
