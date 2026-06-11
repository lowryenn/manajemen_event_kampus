<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'phone' => '08111222333',
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'phone' => '08123456789',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 2. Create Default User
        $user = User::create([
            'name' => 'Mahasiswa Budi',
            'email' => 'user@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'phone' => '08999888777',
            'role' => 'user',
            'is_active' => true,
        ]);

        // 3. Create Sample Events
        \App\Models\Event::create([
            'organizer_id' => $admin->id,
            'name' => 'Hackathon Nasional HackFest 2026',
            'description' => 'HackFest 2026 adalah kompetisi hackathon nasional 48 jam untuk mahasiswa di seluruh Indonesia untuk membangun solusi inovatif berbasis teknologi AI dan Cloud Computing.',
            'type' => 'online',
            'location' => 'https://zoom.us/j/9876543210',
            'date' => now()->addDays(15)->setHour(9)->setMinute(0)->setSecond(0),
            'price' => 50000,
            'quota' => 200,
        ]);

        \App\Models\Event::create([
            'organizer_id' => $admin->id,
            'name' => 'Seminar Career Path in AI & DevOps',
            'description' => 'Bagaimana memulai karir di bidang Artificial Intelligence dan DevOps Engineering? Temukan jawabannya langsung dari para expert industri teknologi terkemuka Indonesia dalam seminar eksklusif ini.',
            'type' => 'offline',
            'location' => 'Auditorium Gd. Rektorat Lt. 4, Kampus Pusat',
            'date' => now()->addDays(7)->setHour(13)->setMinute(30)->setSecond(0),
            'price' => 0, // FREE event
            'quota' => 120,
        ]);
    }
}
