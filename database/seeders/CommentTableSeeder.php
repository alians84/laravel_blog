<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // создать 200 комментариев
        Comment::factory()->count(200)->create()->each(function ($comment){

        });
    }
}
