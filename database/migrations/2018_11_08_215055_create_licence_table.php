<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licences', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('client_id');
			$table->foreign('client_id')->references('id')->on('oauth_clients');
			$table->string('licence', 100)->unique();
			$table->string('token_id', 100)->nullable();
			$table->foreign('token_id')->references('id')->on('oauth_access_tokens')->nullable();
			$table->string('ip', 100)->nullable();
			$table->string('hostid', 100)->nullable();
			$table->string('macAddress', 100)->nullable();
			$table->unsignedInteger('licence_amount');
			$table->longText('remoteips')->nullable();
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
        Schema::dropIfExists('licenses');
    }
}
