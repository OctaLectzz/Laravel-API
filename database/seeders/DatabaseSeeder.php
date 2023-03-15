<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\PostLike;
use App\Models\PostSave;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User Create
        User::factory(50)->create();
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password')
        ]);

        // Post Create
        Post::factory(50)->create();
        // Post Like Create
        PostLike::factory(500)->create();
        // Post Save Create
        PostSave::factory(500)->create();

        // Comment Create
        Comment::factory(100)->create();

        // Tag Create
        Tag::factory(50)->create();


    }
}
