<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCameraReportsTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('camera_reports', function (Blueprint $table) {
            $table->id();
            $table->date('download_date');
            $table->integer('result_number');
            $table->integer('essay_code');
            $table->float('essay_result');
            $table->integer('kg');
            $table->integer('wagon_number');
            $table->float('fee_amount');
            $table->char('bonus_or_discount'); // B-Bonifica | R-Rebaja | X-No tiene bonificación ni rebaja
            $table->float('total_bonus_or_discount')
                  ->default(0);
            $table->char('out_of_standard');
            $table->integer('certificate_number'); // S-Si | N-No
            $table->string('ctg_number');
            $table->foreignId('grain_id')
                  ->constrained('grains')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            $table->foreignId('carta_porte_id')
                  ->constrained('cartas_portes')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('camera_reports');
    }
}
