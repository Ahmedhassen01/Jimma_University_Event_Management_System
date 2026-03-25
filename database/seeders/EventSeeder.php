<?php
// database/seeders/EventSeeder.php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Get venue IDs from the database
        $mainAuditorium = DB::table('venues')->where('name', 'Main Auditorium')->first();
        $conferenceHall = DB::table('venues')->where('name', 'Conference Hall A')->first();
        $lectureHall = DB::table('venues')->where('name', 'Lecture Hall 101')->first();
        $seminarRoom = DB::table('venues')->where('name', 'Seminar Room 205')->first();
        $studentHall = DB::table('venues')->where('name', 'Student Union Hall')->first();
        $stadium = DB::table('venues')->where('name', 'University Stadium')->first();
        $vetHall = DB::table('venues')->where('name', 'Veterinary Conference Hall')->first();
        $engAuditorium = DB::table('venues')->where('name', 'Engineering Auditorium')->first();
        $ictRoom = DB::table('venues')->where('name', 'ICT Training Room')->first();
        $libraryRoom = DB::table('venues')->where('name', 'Library Conference Room')->first();

        // Create a directory for sample images if it doesn't exist
        $sampleImageDir = storage_path('app/public/events');
        if (!file_exists($sampleImageDir)) {
            mkdir($sampleImageDir, 0755, true);
        }

        // Sample image URLs (you can replace these with actual images)
        $sampleImages = [
            'conference' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop',
            'cultural' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&auto=format&fit=crop',
            'workshop' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&auto=format&fit=crop',
            'sports' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop',
            'seminar' => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&auto=format&fit=crop',
            'health' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&auto=format&fit=crop',
            'entrepreneurship' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=800&auto=format&fit=crop',
            'python' => 'https://images.unsplash.com/photo-1526379095098-d400fd0bf935?w=800&auto=format&fit=crop',
            'career' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&auto=format&fit=crop',
            'agriculture' => 'https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=800&auto=format&fit=crop',
            'engineering' => 'https://images.unsplash.com/photo-1581091226033-d5c48150dbaa?w=800&auto=format&fit=crop',
            'medical' => 'https://images.unsplash.com/photo-1584515933487-7798242938b6?w=800&auto=format&fit=crop',
        ];

        $events = [
            // UPCOMING EVENTS
            [
                'title' => 'Annual Research Conference 2024',
                'description' => 'Join us for the annual research conference showcasing the latest discoveries and innovations from Jimma University researchers. Keynote speeches from leading experts, paper presentations, and networking opportunities. Topics include: Artificial Intelligence, Public Health, Sustainable Agriculture, and Renewable Energy.',
                'start_date' => Carbon::now()->addDays(15)->setTime(9, 0, 0),
                'end_date' => Carbon::now()->addDays(17)->setTime(17, 0, 0),
                'venue_id' => $mainAuditorium->id ?? null,
                'building_id' => $mainAuditorium ? DB::table('venues')->where('id', $mainAuditorium->id)->value('building_id') : null,
                'campus_id' => $mainAuditorium ? DB::table('buildings')->where('id', DB::table('venues')->where('id', $mainAuditorium->id)->value('building_id'))->value('campus_id') : null,
                'venue' => 'Main Auditorium',
                'building' => 'Administration Building',
                'campus' => 'Main Campus',
                'event_type' => 'conference',
                'organizer' => 'Office of Vice President for Research',
                'contact_email' => 'research@ju.edu.et',
                'contact_phone' => '+251-47-111-2201',
                'max_attendees' => 500,
                'registered_attendees' => 120,
                'is_featured' => true,
                'is_public' => true,
                'requires_registration' => true,
                'tags' => json_encode(['research', 'conference', 'innovation', 'academic']),
                'image' => 'events/conference-1.jpg', // This will be downloaded below
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Cultural Day Celebration',
                'description' => 'Experience the rich cultural heritage of Ethiopia through music, dance, traditional food, and cultural exhibitions. All students, staff, and faculty are welcome to celebrate diversity. Featuring performances from various cultural troupes, traditional coffee ceremony, and ethnic fashion show.',
                'start_date' => Carbon::now()->addDays(10)->setTime(14, 0, 0),
                'end_date' => Carbon::now()->addDays(10)->setTime(22, 0, 0),
                'venue_id' => $studentHall->id ?? null,
                'building_id' => $studentHall ? DB::table('venues')->where('id', $studentHall->id)->value('building_id') : null,
                'campus_id' => $studentHall ? DB::table('buildings')->where('id', DB::table('venues')->where('id', $studentHall->id)->value('building_id'))->value('campus_id') : null,
                'venue' => 'Student Union Hall',
                'building' => 'Student Union Building',
                'campus' => 'Main Campus',
                'event_type' => 'cultural',
                'organizer' => 'Office of Student Affairs',
                'contact_email' => 'student.affairs@ju.edu.et',
                'contact_phone' => '+251-47-111-2202',
                'max_attendees' => 300,
                'registered_attendees' => 180,
                'is_featured' => true,
                'is_public' => true,
                'requires_registration' => true,
                'tags' => json_encode(['culture', 'music', 'food', 'celebration']),
                'image' => 'events/cultural-1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'AI and Machine Learning Workshop',
                'description' => 'Hands-on workshop on artificial intelligence and machine learning fundamentals. Learn Python, TensorFlow, and build your first ML model. Suitable for beginners with basic programming knowledge. Includes practical sessions and project work.',
                'start_date' => Carbon::now()->addDays(5)->setTime(10, 0, 0),
                'end_date' => Carbon::now()->addDays(5)->setTime(16, 0, 0),
                'venue_id' => $ictRoom->id ?? null,
                'building_id' => $ictRoom ? DB::table('venues')->where('id', $ictRoom->id)->value('building_id') : null,
                'campus_id' => $ictRoom ? DB::table('buildings')->where('id', DB::table('venues')->where('id', $ictRoom->id)->value('building_id'))->value('campus_id') : null,
                'venue' => 'ICT Training Room',
                'building' => 'ICT Center',
                'campus' => 'Institute of Technology',
                'event_type' => 'workshop',
                'organizer' => 'Department of Computer Science',
                'contact_email' => 'cs@ju.edu.et',
                'contact_phone' => '+251-47-111-2203',
                'max_attendees' => 40,
                'registered_attendees' => 35,
                'is_featured' => true,
                'is_public' => true,
                'requires_registration' => true,
                'tags' => json_encode(['ai', 'machine learning', 'workshop', 'technology']),
                'image' => 'events/workshop-1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Inter-Campus Football Tournament',
                'description' => 'Annual football tournament between different campuses. Teams from Main Campus, Technology Campus, Agriculture Campus, and Health Sciences Campus will compete for the championship trophy. Join us to cheer for your campus team!',
                'start_date' => Carbon::now()->addDays(20)->setTime(9, 0, 0),
                'end_date' => Carbon::now()->addDays(22)->setTime(18, 0, 0),
                'venue_id' => $stadium->id ?? null,
                'building_id' => null,
                'campus_id' => 1, // Main Campus
                'venue' => 'University Stadium',
                'building' => 'Sports Complex',
                'campus' => 'Main Campus',
                'event_type' => 'sports',
                'organizer' => 'Department of Sports',
                'contact_email' => 'sports@ju.edu.et',
                'contact_phone' => '+251-47-111-2204',
                'max_attendees' => 2000,
                'registered_attendees' => 0,
                'is_featured' => true,
                'is_public' => true,
                'requires_registration' => false,
                'tags' => json_encode(['sports', 'football', 'tournament', 'competition']),
                'image' => 'events/sports-1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Academic Writing Seminar',
                'description' => 'Learn effective academic writing techniques for research papers, theses, and publications. Topics include: structure, citation styles, avoiding plagiarism, and getting published in international journals.',
                'start_date' => Carbon::now()->addDays(8)->setTime(9, 30, 0),
                'end_date' => Carbon::now()->addDays(8)->setTime(13, 30, 0),
                'venue_id' => $libraryRoom->id ?? null,
                'building_id' => $libraryRoom ? DB::table('venues')->where('id', $libraryRoom->id)->value('building_id') : null,
                'campus_id' => $libraryRoom ? DB::table('buildings')->where('id', DB::table('venues')->where('id', $libraryRoom->id)->value('building_id'))->value('campus_id') : null,
                'venue' => 'Library Conference Room',
                'building' => 'Library Complex',
                'campus' => 'Main Campus',
                'event_type' => 'seminar',
                'organizer' => 'Graduate Studies Office',
                'contact_email' => 'graduate@ju.edu.et',
                'contact_phone' => '+251-47-111-2205',
                'max_attendees' => 80,
                'registered_attendees' => 45,
                'is_featured' => false,
                'is_public' => true,
                'requires_registration' => true,
                'tags' => json_encode(['academic', 'writing', 'research', 'seminar']),
                'image' => 'events/seminar-1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // ONGOING EVENTS
            [
                'title' => 'Public Health Awareness Week',
                'description' => 'A week-long campaign promoting public health awareness. Includes free health screenings, workshops on nutrition, exercise sessions, and talks from medical professionals. Open to all students, staff, and the community.',
                'start_date' => Carbon::now()->subDays(1)->setTime(10, 0, 0),
                'end_date' => Carbon::now()->addDays(4)->setTime(18, 0, 0),
                'venue_id' => $vetHall->id ?? null,
                'building_id' => $vetHall ? DB::table('venues')->where('id', $vetHall->id)->value('building_id') : null,
                'campus_id' => $vetHall ? DB::table('buildings')->where('id', DB::table('venues')->where('id', $vetHall->id)->value('building_id'))->value('campus_id') : null,
                'venue' => 'Veterinary Conference Hall',
                'building' => 'Veterinary Medicine Complex',
                'campus' => 'College of Agriculture and Veterinary Medicine',
                'event_type' => 'workshop',
                'organizer' => 'College of Health Sciences',
                'contact_email' => 'publichealth@ju.edu.et',
                'contact_phone' => '+251-47-111-2206',
                'max_attendees' => 200,
                'registered_attendees' => 150,
                'is_featured' => true,
                'is_public' => true,
                'requires_registration' => false,
                'tags' => json_encode(['health', 'wellness', 'screening', 'public health']),
                'image' => 'events/health-1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // UPCOMING WITH FEW SEATS LEFT
            [
                'title' => 'Entrepreneurship Bootcamp',
                'description' => 'Intensive bootcamp for aspiring entrepreneurs. Learn business planning, market research, funding strategies, and pitch development. Final day includes a pitch competition with prizes for the best ideas.',
                'start_date' => Carbon::now()->addDays(12)->setTime(8, 30, 0),
                'end_date' => Carbon::now()->addDays(14)->setTime(17, 0, 0),
                'venue_id' => $conferenceHall->id ?? null,
                'building_id' => $conferenceHall ? DB::table('venues')->where('id', $conferenceHall->id)->value('building_id') : null,
                'campus_id' => $conferenceHall ? DB::table('buildings')->where('id', DB::table('venues')->where('id', $conferenceHall->id)->value('building_id'))->value('campus_id') : null,
                'venue' => 'Conference Hall A',
                'building' => 'Administration Building',
                'campus' => 'Main Campus',
                'event_type' => 'workshop',
                'organizer' => 'Business Incubation Center',
                'contact_email' => 'incubation@ju.edu.et',
                'contact_phone' => '+251-47-111-2207',
                'max_attendees' => 100,
                'registered_attendees' => 92,
                'is_featured' => true,
                'is_public' => true,
                'requires_registration' => true,
                'tags' => json_encode(['entrepreneurship', 'business', 'startup', 'bootcamp']),
                'image' => 'events/entrepreneurship-1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // FULL EVENT
            [
                'title' => 'Introduction to Python Programming',
                'description' => 'A beginner-friendly workshop introducing Python programming. Learn variables, data types, control structures, functions, and basic data manipulation. Perfect for students with no prior programming experience.',
                'start_date' => Carbon::now()->addDays(3)->setTime(14, 0, 0),
                'end_date' => Carbon::now()->addDays(3)->setTime(17, 0, 0),
                'venue_id' => $ictRoom->id ?? null,
                'building_id' => $ictRoom ? DB::table('venues')->where('id', $ictRoom->id)->value('building_id') : null,
                'campus_id' => $ictRoom ? DB::table('buildings')->where('id', DB::table('venues')->where('id', $ictRoom->id)->value('building_id'))->value('campus_id') : null,
                'venue' => 'ICT Training Room',
                'building' => 'ICT Center',
                'campus' => 'Institute of Technology',
                'event_type' => 'workshop',
                'organizer' => 'ICT Center',
                'contact_email' => 'ict@ju.edu.et',
                'contact_phone' => '+251-47-111-2208',
                'max_attendees' => 40,
                'registered_attendees' => 40,
                'is_featured' => false,
                'is_public' => true,
                'requires_registration' => true,
                'tags' => json_encode(['python', 'programming', 'coding', 'workshop']),
                'image' => 'events/python-1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // PAST EVENT
            [
                'title' => 'Career Fair 2024',
                'description' => 'Annual career fair connecting students and graduates with employers. Meet representatives from leading companies, submit your CV, and learn about job opportunities. Participating companies include Ethio Telecom, Commercial Bank of Ethiopia, and many more.',
                'start_date' => Carbon::now()->subDays(10)->setTime(9, 0, 0),
                'end_date' => Carbon::now()->subDays(10)->setTime(17, 0, 0),
                'venue_id' => $studentHall->id ?? null,
                'building_id' => $studentHall ? DB::table('venues')->where('id', $studentHall->id)->value('building_id') : null,
                'campus_id' => $studentHall ? DB::table('buildings')->where('id', DB::table('venues')->where('id', $studentHall->id)->value('building_id'))->value('campus_id') : null,
                'venue' => 'Student Union Hall',
                'building' => 'Student Union Building',
                'campus' => 'Main Campus',
                'event_type' => 'conference',
                'organizer' => 'Career Development Center',
                'contact_email' => 'career@ju.edu.et',
                'contact_phone' => '+251-47-111-2209',
                'max_attendees' => 500,
                'registered_attendees' => 380,
                'is_featured' => false,
                'is_public' => true,
                'requires_registration' => true,
                'tags' => json_encode(['career', 'jobs', 'networking', 'employers']),
                'image' => 'events/career-1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // AGRICULTURE EVENT
            [
                'title' => 'Sustainable Agriculture Symposium',
                'description' => 'Symposium on sustainable agriculture practices in Ethiopia. Topics include climate-smart agriculture, water management, crop diversification, and market access for smallholder farmers.',
                'start_date' => Carbon::now()->addDays(25)->setTime(9, 0, 0),
                'end_date' => Carbon::now()->addDays(26)->setTime(17, 0, 0),
                'venue_id' => $vetHall->id ?? null,
                'building_id' => $vetHall ? DB::table('venues')->where('id', $vetHall->id)->value('building_id') : null,
                'campus_id' => $vetHall ? DB::table('buildings')->where('id', DB::table('venues')->where('id', $vetHall->id)->value('building_id'))->value('campus_id') : null,
                'venue' => 'Veterinary Conference Hall',
                'building' => 'Veterinary Medicine Complex',
                'campus' => 'College of Agriculture and Veterinary Medicine',
                'event_type' => 'conference',
                'organizer' => 'College of Agriculture',
                'contact_email' => 'agriculture@ju.edu.et',
                'contact_phone' => '+251-47-111-2210',
                'max_attendees' => 150,
                'registered_attendees' => 45,
                'is_featured' => false,
                'is_public' => true,
                'requires_registration' => true,
                'tags' => json_encode(['agriculture', 'sustainability', 'farming', 'symposium']),
                'image' => 'events/agriculture-1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // ENGINEERING EVENT
            [
                'title' => 'Engineering Innovation Expo',
                'description' => 'Showcase of engineering student projects and innovations. See working prototypes, posters, and demonstrations of projects from mechanical, electrical, civil, and computer engineering students.',
                'start_date' => Carbon::now()->addDays(18)->setTime(10, 0, 0),
                'end_date' => Carbon::now()->addDays(18)->setTime(16, 0, 0),
                'venue_id' => $engAuditorium->id ?? null,
                'building_id' => $engAuditorium ? DB::table('venues')->where('id', $engAuditorium->id)->value('building_id') : null,
                'campus_id' => $engAuditorium ? DB::table('buildings')->where('id', DB::table('venues')->where('id', $engAuditorium->id)->value('building_id'))->value('campus_id') : null,
                'venue' => 'Engineering Auditorium',
                'building' => 'Engineering Block A',
                'campus' => 'Institute of Technology',
                'event_type' => 'exhibition',
                'organizer' => 'Faculty of Engineering',
                'contact_email' => 'engineering@ju.edu.et',
                'contact_phone' => '+251-47-111-2211',
                'max_attendees' => 300,
                'registered_attendees' => 120,
                'is_featured' => true,
                'is_public' => true,
                'requires_registration' => false,
                'tags' => json_encode(['engineering', 'innovation', 'projects', 'exhibition']),
                'image' => 'events/engineering-1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // MEDICAL EVENT
            [
                'title' => 'Community Health Outreach',
                'description' => 'Free medical consultation and health education for the local community. Services include blood pressure screening, diabetes testing, health counseling, and medication distribution.',
                'start_date' => Carbon::now()->addDays(7)->setTime(9, 0, 0),
                'end_date' => Carbon::now()->addDays(7)->setTime(16, 0, 0),
                'venue_id' => null,
                'building_id' => null,
                'campus_id' => 5, // CHS
                'venue' => 'Teaching Hospital Grounds',
                'building' => 'Teaching Hospital',
                'campus' => 'College of Health Sciences',
                'event_type' => 'outreach',
                'organizer' => 'Medical Students Association',
                'contact_email' => 'msa@ju.edu.et',
                'contact_phone' => '+251-47-111-2212',
                'max_attendees' => null, // Unlimited
                'registered_attendees' => 0,
                'is_featured' => false,
                'is_public' => true,
                'requires_registration' => false,
                'tags' => json_encode(['health', 'community', 'medical', 'outreach']),
                'image' => 'events/medical-1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($events as $eventData) {
            $slug = Str::slug($eventData['title']);
            
            // Check if event already exists
            if (!Event::where('slug', $slug)->exists()) {
                // Store the image path before creating the event
                $imagePath = $eventData['image'] ?? null;
                unset($eventData['image']); // Remove image from creation data
                
                $eventData['slug'] = $slug;
                $event = Event::create($eventData);
                
                // Now handle the image if it exists
                if ($imagePath) {
                    // Map the image name to a sample URL
                    $imageMap = [
                        'events/conference-1.jpg' => $sampleImages['conference'],
                        'events/cultural-1.jpg' => $sampleImages['cultural'],
                        'events/workshop-1.jpg' => $sampleImages['workshop'],
                        'events/sports-1.jpg' => $sampleImages['sports'],
                        'events/seminar-1.jpg' => $sampleImages['seminar'],
                        'events/health-1.jpg' => $sampleImages['health'],
                        'events/entrepreneurship-1.jpg' => $sampleImages['entrepreneurship'],
                        'events/python-1.jpg' => $sampleImages['python'],
                        'events/career-1.jpg' => $sampleImages['career'],
                        'events/agriculture-1.jpg' => $sampleImages['agriculture'],
                        'events/engineering-1.jpg' => $sampleImages['engineering'],
                        'events/medical-1.jpg' => $sampleImages['medical'],
                    ];
                    
                    if (isset($imageMap[$imagePath])) {
                        // Download and save the image
                        try {
                            $imageContent = file_get_contents($imageMap[$imagePath]);
                            $filename = 'event-' . $event->id . '-' . time() . '.jpg';
                            Storage::disk('public')->put('events/' . $filename, $imageContent);
                            
                            // Update the event with the image path
                            $event->image = 'events/' . $filename;
                            $event->save();
                            
                            $this->command->info('Image added for event: ' . $event->title);
                        } catch (\Exception $e) {
                            $this->command->warn('Could not download image for: ' . $event->title);
                        }
                    }
                }
            }
        }
        
        $this->command->info('Events seeded successfully!');
    }
}