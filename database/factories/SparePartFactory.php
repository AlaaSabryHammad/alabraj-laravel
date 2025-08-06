<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SparePart>
 */
class SparePartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['محرك', 'كهرباء', 'هيدروليك', 'فرامل', 'تبريد', 'عادم', 'أخرى'];
        $sparePartNames = [
            'فلتر زيت المحرك',
            'فلتر الهواء',
            'شمعات الإشعال',
            'سير المولد',
            'طرمبة المياه',
            'بطارية 12 فولت',
            'مصابيح LED',
            'فيوز كهربائي',
            'زيت هيدروليك',
            'خراطيم هيدروليك',
            'أقراص فرامل',
            'زيت فرامل',
            'مروحة التبريد',
            'ترموستات',
            'كاتم الصوت',
            'أنبوب العادم'
        ];

        return [
            'code' => 'SP' . fake()->unique()->numberBetween(1000, 9999),
            'name' => fake()->randomElement($sparePartNames),
            'unit_price' => fake()->randomFloat(2, 10, 1000),
            'category' => fake()->randomElement($categories),
            'description' => fake()->text(200),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
