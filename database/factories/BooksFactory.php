<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Books;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BooksFactory extends Factory
{
    protected $model = Books::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->domainWord(),
            'pages' => fake()->numberBetween(0,10),
            'summary' => fake()->paragraph(),
            'editors' => fake()->name(),
            'tags'=>  fake()->word()
        ];
    }
}
