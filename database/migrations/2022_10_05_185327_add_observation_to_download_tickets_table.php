<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObservationToDownloadTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('download_tickets', function (Blueprint $table) {
            $table->longText('observation')
                  ->nullable()
                  ->after('condition');
            $table->string('port')
                  ->nullable()
                  ->after('observation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('download_tickets', function (Blueprint $table) {
            $table->dropColumn('observation');
            $table->dropColumn('port');
        });
    }
}
