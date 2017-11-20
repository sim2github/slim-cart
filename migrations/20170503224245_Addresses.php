<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class Addresses extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address1', 255);
            $table->string('address2', 255)->nullable();
            $table->string('city', 255);
            $table->string('postal_code', 255);
            $table->timestamps();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('addresses');
    }
}
