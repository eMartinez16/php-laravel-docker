<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifDebeHaberToCurrentAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('current_accounts', function (Blueprint $table) {
            $table->float('debe')->change();
            $table->float('haber')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('current_accounts', function (Blueprint $table) {
            $table->integer('debe')->change();
            $table->integer('haber')->change();            
        });
    }
}
