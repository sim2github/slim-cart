<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class ProductsExamples extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $data = array(
            array( 'title' => 'Clarity', 'slug' => 'clarity', 'description' => 'Restores mana to the target unit over time. If the unit is attacked, the effect is lost.', 'price' => 5.5, 'image' => 'https://placeholdit.imgix.net/~text?txtsize=50&txt=Product%20image&w=800&h=500', 'stock' => 5, 'created_at' => '2016-05-28 05:10:00', 'updated_at' => '2016-05-28 05:10:00', ),
            array( 'title' => 'Faerie Fire', 'slug' => 'faerie-fire', 'description' => 'Consume the Faerie Fire to instantly restore 75 health.', 'price' => 10.5, 'image' => 'https://placeholdit.imgix.net/~text?txtsize=50&txt=Product%20image&w=800&h=500', 'stock' => 10, 'created_at' => '2016-05-28 05:14:06', 'updated_at' => '2016-05-28 05:14:06', ),
            array( 'title' => 'Enchanted Mango', 'slug' => 'enchanted-mango', 'description' => 'Consume the mango to instantly restore 150 mana. Hold Control to use Enchanted Mango on an allied hero.', 'price' => 12, 'image' => 'https://placeholdit.imgix.net/~text?txtsize=50&txt=Product%20image&w=800&h=500', 'stock' => 5, 'created_at' => '2016-05-28 05:17:32', 'updated_at' => '2016-05-28 05:17:32', ),
            array( 'title' => 'Tango', 'slug' => 'tango', 'description' => 'Consume a target tree or ward to gradually restore health.  Comes with 4 charges. Can be used on an allied hero to give them one Tango.', 'price' => 6.8, 'image' => 'https://placeholdit.imgix.net/~text?txtsize=50&txt=Product%20image&w=800&h=500', 'stock' => 5, 'created_at' => '2016-05-28 05:17:32', 'updated_at' => '2016-05-28 05:17:32', ),
            array( 'title' => 'Healing Salve', 'slug' => 'healing-salve', 'description' => 'Restores health to the target unit over time. If the unit is attacked, the effect is lost.', 'price' => 4.8, 'image' => 'https://placeholdit.imgix.net/~text?txtsize=50&txt=Product%20image&w=800&h=500', 'stock' => 10, 'created_at' => '2016-05-28 05:18:32', 'updated_at' => '2016-05-28 05:18:32', ),
        );
        
        Capsule::table('products')->insert($data);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::table('products')
            ->where('title', [
                    'Clarity', 'Faerie Fire', 'Enchanted Mango', 'Tango',   'Healing Salve',
            ])->delete();
    }
}
