<?php

use Illuminate\Database\Seeder;

use App\Models\Profiler\ProfileSection;

class ProfileSectionCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProfileSection::create([
            'general_name' => 'BASIC',
            'display_name' => 'BASIC',
            'type' => 'private',
            'completion_time' => 0,
            'points' => 0,
            'status'  => 1,
            'order'  => 10,
            'translated'  => [],
        ]);

        ProfileSection::create([
            'general_name' => 'MY_PROFILE',
            'display_name' => 'My Profile',
            'type' => 'public',
            'completion_time' => 2,
            'points' => 100,
            'status'  => 1,
            'order'  => 10,
            'translated'  => [
                [
                    'con_lang' => 'US-EN',
                    'country_code' => 'US',
                    'language' => 'EN',
                    'text' => 'My Profile',
                    'description' => 'My Profile',
                ],
                [
                    'con_lang' => 'US-ES',
                    'country_code' => 'US',
                    'language' => 'ES',
                    'text' => 'Mi perfil',
                    'description' => 'Mi perfil',
                ],
                [
                    'con_lang' => 'CA-FR',
                    'country_code' => 'CA',
                    'language' => 'FR',
                    'text' => 'Mon profil',
                    'description' => 'Mon profil',
                ],
                [
                    'con_lang' => 'FR-FR',
                    'country_code' => 'FR',
                    'language' => 'FR',
                    'text' => 'Mon profil',
                    'description' => 'Mon profil',
                ],
                [
                    'con_lang' => 'FR-EN',
                    'country_code' => 'FR',
                    'language' => 'EN',
                    'text' => 'My Profile',
                    'description' => 'My Profile',
                ],

                [
                    'con_lang' => 'ES-EN',
                    'country_code' => 'ES',
                    'language' => 'EN',
                    'text' => 'My Profile',
                    'description' => 'My Profile',
                ],
                [
                    'con_lang' => 'ES-ES',
                    'country_code' => 'ES',
                    'language' => 'ES',
                    'text' => 'Mi perfil',
                    'description' => 'Mi perfil',
                ],
                [
                    'con_lang' => 'IT-IT',
                    'country_code' => 'IT',
                    'language' => 'IT',
                    'text' => 'Il mio profilo',
                    'description' => 'Il mio profilo',
                ],
                [
                    'con_lang' => 'IT-EN',
                    'country_code' => 'IT',
                    'language' => 'EN',
                    'text' => 'My Profile',
                    'description' => 'My Profile',
                ],
                [
                    'con_lang' => 'DE-DE',
                    'country_code' => 'DE',
                    'language' => 'DE',
                    'text' => 'Mein Profil',
                    'description' => 'Mein Profil',
                ],
                [
                    'con_lang' => 'DE-EN',
                    'country_code' => 'DE',
                    'language' => 'EN',
                    'text' => 'My Profile',
                    'description' => 'My Profile',
                ],
                [
                    'con_lang' => 'IN-EN',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'My Profile',
                    'description' => 'My Profile',
                ],
                [
                    'con_lang' => 'IN-HI',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'मेरी प्रोफाइल',
                    'description' => 'मेरी प्रोफाइल',
                ],
                [
                    'con_lang' => 'AU-EN',
                    'country_code' => 'AU',
                    'language' => 'EN',
                    'text' => 'My Profile',
                    'description' => 'My Profile',
                ]
            ],
        ]);

        ProfileSection::create([
            'general_name' => 'FAMILY',
            'display_name' => 'Family',
            'type' => 'public',
            'completion_time' => 2,
            'points' => 100,
            'status'  => 1,
            'order'  => 10,
            'translated'  => [
                [
                    'con_lang' => 'US-EN',
                    'country_code' => 'US',
                    'language' => 'EN',
                    'text' => 'Family',
                    'description' => 'Family',
                ],
                [
                    'con_lang' => 'US-ES',
                    'country_code' => 'US',
                    'language' => 'ES',
                    'text' => 'Familia',
                    'description' => 'Familia',
                ],
                [
                    'con_lang' => 'CA-FR',
                    'country_code' => 'CA',
                    'language' => 'FR',
                    'text' => 'La famille',
                    'description' => 'La famille',
                ],
                [
                    'con_lang' => 'FR-FR',
                    'country_code' => 'FR',
                    'language' => 'FR',
                    'text' => 'La famille',
                    'description' => 'La famille',
                ],
                [
                    'con_lang' => 'FR-EN',
                    'country_code' => 'FR',
                    'language' => 'EN',
                    'text' => 'Family',
                    'description' => 'Family',
                ],

                [
                    'con_lang' => 'ES-EN',
                    'country_code' => 'ES',
                    'language' => 'EN',
                    'text' => 'Family',
                    'description' => 'Family',
                ],
                [
                    'con_lang' => 'ES-ES',
                    'country_code' => 'ES',
                    'language' => 'ES',
                    'text' => 'Familia',
                    'description' => 'Familia',
                ],
                [
                    'con_lang' => 'IT-IT',
                    'country_code' => 'IT',
                    'language' => 'IT',
                    'text' => 'Famiglia',
                    'description' => 'Famiglia',
                ],
                [
                    'con_lang' => 'IT-EN',
                    'country_code' => 'IT',
                    'language' => 'EN',
                    'text' => 'Family',
                    'description' => 'Family',
                ],
                [
                    'con_lang' => 'DE-DE',
                    'country_code' => 'DE',
                    'language' => 'DE',
                    'text' => 'Familie',
                    'description' => 'Familie',
                ],
                [
                    'con_lang' => 'DE-EN',
                    'country_code' => 'DE',
                    'language' => 'EN',
                    'text' => 'Family',
                    'description' => 'Family',
                ],
                [
                    'con_lang' => 'IN-EN',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'Family',
                    'description' => 'Family',
                ],
                [
                    'con_lang' => 'IN-HI',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'परिवार',
                    'description' => 'परिवार',
                ],
                [
                    'con_lang' => 'AU-EN',
                    'country_code' => 'AU',
                    'language' => 'EN',
                    'text' => 'Family',
                    'description' => 'Family',
                ]
            ],
        ]);

        ProfileSection::create([
            'general_name' => 'AUTOMOTIVE',
            'display_name' => 'Automotive',
            'type' => 'public',
            'completion_time' => 2,
            'points' => 100,
            'status'  => 1,
            'order'  => 10,
            'translated'  => [
                [
                    'con_lang' => 'US-EN',
                    'country_code' => 'US',
                    'language' => 'EN',
                    'text' => 'Automotive',
                    'description' => 'Automotive',
                ],
                [
                    'con_lang' => 'US-ES',
                    'country_code' => 'US',
                    'language' => 'ES',
                    'text' => 'Automotriz',
                    'description' => 'Automotriz',
                ],
                [
                    'con_lang' => 'CA-FR',
                    'country_code' => 'CA',
                    'language' => 'FR',
                    'text' => 'Automobile',
                    'description' => 'Automobile',
                ],


                [
                    'con_lang' => 'FR-FR',
                    'country_code' => 'FR',
                    'language' => 'FR',
                    'text' => 'Automobile',
                    'description' => 'Automobile',
                ],
                [
                    'con_lang' => 'FR-EN',
                    'country_code' => 'FR',
                    'language' => 'EN',
                    'text' => 'Automotive',
                    'description' => 'Automotive',
                ],

                [
                    'con_lang' => 'ES-EN',
                    'country_code' => 'ES',
                    'language' => 'EN',
                    'text' => 'Automotive',
                    'description' => 'Automotive',
                ],
                [
                    'con_lang' => 'ES-ES',
                    'country_code' => 'ES',
                    'language' => 'ES',
                    'text' => 'Automotora',
                    'description' => 'Automotora',
                ],
                [
                    'con_lang' => 'IT-IT',
                    'country_code' => 'IT',
                    'language' => 'IT',
                    'text' => 'Settore automobilistico',
                    'description' => 'Settore automobilistico',
                ],
                [
                    'con_lang' => 'IT-EN',
                    'country_code' => 'IT',
                    'language' => 'EN',
                    'text' => 'Automotive',
                    'description' => 'Automotive',
                ],
                [
                    'con_lang' => 'DE-DE',
                    'country_code' => 'DE',
                    'language' => 'DE',
                    'text' => 'Automotive',
                    'description' => 'Automotive',
                ],
                [
                    'con_lang' => 'DE-EN',
                    'country_code' => 'DE',
                    'language' => 'EN',
                    'text' => 'Automotive',
                    'description' => 'Automotive',
                ],
                [
                    'con_lang' => 'IN-EN',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'Automotive',
                    'description' => 'Automotive',
                ],
                [
                    'con_lang' => 'IN-HI',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'मोटर वाहन',
                    'description' => 'मोटर वाहन',
                ],
                [
                    'con_lang' => 'AU-EN',
                    'country_code' => 'AU',
                    'language' => 'EN',
                    'text' => 'Automotive',
                    'description' => 'Automotive',
                ]
            ],
        ]);

        ProfileSection::create([
            'general_name' => 'EMPLOYMENT',
            'display_name' => 'Employment',
            'type' => 'public',
            'completion_time' => 2,
            'points' => 100,
            'status'  => 1,
            'order'  => 10,
            'translated'  => [
                [
                    'con_lang' => 'US-EN',
                    'country_code' => 'US',
                    'language' => 'EN',
                    'text' => 'Employment',
                    'description' => 'Employment',
                ],
                [
                    'con_lang' => 'US-ES',
                    'country_code' => 'US',
                    'language' => 'ES',
                    'text' => 'Empleo',
                    'description' => 'Empleo',
                ],
                [
                    'con_lang' => 'CA-FR',
                    'country_code' => 'CA',
                    'language' => 'FR',
                    'text' => 'Emploi',
                    'description' => 'Emploi',
                ],

                [
                    'con_lang' => 'FR-FR',
                    'country_code' => 'FR',
                    'language' => 'FR',
                    'text' => 'Emploi',
                    'description' => 'Emploi',
                ],
                [
                    'con_lang' => 'FR-EN',
                    'country_code' => 'FR',
                    'language' => 'EN',
                    'text' => 'Employment',
                    'description' => 'Employment',
                ],

                [
                    'con_lang' => 'ES-EN',
                    'country_code' => 'ES',
                    'language' => 'EN',
                    'text' => 'Employment',
                    'description' => 'Employment',
                ],
                [
                    'con_lang' => 'ES-ES',
                    'country_code' => 'ES',
                    'language' => 'ES',
                    'text' => 'Empleo',
                    'description' => 'Empleo',
                ],
                [
                    'con_lang' => 'IT-IT',
                    'country_code' => 'IT',
                    'language' => 'IT',
                    'text' => 'occupazione',
                    'description' => 'occupazione',
                ],
                [
                    'con_lang' => 'IT-EN',
                    'country_code' => 'IT',
                    'language' => 'EN',
                    'text' => 'Employment',
                    'description' => 'Employment',
                ],
                [
                    'con_lang' => 'DE-DE',
                    'country_code' => 'DE',
                    'language' => 'DE',
                    'text' => 'Beschäftigung',
                    'description' => 'Beschäftigung',
                ],
                [
                    'con_lang' => 'DE-EN',
                    'country_code' => 'DE',
                    'language' => 'EN',
                    'text' => 'Employment',
                    'description' => 'Employment',
                ],
                [
                    'con_lang' => 'IN-EN',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'Employment',
                    'description' => 'Employment',
                ],
                [
                    'con_lang' => 'IN-HI',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'रोज़गार',
                    'description' => 'रोज़गार',
                ],
                [
                    'con_lang' => 'AU-EN',
                    'country_code' => 'AU',
                    'language' => 'EN',
                    'text' => 'Employment',
                    'description' => 'Employment',
                ]

            ],
        ]);

        ProfileSection::create([
            'general_name' => 'TRAVEL_/_LEISURE',
            'display_name' => 'Travel / Leisure',
            'type' => 'public',
            'completion_time' => 2,
            'points' => 100,
            'status'  => 1,
            'order'  => 10,
            'translated'  => [
                [
                    'con_lang' => 'US-EN',
                    'country_code' => 'US',
                    'language' => 'EN',
                    'text' => 'Travel / Leisure',
                    'description' => 'Travel / Leisure',
                ],
                [
                    'con_lang' => 'US-ES',
                    'country_code' => 'US',
                    'language' => 'ES',
                    'text' => 'Viajes / Ocio',
                    'description' => 'Viajes / Ocio',
                ],
                [
                    'con_lang' => 'CA-FR',
                    'country_code' => 'CA',
                    'language' => 'FR',
                    'text' => 'Voyage / Loisirs',
                    'description' => 'Voyage / Loisirs',
                ],

                [
                    'con_lang' => 'FR-FR',
                    'country_code' => 'FR',
                    'language' => 'FR',
                    'text' => 'Voyage / Loisirs',
                    'description' => 'Voyage / Loisirs',
                ],
                [
                    'con_lang' => 'FR-EN',
                    'country_code' => 'FR',
                    'language' => 'EN',
                    'text' => 'Travel / Leisure',
                    'description' => 'Travel / Leisure',
                ],

                [
                    'con_lang' => 'ES-EN',
                    'country_code' => 'ES',
                    'language' => 'EN',
                    'text' => 'Travel / Leisure',
                    'description' => 'Travel / Leisure',
                ],
                [
                    'con_lang' => 'ES-ES',
                    'country_code' => 'ES',
                    'language' => 'ES',
                    'text' => 'Viajes / Ocio',
                    'description' => 'Viajes / Ocio',
                ],
                [
                    'con_lang' => 'IT-IT',
                    'country_code' => 'IT',
                    'language' => 'IT',
                    'text' => 'Viaggi / tempo libero',
                    'description' => 'Viaggi / tempo libero',
                ],
                [
                    'con_lang' => 'IT-EN',
                    'country_code' => 'IT',
                    'language' => 'EN',
                    'text' => 'Travel / Leisure',
                    'description' => 'Travel / Leisure',
                ],
                [
                    'con_lang' => 'DE-DE',
                    'country_code' => 'DE',
                    'language' => 'DE',
                    'text' => 'Reise / Freizeit',
                    'description' => 'Reise / Freizeit',
                ],
                [
                    'con_lang' => 'DE-EN',
                    'country_code' => 'DE',
                    'language' => 'EN',
                    'text' => 'Travel / Leisure',
                    'description' => 'Travel / Leisure',
                ],
                [
                    'con_lang' => 'IN-EN',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'Travel / Leisure',
                    'description' => 'Travel / Leisure',
                ],
                [
                    'con_lang' => 'IN-HI',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'यात्रा / आराम',
                    'description' => 'यात्रा / आराम',
                ],
                [
                    'con_lang' => 'AU-EN',
                    'country_code' => 'AU',
                    'language' => 'EN',
                    'text' => 'Travel / Leisure',
                    'description' => 'Travel / Leisure',
                ]

            ],
        ]);

        ProfileSection::create([
            'general_name' => 'INTERNET_/_GAMES_/_SPORTS',
            'display_name' => 'Internet / Games / Sports',
            'type' => 'public',
            'completion_time' => 2,
            'points' => 100,
            'status'  => 1,
            'order'  => 10,
            'translated'  => [
                [
                    'con_lang' => 'US-EN',
                    'country_code' => 'US',
                    'language' => 'EN',
                    'text' => 'Internet / Games / Sports',
                    'description' => 'Internet / Games / Sports',
                ],
                [
                    'con_lang' => 'US-ES',
                    'country_code' => 'US',
                    'language' => 'ES',
                    'text' => 'Internet / Juegos / Deportes',
                    'description' => 'Internet / Juegos / Deportes',
                ],
                [
                    'con_lang' => 'CA-FR',
                    'country_code' => 'CA',
                    'language' => 'FR',
                    'text' => 'Internet / Jeux / Sports',
                    'description' => 'Internet / Jeux / Sports',
                ],

                [
                    'con_lang' => 'FR-FR',
                    'country_code' => 'FR',
                    'language' => 'FR',
                    'text' => 'Internet / Jeux / Sports',
                    'description' => 'Internet / Jeux / Sports',
                ],
                [
                    'con_lang' => 'FR-EN',
                    'country_code' => 'FR',
                    'language' => 'EN',
                    'text' => 'Internet / Games / Sports',
                    'description' => 'Internet / Games / Sports',
                ],

                [
                    'con_lang' => 'ES-EN',
                    'country_code' => 'ES',
                    'language' => 'EN',
                    'text' => 'Internet / Games / Sports',
                    'description' => 'Internet / Games / Sports',
                ],
                [
                    'con_lang' => 'ES-ES',
                    'country_code' => 'ES',
                    'language' => 'ES',
                    'text' => 'Internet / Juegos / Deportes',
                    'description' => 'Internet / Juegos / Deportes',
                ],
                [
                    'con_lang' => 'IT-IT',
                    'country_code' => 'IT',
                    'language' => 'IT',
                    'text' => 'Internet / Giochi / Sport',
                    'description' => 'Internet / Giochi / Sport',
                ],
                [
                    'con_lang' => 'IT-EN',
                    'country_code' => 'IT',
                    'language' => 'EN',
                    'text' => 'Internet / Games / Sports',
                    'description' => 'Internet / Games / Sports',
                ],
                [
                    'con_lang' => 'DE-DE',
                    'country_code' => 'DE',
                    'language' => 'DE',
                    'text' => 'Internet / Spiele / Sport',
                    'description' => 'Internet / Spiele / Sport',
                ],
                [
                    'con_lang' => 'DE-EN',
                    'country_code' => 'DE',
                    'language' => 'EN',
                    'text' => 'Internet / Games / Sports',
                    'description' => 'Internet / Games / Sports',
                ],
                [
                    'con_lang' => 'IN-EN',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'Internet / Games / Sports',
                    'description' => 'Internet / Games / Sports',
                ],
                [
                    'con_lang' => 'IN-HI',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'इंटरनेट / गेम्स / खेल',
                    'description' => 'इंटरनेट / गेम्स / खेल',
                ],
                [
                    'con_lang' => 'AU-EN',
                    'country_code' => 'AU',
                    'language' => 'EN',
                    'text' => 'Internet / Games / Sports',
                    'description' => 'Internet / Games / Sports',
                ]
            ],
        ]);

        ProfileSection::create([
            'general_name' => 'HEALTH_/_FOOD',
            'display_name' => 'Health / Food',
            'type' => 'public',
            'completion_time' => 2,
            'points' => 100,
            'status'  => 1,
            'order'  => 10,
            'translated'  => [
                [
                    'con_lang' => 'US-EN',
                    'country_code' => 'US',
                    'language' => 'EN',
                    'text' => 'Health / Food',
                    'description' => 'Health / Food',
                ],
                [
                    'con_lang' => 'US-ES',
                    'country_code' => 'US',
                    'language' => 'ES',
                    'text' => 'Salud / AlimentaciÃ³n',
                    'description' => 'Salud / AlimentaciÃ³n',
                ],
                [
                    'con_lang' => 'CA-FR',
                    'country_code' => 'CA',
                    'language' => 'FR',
                    'text' => 'SantÃ© / Alimentation',
                    'description' => 'SantÃ© / Alimentation',
                ],

                [
                    'con_lang' => 'FR-FR',
                    'country_code' => 'FR',
                    'language' => 'FR',
                    'text' => 'SantÃ© / Alimentation',
                    'description' => 'SantÃ© / Alimentation',
                ],
                [
                    'con_lang' => 'FR-EN',
                    'country_code' => 'FR',
                    'language' => 'EN',
                    'text' => 'Health / Food',
                    'description' => 'Health / Food',
                ],

                [
                    'con_lang' => 'ES-EN',
                    'country_code' => 'ES',
                    'language' => 'EN',
                    'text' => 'Health / Food',
                    'description' => 'Health / Food',
                ],
                [
                    'con_lang' => 'ES-ES',
                    'country_code' => 'ES',
                    'language' => 'ES',
                    'text' => 'Comida saludable',
                    'description' => 'Comida saludable',
                ],
                [
                    'con_lang' => 'IT-IT',
                    'country_code' => 'IT',
                    'language' => 'IT',
                    'text' => 'Cibo sano',
                    'description' => 'Cibo sano',
                ],
                [
                    'con_lang' => 'IT-EN',
                    'country_code' => 'IT',
                    'language' => 'EN',
                    'text' => 'Health / Food',
                    'description' => 'Health / Food',
                ],
                [
                    'con_lang' => 'DE-DE',
                    'country_code' => 'DE',
                    'language' => 'DE',
                    'text' => 'Gesundes Essen',
                    'description' => 'Gesundes Essen',
                ],
                [
                    'con_lang' => 'DE-EN',
                    'country_code' => 'DE',
                    'language' => 'EN',
                    'text' => 'Health / Food',
                    'description' => 'Health / Food',
                ],
                [
                    'con_lang' => 'IN-EN',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'Health / Food',
                    'description' => 'Health / Food',
                ],
                [
                    'con_lang' => 'IN-HI',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'स्वास्थ्य भोजन',
                    'description' => 'स्वास्थ्य भोजन',
                ],
                [
                    'con_lang' => 'AU-EN',
                    'country_code' => 'AU',
                    'language' => 'EN',
                    'text' => 'Health / Food',
                    'description' => 'Health / Food',
                ]
            ],
        ]);

        ProfileSection::create([
            'general_name' => 'TECHNOLOGY',
            'display_name' => 'Technology',
            'type' => 'public',
            'completion_time' => 2,
            'points' => 100,
            'status'  => 1,
            'order'  => 10,
            'translated'  => [
                [
                    'con_lang' => 'US-EN',
                    'country_code' => 'US',
                    'language' => 'EN',
                    'text' => 'Technology',
                    'description' => 'Technology',
                ],
                [
                    'con_lang' => 'US-ES',
                    'country_code' => 'US',
                    'language' => 'ES',
                    'text' => 'Tecnologia',
                    'description' => 'Tecnologia',
                ],
                [
                    'con_lang' => 'CA-FR',
                    'country_code' => 'CA',
                    'language' => 'FR',
                    'text' => 'La technologie',
                    'description' => 'La technologie',
                ],
                [
                    'con_lang' => 'FR-FR',
                    'country_code' => 'FR',
                    'language' => 'FR',
                    'text' => 'La technologie',
                    'description' => 'La technologie',
                ],
                [
                    'con_lang' => 'FR-EN',
                    'country_code' => 'FR',
                    'language' => 'EN',
                    'text' => 'Technology',
                    'description' => 'Technology',
                ],

                [
                    'con_lang' => 'ES-EN',
                    'country_code' => 'ES',
                    'language' => 'EN',
                    'text' => 'Technology',
                    'description' => 'Technology',
                ],
                [
                    'con_lang' => 'ES-ES',
                    'country_code' => 'ES',
                    'language' => 'ES',
                    'text' => 'Tecnologia',
                    'description' => 'Tecnologia',
                ],
                [
                    'con_lang' => 'IT-IT',
                    'country_code' => 'IT',
                    'language' => 'IT',
                    'text' => 'Tecnologia',
                    'description' => 'Tecnologia',
                ],
                [
                    'con_lang' => 'IT-EN',
                    'country_code' => 'IT',
                    'language' => 'EN',
                    'text' => 'Technology',
                    'description' => 'Technology',
                ],
                [
                    'con_lang' => 'DE-DE',
                    'country_code' => 'DE',
                    'language' => 'DE',
                    'text' => 'Technologie',
                    'description' => 'GTechnologie',
                ],
                [
                    'con_lang' => 'DE-EN',
                    'country_code' => 'DE',
                    'language' => 'EN',
                    'text' => 'Technology',
                    'description' => 'Technology',
                ],
                [
                    'con_lang' => 'IN-EN',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'Technology',
                    'description' => 'Technology',
                ],
                [
                    'con_lang' => 'IN-HI',
                    'country_code' => 'IN',
                    'language' => 'EN',
                    'text' => 'टैकनोलजी',
                    'description' => 'टैकनोलजी',
                ],
                [
                    'con_lang' => 'AU-EN',
                    'country_code' => 'AU',
                    'language' => 'EN',
                    'text' => 'Technology',
                    'description' => 'Technology',
                ]
            ],
        ]);
    }
}
