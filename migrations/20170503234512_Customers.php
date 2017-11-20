<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class Customers extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 90);
            $table->string('email', 70)->unique();
            $table->timestamps();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('customers');
    }
}
