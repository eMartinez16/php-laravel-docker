<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCurrentAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('current_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) {
                $table->$table->dropColumn('comprobante_id');
            }

            $table->bigInteger('nro_voucher')->after('haber');
            $table->bigInteger('invoice_id')->after('haber');
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
            $table->dropColumn('nro_voucher');
            $table->dropColumn('invoice_id');
        });
    }
}
