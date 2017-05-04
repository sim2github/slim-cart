<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class Products extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('products', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->float('price')->unsigned();
            $table->string('image');
            $table->integer('stock')->unsigned();
            $table->timestamps();
        });

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('products');
    }
}
