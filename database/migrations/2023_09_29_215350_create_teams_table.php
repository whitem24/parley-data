<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('apiId');
            $table->string('uid');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('abbreviation')->nullable();
            $table->string('displayName');
            $table->string('shortDisplayName');
            $table->string('nickname')->nullable();
            $table->string('location');
            $table->string('color')->nullable();
            $table->string('alternateColor')->nullable();
            $table->string('isActive')->nullable();
            $table->string('isAllStar')->nullable();
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
        Schema::dropIfExists('teams');
    }
}
