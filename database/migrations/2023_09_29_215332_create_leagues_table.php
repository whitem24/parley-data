<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->string('apiId');
            $table->string('uid');
            $table->string('guid')->nullable();
            $table->string('ref');
            $table->string('name');
            $table->string('slug');
            $table->string('midsizeName')->nullable();
            $table->string('alternateId')->nullable();
            $table->string('abbreviation')->nullable();
            $table->string('shortName')->nullable();
            $table->boolean('isTournament')->nullable();
            $table->string('seasonsRef')->nullable();
            $table->unsignedBigInteger('sport_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leagues');
    }
}
