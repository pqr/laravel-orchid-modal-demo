<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    public function up()
    {
        Schema::create(
            'contacts',
            function (Blueprint $table) {
                $table->bigIncrements('id');

                $table->string('name');

                $table->timestamps();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
