<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Author;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'cover_picture' => 'default_picture_' . rand(1,5) . '.jpg',
            'summary' => $this->faker->paragraph(),
            'author_id' => rand(1, Author::count()),
            'category_id' => rand(1, Category::count()),
        ];
    }
}
