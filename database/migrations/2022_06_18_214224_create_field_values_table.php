<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_values', function (Blueprint $table) {
            $table->integer('subscriber_id');
            $table->integer('field_id');
            $table->string('value');
            $table->foreign('subscriber_id')->references('id')->on('subscribers');
            $table->foreign('field_id')->references('id')->on('fields');
        });
    }

   /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('field_values');
    }
};
