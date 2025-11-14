<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Point-to-Point Services
            [
                'name' => 'City to City Transfer',
                'slug' => 'city-to-city-transfer',
                'category' => 'point-to-point',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'short_description' => 'Direct transfers between cities with precise timing and maximum comfort',
                'description' => '<h3>Professional City-to-City Transportation</h3><p>Travel smarter and in style with our premium city-to-city transfer service. Whether you\'re heading to a business meeting, catching a connecting flight, or exploring neighboring cities, our professional chauffeurs ensure a comfortable and timely journey.</p><p>Our service includes door-to-door pickup and drop-off, eliminating the stress of navigating unfamiliar routes or finding parking. Sit back, relax, and let us handle the driving while you focus on what matters most.</p>',
                'features' => '<ul><li>Professional uniformed chauffeur</li><li>Door-to-door service</li><li>Real-time traffic monitoring</li><li>Complimentary bottled water</li><li>Wi-Fi enabled vehicles</li><li>Phone charging stations</li><li>Flexible scheduling</li></ul>',
                'inclusions' => '<ul><li>Luxury vehicle rental</li><li>Professional chauffeur</li><li>Fuel and tolls</li><li>All taxes and fees</li><li>Meet and greet service</li></ul>',
                'exclusions' => '<ul><li>Gratuity (optional)</li><li>Additional stops (extra charges apply)</li><li>Waiting time beyond 15 minutes</li></ul>',
                'terms_conditions' => '<p><strong>Booking Requirements:</strong> Minimum 24-hour advance booking required. Cancellations must be made at least 12 hours before scheduled pickup.</p><p><strong>Payment:</strong> Full payment required at time of booking. Credit cards and corporate accounts accepted.</p>',
                'cancellation_policy' => '<p>Free cancellation up to 12 hours before scheduled pickup. Cancellations within 12 hours are subject to 50% charge. No-shows are charged in full.</p>',
                'pricing_type' => 'distance_based',
                'base_price' => 50.00,
                'min_price' => 75.00,
                'per_mile_rate' => 3.50,
                'available_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                'available_from' => '06:00:00',
                'available_to' => '23:00:00',
                'advance_booking_hours' => 24,
                'max_advance_days' => 90,
                'max_passengers' => 3,
                'max_luggage' => 3,
                'free_waiting_time' => 15,
                'waiting_charge_per_min' => 1.00,
                'service_areas' => [
                    ['name' => 'San Francisco', 'type' => 'city'],
                    ['name' => 'Oakland', 'type' => 'city'],
                    ['name' => 'San Jose', 'type' => 'city'],
                    ['name' => 'Sacramento', 'type' => 'city'],
                    ['name' => 'Bay Area', 'type' => 'region'],
                ],
                'max_distance_miles' => 150,
                'airport_service' => false,
                'vehicle_types' => [1, 2, 3],
                'amenities' => ['Professional uniformed chauffeur', 'Complimentary bottled water', 'Wi-Fi enabled', 'Phone chargers', 'Climate control', 'Premium sound system'],
                'icon' => 'fa-route',
                'quick_facts' => [
                    'Distance Pricing' => '$3.50 per mile',
                    'Minimum Fare' => '$75',
                    'Availability' => '6 AM - 11 PM',
                    'Capacity' => '1-3 passengers',
                ],
                'meta_title' => 'City to City Transfer - Premium Point-to-Point Service',
                'meta_description' => 'Professional city-to-city transfer service with luxury vehicles and experienced chauffeurs. Door-to-door service across Bay Area.',
                'meta_keywords' => ['city transfer', 'point to point', 'chauffeur service', 'bay area transport'],
            ],
            [
                'name' => 'Executive Point-to-Point',
                'slug' => 'executive-point-to-point',
                'category' => 'point-to-point',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2,
                'short_description' => 'Premium executive transportation for business professionals',
                'description' => '<h3>Executive-Class Transportation</h3><p>Designed specifically for business executives and professionals who demand the best. Our executive point-to-point service combines luxury vehicles, professional chauffeurs, and business-friendly amenities to ensure you arrive at your destination refreshed and ready.</p><p>Perfect for board meetings, client presentations, or important business engagements where first impressions matter.</p>',
                'features' => '<ul><li>Executive luxury vehicles</li><li>Business-class amenities</li><li>Newspapers and magazines</li><li>Private work space</li><li>Conference call capabilities</li><li>Priority service</li><li>Discreet and professional</li></ul>',
                'inclusions' => '<ul><li>Premium executive vehicle</li><li>Experienced professional chauffeur</li><li>Business amenities package</li><li>Complimentary refreshments</li><li>All taxes and fees</li></ul>',
                'exclusions' => '<ul><li>Gratuity</li><li>Extended stops</li><li>Additional passengers beyond capacity</li></ul>',
                'terms_conditions' => '<p>Designed for business professionals. 48-hour advance booking recommended. Corporate billing available with approved accounts.</p>',
                'cancellation_policy' => '<p>Free cancellation up to 24 hours before service. Late cancellations subject to 50% charge.</p>',
                'pricing_type' => 'flat_rate',
                'base_price' => 150.00,
                'min_price' => 150.00,
                'available_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'available_from' => '05:00:00',
                'available_to' => '22:00:00',
                'advance_booking_hours' => 48,
                'max_advance_days' => 180,
                'max_passengers' => 2,
                'max_luggage' => 2,
                'free_waiting_time' => 30,
                'waiting_charge_per_min' => 1.50,
                'service_areas' => [
                    ['name' => 'San Francisco Financial District', 'type' => 'district'],
                    ['name' => 'Silicon Valley', 'type' => 'region'],
                    ['name' => 'Oakland Business District', 'type' => 'district'],
                ],
                'max_distance_miles' => 100,
                'airport_service' => false,
                'vehicle_types' => [2, 3],
                'amenities' => ['Executive sedan or SUV', 'Business-class interior', 'Privacy partition', 'Wi-Fi hotspot', 'Charging stations', 'Bottled water', 'Daily newspapers'],
                'icon' => 'fa-briefcase',
                'quick_facts' => [
                    'Pricing' => 'Flat rate from $150',
                    'Vehicle' => 'Executive Sedan/SUV',
                    'Availability' => 'Monday-Friday',
                    'Capacity' => '1-2 passengers',
                ],
                'meta_title' => 'Executive Point-to-Point Transportation - Business Travel',
                'meta_description' => 'Premium executive transportation for business professionals. Luxury vehicles with business amenities.',
                'meta_keywords' => ['executive transport', 'business travel', 'corporate chauffeur', 'luxury sedan'],
            ],

            // Hourly Charter Services (continuing from Part 1...)
        ];

        // Due to character limits, I'll add services in batches
        // Add more services here following the same pattern

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }
    }
}
