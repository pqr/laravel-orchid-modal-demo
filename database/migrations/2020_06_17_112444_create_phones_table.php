<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhonesTable extends Migration
{
    public function up()
    {
        Schema::create(
            'phones',
            function (Blueprint $table) {
                $table->bigIncrements('id');

                $table->string('number');
                $table->string('description');
                $table->foreignId('contact_id')->constrained();

                $table->timestamps();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('phones');
    }
}
