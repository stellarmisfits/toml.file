<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function ($table) {
            $table->foreign('signing_key_id')->references('id')->on('accounts');
            $table->foreign('uri_request_signing_key_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function ($table) {
            $table->dropForeign(['signing_key_id']);
            $table->dropForeign(['uri_request_signing_key_id']);
        });
    }
}
