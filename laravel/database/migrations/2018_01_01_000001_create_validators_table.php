<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->unsignedBigInteger('team_id');

            // begin sep-0001 properties
            // https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0001.md#validator-information
            $table->string('name');
            $table->unsignedBigInteger('account_id');
            $table->string('alias', 15)->unique()->index();
            $table->string('host')->nullable();
            $table->string('history')->nullable();
            // end sep-0001 properties

            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('validators');
    }
}
