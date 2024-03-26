<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->text(20);
        $slug = Str::slug($title);

        Storage::makeDirectory('projects_images');

        $image_url = Storage::putFileAs('projects_images', fake()->image(storage_path('app/public/projects_images'), 250, 250), "$slug.png" );
        $type_ids = Type::pluck('id')->toArray();
        $type_ids[] = null;

        return [
            'title' => $title,
            'slug' => $slug,
            'type_id' => Arr::random($type_ids),
            'content' => fake()->paragraphs(1, true),
            'image' => $image_url,

        ];
    }
}
