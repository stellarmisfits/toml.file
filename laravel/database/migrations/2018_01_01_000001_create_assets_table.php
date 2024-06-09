<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('account_id');

            $table->string('name');
            $table->string('code');
            $table->string('description');
            $table->string('code_template')->nullable();
            // $table->string('issuer'); // obtain through account_id
            $table->string('status')->nullable();
            $table->integer('display_decimals')->default(0);
            $table->text('conditions')->nullable();
            // $table->string('image'); // obtain through media library
            $table->bigInteger('fixed_number')->nullable();
            $table->bigInteger('max_number')->nullable();
            $table->boolean('is_unlimited')->default(false);
            $table->boolean('anchored')->default(false);
            $table->enum('anchor_asset_type', ['fiat', 'crypto', 'stock', 'bond', 'commodity', 'realestate', 'other'])->nullable();

            $table->string('anchor_asset')->nullable();
            $table->string('redemption_instructions', 1000)->nullable();
            $table->string('collateral_addresses', 1000)->nullable();
            $table->string('collateral_address_messages', 1000)->nullable();
            $table->string('collateral_address_signatures', 1000)->nullable();
            $table->boolean('regulated')->default(false);
            $table->string('approval_server')->nullable();
            $table->string('approval_criteria', 1000)->nullable();

            $table->timestamps();

            $table->unique('code');
            $table->index('code');
            $table->index('account_id');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
}
