<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();

        foreach (range(1, 24) as $index) {
            $eventTime = now()->subHours($index); // Generate a timestamp within the past 24 hours

            $event = DB::table('events')->insertGetId([
                'site_id' => 1,
                'domain' => $faker->domainName,
                'event' => 'pageview',
                'entry_page' => $faker->url,
                'exit_page' => $faker->url,
                'referrer' => $faker->url,
                'referrer_domain' => $faker->domainName,
                'hash' => $faker->md5,
                'device_type' => 'desktop',
                'user_agent' => $faker->userAgent,
                'os' => $faker->word,
                'os_version' => $faker->randomNumber(2),
                'browser' => $faker->word,
                'browser_version' => $faker->randomNumber(2),
                'resolution' => null,
                'country' => null,
                'state' => null,
                'city' => null,
                'is_bounced' => $faker->boolean,
                'duration' => $faker->randomFloat(2, 0, 100),
                'created_at' => $eventTime,
                'updated_at' => $eventTime,
                // 'created_at' => now(),
                // 'updated_at' => now(),
            ]);
            $numPageviews = rand(1, 3);
            for ($i = 0; $i < $numPageviews; $i++) {
                $pageviewTime = $eventTime->copy()->addMinutes(rand(1, 60)); // Generate a timestamp within the same hour

                DB::table('pageviews')->insert([
                    'event_id' => $event,
                    'page' => '/tracker/test',
                    'created_at' => $pageviewTime,
                    'updated_at' => $pageviewTime,
                    // 'created_at' => now(),
                    // 'updated_at' => now(),
                ]);
            }
        }
    }
}
