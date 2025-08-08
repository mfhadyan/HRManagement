<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Announcement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraphs(3, true),
            'department_id' => Department::inRandomOrder()->first()?->id,
            'created_by' => Employee::inRandomOrder()->first()?->id ?? 1,
            'attachment' => null,
        ];
    }

    /**
     * Indicate that the announcement is for all departments.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forAllDepartments()
    {
        return $this->state(function (array $attributes) {
            return [
                'department_id' => null,
            ];
        });
    }

    /**
     * Indicate that the announcement has an attachment.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withAttachment()
    {
        return $this->state(function (array $attributes) {
            return [
                'attachment' => 'announcements/sample-document.pdf',
            ];
        });
    }
}
