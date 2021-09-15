<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('URL')->nullable();
            $table->string('Home_MP')->nullable();
            $table->string('Away_MP')->nullable();
            $table->string('Home_AGS')->nullable();
            $table->string('Away_AGS')->nullable();
            $table->string('Home_AGC')->nullable();
            $table->string('Away_AGC')->nullable();
            $table->string('Conclusion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('numbers');
    }
}
