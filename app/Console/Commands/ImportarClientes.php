<?php

namespace App\Console\Commands;

use App\Models\Client;
use Illuminate\Console\Command;

class ImportarClientes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:clientes {--all=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importación inicial de clientes desde CSV';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      try {          
        $all = $this->option('all'); //si es true, carga a todos los clientes, si existe lo actualiza / si es falso, carga sino existe

        if($all=='true')
          $all = true;
        else 
          $all = false;

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "-1");
  
        // $archivo =  fopen(public_path().'/import/clientes.csv', 'r');
        $archivo =  fopen(public_path().'/import/clientes-copia.csv', 'r');
        
        if($archivo){
          // $fila = 0;
          while(($registro = fgetcsv($archivo, 1000, ';')) == true){
            $arrCliente = explode(',',$registro[0]);
            // if ($fila > 0) {
              $cuit = str_replace("-","",$arrCliente[0]);
              $nombre = $arrCliente[1];
              $residence = $arrCliente[3];
              $location = $arrCliente[4];
              $payment_condition = $arrCliente[5];
              if($all){
                $c = Client::where('cuit',$cuit)->first();
                if(empty($valid)) {
                  $c = new Client();
                }
                $c->liable = $nombre ?? null;
                $c->business_name = $nombre ?? null;
                $c->email = '';
                $c->CUIT = $cuit ?? null;
                $c->phone = '';
                $c->location = $location ?? null;
                $c->residence = $residence ?? null;
                $c->fiscal_condition_id = 2;
                $c->payment_condition = $payment_condition ?? null;
                $c->category_id = 1;
                $c->disabled = false;
                $c->save();
              } else {
                $valid = Client::where('cuit',$cuit)->first();
                if(empty($valid)) {
                  $c = new Client();
                  $c->liable = $nombre ?? null;
                  $c->business_name = $nombre ?? null;
                  $c->email = '';
                  $c->CUIT = $cuit ?? null;
                  $c->phone = '';
                  $c->location = $location ?? null;
                  $c->residence = $residence ?? null;
                  $c->fiscal_condition_id = 2;
                  $c->payment_condition = $payment_condition ?? null;
                  $c->category_id = 1;
                  $c->disabled = false;
                  $c->save();
                }
              }
          //   }
          // $fila ++;
          }
        }
      }catch(\Exception $ex){
        $this->info($ex->getMessage());
        return false;
      }    
    }
}
