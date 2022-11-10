<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiscalConditionIdToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
          $table->foreignId('fiscal_condition_id')->nullable()->constrained('condicion_fiscal');
          $table->dropColumn('fiscal_condition');    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
          $table->dropColumn('fiscal_condition_id');   
          $table->string('fiscal_condition')->nullable(); 
        });
    }
}
