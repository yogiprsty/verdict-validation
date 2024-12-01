<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VerdictSeeder extends Seeder
{
    public function run()
    {
        // Inserting sample data into the 'verdicts' table
        DB::table('verdicts')->insert([
            [
                'id' => Str::uuid(),
                'litigant' => 'John Doe',
                'defendant' => 'Jane Smith',
                'case_number' => 'CV001',
                'case_type' => 'Gugatan',
                'sub_case_type' => 'Perdata',
                'verdict_date' => Carbon::parse('2024-01-15'),
                'url_to_valid_verdict' => 'https://example.com/verdicts/cv001',
                'file_verdict_path' => '/path/to/verdict/file1.pdf',
                'file_verdict_stamped_path' => '/path/to/verdict/file1_stamped.pdf',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => Str::uuid(),
                'litigant' => 'Alice Brown',
                'defendant' => 'Bob Johnson',
                'case_number' => 'CV002',
                'case_type' => 'Permohonan',
                'sub_case_type' => 'Pelanggaran',
                'verdict_date' => Carbon::parse('2024-02-20'),
                'url_to_valid_verdict' => 'https://example.com/verdicts/cv002',
                'file_verdict_path' => '/path/to/verdict/file2.pdf',
                'file_verdict_stamped_path' => '/path/to/verdict/file2_stamped.pdf',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => Str::uuid(),
                'litigant' => 'Chris Green',
                'defendant' => 'Patricia White',
                'case_number' => 'CV003',
                'case_type' => 'Gugatan',
                'sub_case_type' => 'Keluarga',
                'verdict_date' => Carbon::parse('2024-03-10'),
                'url_to_valid_verdict' => 'https://example.com/verdicts/cv003',
                'file_verdict_path' => '/path/to/verdict/file3.pdf',
                'file_verdict_stamped_path' => '/path/to/verdict/file3_stamped.pdf',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Add more entries as needed
        ]);
    }
}
