<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Organization;
use App\Models\Vessel;
use App\Models\ServiceLine;
use App\Models\Category;
use App\Models\Ticket;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@helpdesk.com',
            'password' => Hash::make('password'),
        ]);

        $staff = User::create([
            'name' => 'John Doe',
            'email' => 'staff@helpdesk.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Create Organizations
        $org1 = Organization::create(['name' => 'MOLSHP']);
        $org2 = Organization::create(['name' => 'K-Line']);

        // 3. Create Vessels
        $vessel1 = Vessel::create(['name' => 'MV Tenso', 'organization_id' => $org1->id]);
        $vessel2 = Vessel::create(['name' => 'MV Gimm', 'organization_id' => $org1->id]);
        $vessel3 = Vessel::create(['name' => 'Blue Ocean', 'organization_id' => $org2->id]);

        // 4. Create Categories
        $cat1 = Category::create(['name' => 'Antivirus']);
        $cat2 = Category::create(['name' => 'Network']);
        $cat3 = Category::create(['name' => 'Hardware']);

        // 5. Create Service Lines
        $sl1 = ServiceLine::create(['name' => 'Second']);
        $sl2 = ServiceLine::create(['name' => 'First']);

        // 6. Create Tickets
        Ticket::create([
            'title' => 'Request to assist and check MV Tenso Port IT AV',
            'description' => 'This is a description of the ticket. It should just be a few lines and describe the issue.',
            'priority' => 'High',
            'status' => 'Open',
            'contact_email' => 'mruser@gimm.ph',
            'cc_emails' => 'myboss@gimm.ph,support@it.com',
            'organization_id' => $org1->id, // MOLSHP
            'vessel_id' => $vessel1->id,    // MV Tenso
            'service_line_id' => $sl1->id,  // Second
            'category_id' => $cat1->id,     // Antivirus
            'assigned_to_user_id' => null,  // Currently unassigned
            'created_at' => now()->subHours(2), // สร้างย้อนหลังเพื่อให้เห็นเวลาผ่านไป
        ]);

        // Ticket ใบที่ 2: สร้างเพิ่มให้ List ดูไม่โล่ง
        Ticket::create([
            'title' => 'Internet connection unstable on Bridge',
            'description' => 'The captain reports intermittent connection issues properly.',
            'priority' => 'Medium',
            'status' => 'In Progress',
            'contact_email' => 'captain@vessel.com',
            'cc_emails' => null,
            'organization_id' => $org1->id,
            'vessel_id' => $vessel2->id,
            'service_line_id' => $sl2->id,
            'category_id' => $cat2->id,
            'assigned_to_user_id' => $staff->id, // Assign ให้ John Doe
            'created_at' => now()->subDays(1),
        ]);

         // Ticket ใบที่ 3
         Ticket::create([
            'title' => 'Printer jamming constantly',
            'description' => 'HP LaserJet in the control room needs service.',
            'priority' => 'Low',
            'status' => 'Closed',
            'contact_email' => 'crew@gimm.ph',
            'cc_emails' => 'myboss@gimm.ph,support@it.com, it@gimm.ph',
            'organization_id' => $org2->id,
            'vessel_id' => $vessel3->id,
            'service_line_id' => $sl3->id,
            'category_id' => $cat3->id,
            'assigned_to_user_id' => $staff->id,
            'created_at' => now()->subDays(5),
        ]);
    }
}
