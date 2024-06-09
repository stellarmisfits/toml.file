<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->unsignedBigInteger('team_id')->index();
            $table->unsignedBigInteger('organization_id')->index()->nullable();
            $table->string('name');
            $table->string('alias')->unique()->index();
            $table->string('public_key', 56)->unique()->index();
            $table->boolean('verified')->nullable();
            $table->string('verification_tx', 1000)->nullable();
            $table->string('home_domain')->nullable(); // always obtained from horizon api
            $table->dateTime('home_domain_updated_at')->nullable(); // always obtained from horizon api
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams');
            //$table->foreign('organization_id')->references('id')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
