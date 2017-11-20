<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class Orders extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->string('hash');
            $table->float('total')->unsigned();
            $table->integer('address_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->tinyInteger('paid')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('orders');
    }
}
