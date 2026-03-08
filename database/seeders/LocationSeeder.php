<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Sport;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sportIds = Sport::query()
            ->pluck('id', 'slug')
            ->all();

        $locations = [
            [
                'name' => 'GymOne Centrum',
                'address' => 'Spui 10',
                'postcode' => '1012 WX',
                'city' => 'Amsterdam',
                'latitude' => 52.3738,
                'longitude' => 4.8910,
                'website' => 'https://example.com/gymone',
                'phone' => '020-1111111',
                'photo_url' => 'https://picsum.photos/seed/gym-amsterdam/640/360',
                'sports' => ['fitness', 'yoga'],
            ],
            [
                'name' => 'Rotterdam Fight & Fit',
                'address' => 'Coolsingel 100',
                'postcode' => '3011 AG',
                'city' => 'Rotterdam',
                'latitude' => 51.9225,
                'longitude' => 4.4792,
                'website' => 'https://example.com/rotterdam-fight-fit',
                'phone' => '010-2222222',
                'photo_url' => 'https://picsum.photos/seed/gym-rotterdam/640/360',
                'sports' => ['fitness', 'boksen'],
            ],
            [
                'name' => 'Utrecht Flow Studio',
                'address' => 'Oudegracht 200',
                'postcode' => '3511 NS',
                'city' => 'Utrecht',
                'latitude' => 52.0907,
                'longitude' => 5.1214,
                'website' => 'https://example.com/utrecht-flow',
                'phone' => '030-3333333',
                'photo_url' => 'https://picsum.photos/seed/gym-utrecht/640/360',
                'sports' => ['yoga'],
            ],
            [
                'name' => 'CrossFit The Hague',
                'address' => 'Grote Marktstraat 20',
                'postcode' => '2511 BJ',
                'city' => 'Den Haag',
                'latitude' => 52.0705,
                'longitude' => 4.3007,
                'website' => 'https://example.com/crossfit-denhaag',
                'phone' => '070-4444444',
                'photo_url' => 'https://picsum.photos/seed/gym-denhaag/640/360',
                'sports' => ['crossfit', 'fitness'],
            ],
            [
                'name' => 'Eindhoven Racket Club',
                'address' => 'Stationsplein 10',
                'postcode' => '5611 AC',
                'city' => 'Eindhoven',
                'latitude' => 51.4416,
                'longitude' => 5.4697,
                'website' => 'https://example.com/eindhoven-racket',
                'phone' => '040-5555555',
                'photo_url' => 'https://picsum.photos/seed/gym-eindhoven/640/360',
                'sports' => ['tennis', 'squash'],
            ],
            [
                'name' => 'Groningen SportHub',
                'address' => 'Vismarkt 12',
                'postcode' => '9711 KT',
                'city' => 'Groningen',
                'latitude' => 53.2194,
                'longitude' => 6.5665,
                'website' => 'https://example.com/groningen-sporthub',
                'phone' => '050-6666666',
                'photo_url' => 'https://picsum.photos/seed/gym-groningen/640/360',
                'sports' => ['fitness', 'crossfit'],
            ],
            [
                'name' => 'Breda Boxing Club',
                'address' => 'Grote Markt 5',
                'postcode' => '4811 XL',
                'city' => 'Breda',
                'latitude' => 51.5719,
                'longitude' => 4.7683,
                'website' => 'https://example.com/breda-boxing',
                'phone' => '076-7777777',
                'photo_url' => 'https://picsum.photos/seed/gym-breda/640/360',
                'sports' => ['boksen'],
            ],
            [
                'name' => 'Leeuwarden Yoga & More',
                'address' => 'Nieuwestad 50',
                'postcode' => '8911 CX',
                'city' => 'Leeuwarden',
                'latitude' => 53.2012,
                'longitude' => 5.7999,
                'website' => 'https://example.com/leeuwarden-yoga',
                'phone' => '058-8888888',
                'photo_url' => 'https://picsum.photos/seed/gym-leeuwarden/640/360',
                'sports' => ['yoga', 'fitness'],
            ],
            [
                'name' => 'Nijmegen Tennispark',
                'address' => 'Keizer Karelplein 1',
                'postcode' => '6511 NC',
                'city' => 'Nijmegen',
                'latitude' => 51.8426,
                'longitude' => 5.8520,
                'website' => 'https://example.com/nijmegen-tennispark',
                'phone' => '024-9999999',
                'photo_url' => 'https://picsum.photos/seed/gym-nijmegen/640/360',
                'sports' => ['tennis'],
            ],
            [
                'name' => 'Maastricht Fit & Squash',
                'address' => 'Vrijthof 15',
                'postcode' => '6211 LE',
                'city' => 'Maastricht',
                'latitude' => 50.8514,
                'longitude' => 5.6909,
                'website' => 'https://example.com/maastricht-fit',
                'phone' => '043-1234567',
                'photo_url' => 'https://picsum.photos/seed/gym-maastricht/640/360',
                'sports' => ['fitness', 'squash'],
            ],
        ];

        foreach ($locations as $entry) {
            $sports = $entry['sports'];
            unset($entry['sports']);

            $location = Location::query()->updateOrCreate(
                [
                    'name' => $entry['name'],
                    'postcode' => $entry['postcode'],
                ],
                $entry
            );

            $location->sports()->sync(
                collect($sports)
                    ->map(fn ($slug) => $sportIds[$slug] ?? null)
                    ->filter()
                    ->values()
            );
        }
    }
}
