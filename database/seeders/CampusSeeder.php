<?php
// database/seeders/CampusSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CampusSeeder extends Seeder
{
    public function run(): void
    {
        $campuses = [
            [
                'name' => 'Main Campus',
                'slug' => 'main-campus',
                'description' => 'The main campus of Jimma University, hosting administrative offices and main academic buildings.',
                'location' => 'Jimma City, Oromia',
                'contact_email' => 'main.campus@ju.edu.et',
                'contact_phone' => '+251-47-111-2000',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'College of Agriculture and Veterinary Medicine',
                'slug' => 'cavm',
                'description' => 'Dedicated campus for agricultural studies and veterinary medicine.',
                'location' => 'Jimma City, Oromia',
                'contact_email' => 'cavm@ju.edu.et',
                'contact_phone' => '+251-47-111-2100',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Institute of Technology',
                'slug' => 'iot',
                'description' => 'Campus for engineering and technology programs.',
                'location' => 'Jimma City, Oromia',
                'contact_email' => 'iot@ju.edu.et',
                'contact_phone' => '+251-47-111-2200',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'College of Business and Economics',
                'slug' => 'cbe',
                'description' => 'Business and economics campus.',
                'location' => 'Jimma City, Oromia',
                'contact_email' => 'cbe@ju.edu.et',
                'contact_phone' => '+251-47-111-2300',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'College of Health Sciences',
                'slug' => 'chs',
                'description' => 'Medical and health sciences campus.',
                'location' => 'Jimma City, Oromia',
                'contact_email' => 'chs@ju.edu.et',
                'contact_phone' => '+251-47-111-2400',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Kitfu Campus',
                'slug' => 'kitfu',
                'description' => 'Additional campus for various programs.',
                'location' => 'Jimma City, Oromia',
                'contact_email' => 'kitfu@ju.edu.et',
                'contact_phone' => '+251-47-111-2500',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($campuses as $campus) {
            DB::table('campuses')->insert($campus);
        }
        
        $this->command->info('Campuses seeded successfully!');
    }
}