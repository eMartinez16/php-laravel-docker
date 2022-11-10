<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\CartaPorteController;
use Exception;
use Illuminate\Console\Command;

class SincroCartasPortesAuto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cp:sincro:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza las cartas de portes de automotor';

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
        $this->info("Inicia Sincronización");
        
        $cp = new CartaPorteController;
        $cp->forceSaveAuto();
          
        $this->info("Finaliza Sincronización");
      } catch (Exception $e) {
        $this->info("Error: ".$e->getMessage());
        throw new Exception($e->getMessage());        
      }  
    }
}
