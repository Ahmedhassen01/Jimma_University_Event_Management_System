<?php

namespace Database\Seeders;

use App\Models\Speaker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpeakerSeeder extends Seeder
{
    public function run(): void
    {
        $speakers = [
            [
                'name' => 'Dr. Abebe Kebede',
                'title' => 'Professor',
                'position' => 'Head of Computer Science Department',
                'organization' => 'Jimma University',
                'email' => 'abebe.kebede@ju.edu.et',
                'phone' => '+251 911 223344',
                'bio' => 'Professor Abebe Kebede is a renowned computer scientist with over 20 years of experience in artificial intelligence and machine learning. He has published numerous research papers in international journals.',
                'expertise' => json_encode(['Artificial Intelligence', 'Machine Learning', 'Data Science']),
                'is_featured' => true,
                'is_active' => true,
                'twitter' => '@abebek',
                'linkedin' => 'https://linkedin.com/in/abebekebede',
                'website' => 'https://cs.ju.edu.et/prof-abebe',
            ],
            [
                'name' => 'Dr. Sofia Mohammed',
                'title' => 'Associate Professor',
                'position' => 'Researcher',
                'organization' => 'Jimma University Medical Center',
                'email' => 'sofia.m@ju.edu.et',
                'phone' => '+251 922 334455',
                'bio' => 'Dr. Sofia Mohammed is a medical researcher specializing in public health and epidemiology. She has led several international research projects on tropical diseases.',
                'expertise' => json_encode(['Public Health', 'Epidemiology', 'Medical Research']),
                'is_featured' => true,
                'is_active' => true,
                'linkedin' => 'https://linkedin.com/in/sofiamohammed',
            ],
            [
                'name' => 'Mr. Daniel Teklu',
                'title' => 'CEO',
                'position' => 'Founder and CEO',
                'organization' => 'EthioTech Solutions',
                'email' => 'daniel@ethiotech.com',
                'phone' => '+251 933 445566',
                'bio' => 'Daniel Teklu is a successful entrepreneur who founded EthioTech Solutions, one of Ethiopia\'s leading technology startups. He is passionate about fostering entrepreneurship among young Ethiopians.',
                'expertise' => json_encode(['Entrepreneurship', 'Technology', 'Business Development']),
                'is_featured' => true,
                'is_active' => true,
                'twitter' => '@danielteklu',
                'linkedin' => 'https://linkedin.com/in/danielteklu',
                'website' => 'https://ethiotech.com',
            ],
            [
                'name' => 'Dr. Helen Girma',
                'title' => 'Assistant Professor',
                'position' => 'Environmental Scientist',
                'organization' => 'Jimma University',
                'email' => 'helen.girma@ju.edu.et',
                'phone' => '+251 944 556677',
                'bio' => 'Dr. Helen Girma specializes in environmental science and sustainable development. Her research focuses on climate change adaptation in agricultural communities.',
                'expertise' => json_encode(['Environmental Science', 'Climate Change', 'Sustainable Development']),
                'is_featured' => false,
                'is_active' => true,
                'linkedin' => 'https://linkedin.com/in/helengirma',
            ],
            [
                'name' => 'Ms. Amina Yusuf',
                'title' => 'Manager',
                'position' => 'Cultural Program Coordinator',
                'organization' => 'Ministry of Culture',
                'email' => 'amina.y@culture.gov.et',
                'phone' => '+251 955 667788',
                'bio' => 'Amina Yusuf has over 15 years of experience in cultural preservation and promotion. She has organized numerous cultural events and festivals across Ethiopia.',
                'expertise' => json_encode(['Cultural Preservation', 'Event Management', 'Arts Administration']),
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Samuel Bekele',
                'title' => 'Professor',
                'position' => 'Agricultural Economist',
                'organization' => 'Jimma University',
                'email' => 'samuel.bekele@ju.edu.et',
                'phone' => '+251 966 778899',
                'bio' => 'Professor Samuel Bekele is an expert in agricultural economics and rural development. His work has influenced agricultural policies at the national level.',
                'expertise' => json_encode(['Agricultural Economics', 'Rural Development', 'Policy Analysis']),
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($speakers as $speakerData) {
            $slug = Str::slug($speakerData['name']);
            
            // Check if speaker already exists
            if (!Speaker::where('slug', $slug)->exists()) {
                $speakerData['slug'] = $slug;
                Speaker::create($speakerData);
            }
        }
    }
}