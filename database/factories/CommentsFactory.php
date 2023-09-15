<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Comments;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */

 /*

    $table->id();
    $table->string('comment')->require();
    $table->integer('note')->max(10);
    $table->timestamp("comment_updated_at");

*/
class CommentsFactory extends Factory
{
    protected $model = Comments::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comment' => fake()->paragraph(),
            'note' => fake()->numberBetween(0,10),
            'comment_updated_at' => now(),
        ];
    }
}
