<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class Payments extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->default(0);
            $table->tinyInteger('failed')->unsigned()->default(0);
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('payments');
    }
}
