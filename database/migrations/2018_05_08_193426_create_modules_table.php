<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name', 150)->unique();
			$table->string('key', 50)->unique();
			$table->unsignedInteger('parent_id');
			$table->foreign('parent_id')->references('id')->on('modules');
			$table->unsignedInteger('role_id');
			$table->foreign('role_id')->references('id')->on('roles');
			$table->softDeletes();
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
        Schema::dropIfExists('modules');
    }
}
