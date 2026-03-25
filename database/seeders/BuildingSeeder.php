<?php
// database/seeders/BuildingSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        // Get campus IDs
        $mainCampus = DB::table('campuses')->where('slug', 'main-campus')->first();
        $cavm = DB::table('campuses')->where('slug', 'cavm')->first();
        $iot = DB::table('campuses')->where('slug', 'iot')->first();
        $cbe = DB::table('campuses')->where('slug', 'cbe')->first();
        $chs = DB::table('campuses')->where('slug', 'chs')->first();

        $buildings = [
            // Main Campus Buildings
            [
                'name' => 'Administration Building',
                'slug' => 'admin-building',
                'campus_id' => $mainCampus->id ?? 1,
                'code' => 'ADM',
                'description' => 'Main administration building',
                'floors' => 4,
                'contact_person' => 'Dr. Tesfaye Ayele',
                'contact_phone' => '+251-47-111-2001',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'College of Natural Sciences',
                'slug' => 'natural-sciences',
                'campus_id' => $mainCampus->id ?? 1,
                'code' => 'CNS',
                'description' => 'Natural sciences faculty building',
                'floors' => 5,
                'contact_person' => 'Prof. Alemitu Bekele',
                'contact_phone' => '+251-47-111-2002',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Library Complex',
                'slug' => 'library',
                'campus_id' => $mainCampus->id ?? 1,
                'code' => 'LIB',
                'description' => 'Main university library',
                'floors' => 6,
                'contact_person' => 'Mrs. Tirunesh Desta',
                'contact_phone' => '+251-47-111-2003',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Student Union Building',
                'slug' => 'student-union',
                'campus_id' => $mainCampus->id ?? 1,
                'code' => 'SUB',
                'description' => 'Student center and activities hub',
                'floors' => 3,
                'contact_person' => 'Mr. Tekle Berhan',
                'contact_phone' => '+251-47-111-2004',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // CAVM Buildings
            [
                'name' => 'Veterinary Medicine Complex',
                'slug' => 'vet-medicine',
                'campus_id' => $cavm->id ?? 2,
                'code' => 'VET',
                'description' => 'Veterinary medicine facilities',
                'floors' => 4,
                'contact_person' => 'Dr. Abebech Gobeze',
                'contact_phone' => '+251-47-111-2101',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Agricultural Research Center',
                'slug' => 'ag-research',
                'campus_id' => $cavm->id ?? 2,
                'code' => 'ARC',
                'description' => 'Agricultural research facilities',
                'floors' => 3,
                'contact_person' => 'Dr. Tadesse Worku',
                'contact_phone' => '+251-47-111-2102',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // IOT Buildings
            [
                'name' => 'Engineering Block A',
                'slug' => 'eng-block-a',
                'campus_id' => $iot->id ?? 3,
                'code' => 'ENG-A',
                'description' => 'Mechanical and civil engineering',
                'floors' => 4,
                'contact_person' => 'Dr. Yonas Ayele',
                'contact_phone' => '+251-47-111-2201',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Engineering Block B',
                'slug' => 'eng-block-b',
                'campus_id' => $iot->id ?? 3,
                'code' => 'ENG-B',
                'description' => 'Electrical and computer engineering',
                'floors' => 4,
                'contact_person' => 'Dr. Hailu Ayele',
                'contact_phone' => '+251-47-111-2202',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'ICT Center',
                'slug' => 'ict-center',
                'campus_id' => $iot->id ?? 3,
                'code' => 'ICT',
                'description' => 'Information technology center',
                'floors' => 3,
                'contact_person' => 'Mr. Samson Kebede',
                'contact_phone' => '+251-47-111-2203',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // CBE Buildings
            [
                'name' => 'Business School',
                'slug' => 'business-school',
                'campus_id' => $cbe->id ?? 4,
                'code' => 'BS',
                'description' => 'College of Business and Economics',
                'floors' => 4,
                'contact_person' => 'Dr. Meseret Tadesse',
                'contact_phone' => '+251-47-111-2301',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // CHS Buildings
            [
                'name' => 'Medical School',
                'slug' => 'medical-school',
                'campus_id' => $chs->id ?? 5,
                'code' => 'MED',
                'description' => 'College of Health Sciences',
                'floors' => 5,
                'contact_person' => 'Prof. Getachew Alemu',
                'contact_phone' => '+251-47-111-2401',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Teaching Hospital',
                'slug' => 'teaching-hospital',
                'campus_id' => $chs->id ?? 5,
                'code' => 'HOSP',
                'description' => 'Jimma University Teaching Hospital',
                'floors' => 8,
                'contact_person' => 'Dr. Genet Mekonnen',
                'contact_phone' => '+251-47-111-2402',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($buildings as $building) {
            DB::table('buildings')->insert($building);
        }
        
        $this->command->info('Buildings seeded successfully!');
    }
}