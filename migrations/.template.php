<?= "<?php";?>


use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class <?= $className ?> extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('<?= strtolower($className) ?>', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
        });

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('<?= strtolower($className) ?>');
    }
}
