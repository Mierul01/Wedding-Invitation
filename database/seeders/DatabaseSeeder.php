<?php

namespace Database\Seeders;

use App\Models\Rsvp;
use App\Models\User;
use App\Models\WeddingSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@wedding.test'],
            [
                'name' => 'Wedding Admin',
                'password' => Hash::make('password'),
            ]
        );

        if (! WeddingSetting::query()->exists()) {
            WeddingSetting::create([
                'groom_name' => 'Amirul Aiman',
                'bride_name' => 'Aina Nadhirah',
                'groom_parents' => 'Abdul Razak bin Abdullah',
                'bride_parents' => 'Aminah binti Ali',
                'wedding_datetime' => now()->addMonths(3)->setTime(11, 0),
                'ceremony_title' => 'Akad Nikah',
                'ceremony_time' => '11:00 AM',
                'ceremony_venue' => 'Masjid Wilayah Persekutuan',
                'ceremony_address' => 'Jalan Duta, 50480 Kuala Lumpur',
                'reception_title' => 'Walimatulurus',
                'reception_time' => '11:30 AM – 4:00 PM',
                'reception_venue' => 'The Majestic Ballroom',
                'reception_address' => 'The Majestic Hotel Kuala Lumpur, 5 Jalan Sultan Hishamuddin, 50000 Kuala Lumpur',
                'event_schedule' => [
                    ['label' => 'Kehadiran tetamu', 'time' => '11:30 AM'],
                    ['label' => 'Ketibaan Pengantin', 'time' => '12:30 PM'],
                    ['label' => 'Makan Beradab', 'time' => '1:30 PM'],
                    ['label' => 'Majlis Berakhir', 'time' => '4:00 PM'],
                ],
                'venue_name' => 'The Majestic Hotel Kuala Lumpur',
                'venue_address' => '5 Jalan Sultan Hishamuddin, 50000 Kuala Lumpur, Malaysia',
                'map_embed_url' => 'https://maps.google.com/maps?q=The+Majestic+Hotel+Kuala+Lumpur&t=&z=15&ie=UTF8&iwloc=&output=embed',
                'map_link' => 'https://maps.google.com/?q=The+Majestic+Hotel+Kuala+Lumpur',
                'contact_name' => 'Amirul Aiman',
                'contact_phone' => '+60 12-345 6789',
                'contact_email' => 'amirulaina.wedding@example.com',
                'contact_name_2' => 'Aina Nadhirah',
                'contact_phone_2' => '+60 13-987 6543',
                'dress_code' => 'Formal / Traditional — Soft sage, ivory & champagne tones encouraged',
                'gift_info' => 'Your presence is the greatest gift. Should you wish to honour us further, a wishing well will be available at the reception.',
                'additional_notes' => "Kindly arrive 15 minutes early for the ceremony.\nChildren are welcome at the reception.\nParking is available at the hotel basement.\nPlease RSVP by two weeks before the wedding date.",
                'welcome_message' => "Dengan penuh kesyukuran, kami menjemput Dato' | Datin | Tuan | Puan | Encik | Cik seisi keluarga hadir ke majlis perkahwinan anakanda kami",
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80',
                    'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?w=800&q=80',
                    'https://images.unsplash.com/photo-1520854221256-17451cc331bf?w=800&q=80',
                    'https://images.unsplash.com/photo-1511285560929-80b456fea7bc?w=800&q=80',
                    'https://images.unsplash.com/photo-1522673607200-164a2e6379d7?w=800&q=80',
                    'https://images.unsplash.com/photo-1606800052052-a08af7148866?w=800&q=80',
                ],
                'max_guests' => 200,
                'rsvp_open' => true,
                'music_enabled' => true,
            ]);
        }

        if (Rsvp::query()->count() === 0) {
            $samples = [
                ['guest_name' => 'Aina Rahman', 'phone' => '012-1112233', 'attending' => true, 'guest_count' => 2, 'message' => 'Congrats! Can\'t wait to celebrate with you both.'],
                ['guest_name' => 'Daniel Lim', 'phone' => '013-4445566', 'attending' => true, 'guest_count' => 3, 'message' => 'Wishing you a lifetime of happiness.'],
                ['guest_name' => 'Nurul Huda', 'phone' => '017-7778899', 'attending' => false, 'guest_count' => 0, 'message' => 'Sorry we can\'t make it. Sending love!'],
                ['guest_name' => 'Farah & Family', 'phone' => '019-2223344', 'attending' => true, 'guest_count' => 4, 'message' => null],
            ];

            foreach ($samples as $sample) {
                Rsvp::create($sample);
            }
        }
    }
}
