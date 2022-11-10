<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\CurrentAccount;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;
use Carbon\Carbon;

class CurrentAccountController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::pluck('business_name', 'id');
        $currentaccounts = [];
        if ($request->client) {
            $currentaccounts = CurrentAccount::where('client_id', $request->client)->get();
        } 

        $currentaccounts =  $this->getDateAndDelayDays($currentaccounts);
        $amount = $currentaccounts['total'];

        if($from = $request->from){
            $currentaccounts = $currentaccounts['accounts']->filter(function($ca) use($from){
                return $ca->created_at >= $from;
            });
        }

        if($to = $request->to){
            $currentaccounts = $currentaccounts['accounts']->filter(function($ca) use($to){
                return $ca->created_at <= $to;
            });
        }        

        return view('user.current_account.index', compact('currentaccounts', 'clients', 'amount'));
    }

    /**
     * download accounts in pdf
     *
     * @param integer $client_id 
     */
    public function downloadPDF(int $client_id){
        $accounts = CurrentAccount::where('client_id', $client_id)->get();
        $accounts = $this->getDateAndDelayDays($accounts);

        view()->share(['accounts' => $accounts['accounts'],'total'=> $accounts['total']]);
        $pdf = PDF::loadView('user.current_account.pdf', compact('accounts'));
        return $pdf->download('resumen_cliente_'.$accounts['accounts'][0]->client->business_name.'.pdf');
    }


    /**
     * modified the created_at date and add delaysDays in each object
     *
     * @todo fix this..
     * @param CurrentAccount[] $accounts
     * @return array first position the accounts with fields modified (created_at and added delayDays) and second is the total ammunt
     */
    private function getDateAndDelayDays($accounts){
        $amount = 0;             
        foreach ($accounts as $key => &$ca) {
            $delayDays = 0;
            $clientCondition = $ca->client->payment_condition;

            switch ($clientCondition['reference']) {
                case 'contado':
                    $clientCondition = 10;
                    break;
                
                case '15_dia':
                    $clientCondition = 15;
                    break;
                case '30_dia':
                    $clientCondition = 30;
                    break;
            }
                    
            if (!is_null($ca->debe) && $ca->debe>=0) {
                $amount += $ca->debe;
                $ca->created_at =$ca->invoice->date;
                //Si la fecha de hoy es mayor a la sumatoria de la fecha de creacion + dias de pago dependiendo la condicion del cliente, se agregan los dias de demora
                if(now() > $ca->created_at->addDays($clientCondition)){
                    $delayDays = now()->diffInDays(Carbon::parse($ca->created_at->addDays($clientCondition))); 
                }
            }

            if (!is_null($ca->haber) && $ca->haber>=0) {
                $amount -= $ca->haber;
                $ca->created_at = (Payment::where('nro_comprobante', $ca->nro_voucher)->first())->fecha_pago;

                //Si la fecha de hoy es mayor a la sumatoria de la fecha de creacion + dias de pago dependiendo la condicion del cliente, se agregan los dias de demora
                if(now() > $ca->created_at->addDays($clientCondition)){
                    $delayDays = now()->diffInDays(Carbon::parse($ca->created_at->addDays($clientCondition))); 
                }
            }
            $ca->delayDays = $delayDays;
        }
        return ['accounts' => $accounts, 'total' => $amount];
    }
}
