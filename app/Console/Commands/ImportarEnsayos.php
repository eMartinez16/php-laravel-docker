<?php

namespace App\Console\Commands;

use App\Models\GrainParams;
use Illuminate\Console\Command;

class ImportarEnsayos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:ensayos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar Ensayos para Reporte de Cámara desde CSV';

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
            ini_set('max_execution_time', 0);
            ini_set("memory_limit", "-1");
        
            $file = fopen(public_path().'/import/codigos-ensayo.csv', 'r');
            

            if ($file) {
                while (($line = fgetcsv($file, 1000, ';')) !== false) {
                    $essay     = explode(',', $line[0]);
                    $essayCode = $essay[2];
                    $essayDesc = $essay[3];

                    $essayAlreadyExist = GrainParams::findByCode($essayCode)->first();

                    if ($essayAlreadyExist) continue;

                    $newEssay = new GrainParams();
                    $data = [
                        'code'        => $essayCode,
                        'description' => $essayDesc
                    ];
                    if (!$newEssay->create($data)) return 'Error al guardar importar el ensayo #'.$essayCode;
                }
            }
        } catch(\Exception $ex) {
            $this->info($ex->getMessage());
            return false;
        } finally {
            if (!empty($file)) {
                fclose($file);
                unset($file);
            }
        }
    }
}
