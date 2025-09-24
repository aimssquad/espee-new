<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VisitorAnalytics;
use Carbon\Carbon;

class VisitorAnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            '/',
            '/products',
            '/products/1',
            '/products/2',
            '/products/3',
            '/cart',
            '/checkout'
        ];

        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15',
            'Mozilla/5.0 (Android 10; Mobile; rv:68.0) Gecko/68.0 Firefox/68.0'
        ];

        $countries = ['United States', 'Canada', 'United Kingdom', 'Australia', 'Germany', 'France', 'Japan', 'India'];
        $cities = ['New York', 'Los Angeles', 'London', 'Toronto', 'Sydney', 'Berlin', 'Paris', 'Tokyo', 'Mumbai'];

        // Generate data for the last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            // Generate 5-20 visitors per day
            $visitorsCount = rand(5, 20);

            for ($j = 0; $j < $visitorsCount; $j++) {
                $ip = '192.168.1.' . rand(1, 255);
                $isUnique = rand(0, 1) == 1;

                VisitorAnalytics::create([
                    'ip_address' => $ip,
                    'user_agent' => $userAgents[array_rand($userAgents)],
                    'page_url' => 'http://localhost:8000' . $pages[array_rand($pages)],
                    'referrer' => rand(0, 1) ? 'https://google.com' : null,
                    'country' => $countries[array_rand($countries)],
                    'city' => $cities[array_rand($cities)],
                    'visit_date' => $date->toDateString(),
                    'visit_time' => $date->addMinutes(rand(0, 1439))->toTimeString(),
                    'session_duration' => rand(30, 1800), // 30 seconds to 30 minutes
                    'is_unique_visitor' => $isUnique
                ]);
            }
        }

        $this->command->info('Visitor analytics data seeded successfully.');
    }
}
