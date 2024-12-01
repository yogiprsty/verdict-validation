<?php

namespace Database\Factories;

use App\Models\Verdict;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Verdict>
 */
class VerdictFactory extends Factory
{
    protected $model = Verdict::class;

    public function definition()
    {
        return [
            // UUID for 'id' field
            'id' => Str::uuid(),

            // Litigant name
            'litigant' => $this->faker->name,

            // Defendant name
            'defendant' => $this->faker->name,

            // Case number
            'case_number' => $this->faker->unique()->numerify('C########'),

            // Case type (Gugatan or Permohonan)
            'case_type' => $this->faker->randomElement(['Gugatan', 'Permohonan']),

            // Sub-case type (this should be a realistic case sub-type for your domain)
            'sub_case_type' => $this->faker->word,

            // Verdict date
            'verdict_date' => $this->faker->date(),

            // URL to the valid verdict
            'url_to_valid_verdict' => $this->faker->url,

            // File verdict path (optional)
            'file_verdict_path' => $this->faker->filePath(),

            // Stamped file verdict path (optional)
            'file_verdict_stamped_path' => $this->faker->filePath(),

            // Timestamps will be automatically set by Eloquent
        ];
    }
}
