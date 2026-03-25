<?php
// database/seeders/VenueSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        // First, let's check if we need to create a special building for standalone venues
        // Get building IDs
        $adminBuilding = DB::table('buildings')->where('slug', 'admin-building')->first();
        $library = DB::table('buildings')->where('slug', 'library')->first();
        $studentUnion = DB::table('buildings')->where('slug', 'student-union')->first();
        $vetComplex = DB::table('buildings')->where('slug', 'vet-medicine')->first();
        $agResearch = DB::table('buildings')->where('slug', 'ag-research')->first();
        $engBlockA = DB::table('buildings')->where('slug', 'eng-block-a')->first();
        $ictCenter = DB::table('buildings')->where('slug', 'ict-center')->first();
        $businessSchool = DB::table('buildings')->where('slug', 'business-school')->first();
        $medicalSchool = DB::table('buildings')->where('slug', 'medical-school')->first();

        // For standalone venues, we need a valid building_id
        // Let's get the first building from Main Campus as default
        $defaultBuilding = DB::table('buildings')
            ->where('campus_id', function($query) {
                $query->select('id')
                    ->from('campuses')
                    ->where('slug', 'main-campus')
                    ->limit(1);
            })
            ->first();

        if (!$defaultBuilding) {
            // If no building found, create a default one
            $mainCampus = DB::table('campuses')->where('slug', 'main-campus')->first();
            if ($mainCampus) {
                $defaultBuildingId = DB::table('buildings')->insertGetId([
                    'name' => 'Sports Complex',
                    'slug' => 'sports-complex',
                    'campus_id' => $mainCampus->id,
                    'code' => 'SPC',
                    'description' => 'Sports facilities building',
                    'floors' => 2,
                    'is_active' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                
                $defaultBuilding = (object)['id' => $defaultBuildingId];
            }
        }

        $venues = [
            // Main Campus Venues
            [
                'name' => 'Main Auditorium',
                'slug' => 'main-auditorium',
                'building_id' => $adminBuilding->id ?? $defaultBuilding->id ?? 1,
                'type' => 'auditorium',
                'capacity' => 500,
                'description' => 'Main university auditorium for large events',
                'amenities' => json_encode(['projector', 'sound system', 'air conditioning', 'stage', 'dressing rooms']),
                'booking_fee' => null,
                'is_available' => true,
                'requires_approval' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Conference Hall A',
                'slug' => 'conference-hall-a',
                'building_id' => $adminBuilding->id ?? $defaultBuilding->id ?? 1,
                'type' => 'conference hall',
                'capacity' => 200,
                'description' => 'Conference hall for medium-sized events',
                'amenities' => json_encode(['projector', 'sound system', 'air conditioning', 'podium']),
                'booking_fee' => null,
                'is_available' => true,
                'requires_approval' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Lecture Hall 101',
                'slug' => 'lecture-hall-101',
                'building_id' => $adminBuilding->id ?? $defaultBuilding->id ?? 1,
                'type' => 'lecture hall',
                'capacity' => 150,
                'description' => 'Large lecture hall with modern facilities',
                'amenities' => json_encode(['projector', 'whiteboard', 'sound system', 'air conditioning']),
                'booking_fee' => null,
                'is_available' => true,
                'requires_approval' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Library Conference Room',
                'slug' => 'library-conference',
                'building_id' => $library->id ?? $defaultBuilding->id ?? 1,
                'type' => 'conference room',
                'capacity' => 80,
                'description' => 'Conference room in the library',
                'amenities' => json_encode(['projector', 'video conferencing', 'whiteboard']),
                'booking_fee' => null,
                'is_available' => true,
                'requires_approval' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Student Union Hall',
                'slug' => 'student-union-hall',
                'building_id' => $studentUnion->id ?? $defaultBuilding->id ?? 1,
                'type' => 'multipurpose hall',
                'capacity' => 300,
                'description' => 'Multipurpose hall for student events',
                'amenities' => json_encode(['stage', 'sound system', 'kitchen access']),
                'booking_fee' => null,
                'is_available' => true,
                'requires_approval' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // CAVM Venues
            [
                'name' => 'Veterinary Conference Hall',
                'slug' => 'vet-conference',
                'building_id' => $vetComplex->id ?? $defaultBuilding->id ?? 1,
                'type' => 'conference hall',
                'capacity' => 120,
                'description' => 'Conference hall for veterinary events',
                'amenities' => json_encode(['projector', 'sound system', 'air conditioning']),
                'booking_fee' => null,
                'is_available' => true,
                'requires_approval' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Agriculture Seminar Room',
                'slug' => 'ag-seminar',
                'building_id' => $agResearch->id ?? $defaultBuilding->id ?? 1,
                'type' => 'seminar room',
                'capacity' => 60,
                'description' => 'Seminar room for agricultural workshops',
                'amenities' => json_encode(['projector', 'whiteboard']),
                'booking_fee' => null,
                'is_available' => true,
                'requires_approval' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // IOT Venues
            [
                'name' => 'Engineering Auditorium',
                'slug' => 'eng-auditorium',
                'building_id' => $engBlockA->id ?? $defaultBuilding->id ?? 1,
                'type' => 'auditorium',
                'capacity' => 250,
                'description' => 'Auditorium for engineering events',
                'amenities' => json_encode(['projector', 'sound system', 'air conditioning']),
                'booking_fee' => null,
                'is_available' => true,
                'requires_approval' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'ICT Training Room',
                'slug' => 'ict-training',
                'building_id' => $ictCenter->id ?? $defaultBuilding->id ?? 1,
                'type' => 'computer lab',
                'capacity' => 40,
                'description' => 'Computer training room with 40 workstations',
                'amenities' => json_encode(['computers', 'projector', 'whiteboard', 'air conditioning']),
                'booking_fee' => null,
                'is_available' => true,
                'requires_approval' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // CBE Venues
            [
                'name' => 'Business School Auditorium',
                'slug' => 'business-auditorium',
                'building_id' => $businessSchool->id ?? $defaultBuilding->id ?? 1,
                'type' => 'auditorium',
                'capacity' => 200,
                'description' => 'Auditorium at the Business School',
                'amenities' => json_encode(['projector', 'sound system', 'air conditioning']),
                'booking_fee' => null,
                'is_available' => true,
                'requires_approval' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // CHS Venues
            [
                'name' => 'Medical Lecture Theater',
                'slug' => 'medical-theater',
                'building_id' => $medicalSchool->id ?? $defaultBuilding->id ?? 1,
                'type' => 'lecture theater',
                'capacity' => 300,
                'description' => 'Large lecture theater at the Medical School',
                'amenities' => json_encode(['projector', 'sound system', 'air conditioning', 'video conferencing']),
                'booking_fee' => null,
                'is_available' => true,
                'requires_approval' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // University Stadium - Now with a valid building_id
            [
                'name' => 'University Stadium',
                'slug' => 'university-stadium',
                'building_id' => $defaultBuilding->id ?? 1, // Use default building instead of null
                'type' => 'stadium',
                'capacity' => 5000,
                'description' => 'Main university stadium for sports events',
                'amenities' => json_encode(['field', 'stands', 'scoreboard', 'changing rooms', 'floodlights']),
                'booking_fee' => 5000.00,
                'is_available' => true,
                'requires_approval' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($venues as $venue) {
            DB::table('venues')->insert($venue);
        }
        
        $this->command->info('Venues seeded successfully!');
    }
}