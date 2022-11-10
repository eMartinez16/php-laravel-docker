<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrainEssaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grain_essays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grain_id')
                  ->constrained('grains')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            $table->foreignId('grain_param_id')
                  ->constrained('grain_params')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            $table->float('result');
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
        Schema::dropIfExists('grain_essays');
    }
}
