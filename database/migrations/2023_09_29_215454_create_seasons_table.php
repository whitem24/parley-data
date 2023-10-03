<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('ref');
            $table->integer('year');
            $table->string('startDate');
            $table->string('endDate');
            $table->string('displayName')->nullable();
            $table->string('shortDisplayName')->nullable();
            $table->string('abbreviation')->nullable();
            $table->unsignedBigInteger('league_id');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasons');
    }
}
