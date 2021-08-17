<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Tag;
class PostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // создать связи между постами и тегами
        foreach(Post::all() as $post) {
            foreach(Tag::all() as $tag) {
                if (rand(1, 20) == 10) {
                    $post->tags()->attach($tag->id);
                }
            }
        }
    }
}
