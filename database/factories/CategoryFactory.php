<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoriesByType = [
            'Food' => ['restaurant', 'cafe', 'coffee shops'],
            'Outdoor' => [
                'nature', 'beach', 'waterfront', 'hiking', 'adventure', 'botanical garden', 'picnic',
            ],
            'Entertainment' => [
                'movie theater', 'live music', 'concerts', 'art galleries', 'museums', 'comedy clubs', 'arcades',
            ],
            'Activities' => ['spa', 'wellness', 'cooking classes', 'dance classes', 'escape rooms'],
            'Special Occasions' => [
                'anniversary', 'engagement locations', 'hotel',
            ],
        ];

        $randomType = Type::inRandomOrder()->first();
        $randomCategory = $this->faker->randomElement($categoriesByType[$randomType->name]);

        return [
            'type_id' => $randomType->id,
            'name' => $randomCategory,
        ];
    }
}
