<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('extension');
            $table->string('tamanno');
            $table->unsignedBigInteger('foods_id')->nullable();
            $table->timestamps();
            $table->foreign('foods_id')->references('id')->on('foods');
        });
        DB::statement("ALTER TABLE files ADD archivo LONGBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
