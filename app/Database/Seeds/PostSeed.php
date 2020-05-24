<?php

namespace App\Database\Seeds;

use Faker\Factory;
use Framework\ORM\Seeder;

/**
 * PostSeed
 * 
 * Insert new post row
 */
class PostSeed extends Seeder
{
    /**
     * insert post row
     *
     * @return void
     */
    public function sow(): void
    {
        $faker = Factory::create('en_US');
        
        for ($i = 0; $i < 10; $i++) {
            $post_title = $faker->sentence(8);

            $this->insert('posts', [
                'title' => $post_title,
                'slug' => generate_slug($post_title),
                'image' => $faker->imageUrl(1800, 700, 'nature'),
                'content' => $faker->text(2000)
            ]);
        }
    }
}
