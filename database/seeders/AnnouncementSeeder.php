<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create sample announcements
        $announcements = [
            [
                'title' => 'Welcome to Our New HR Management System',
                'description' => "We are excited to announce the launch of our new HR Management System. This system will help streamline our HR processes and improve communication across all departments.\n\nKey features include:\n- Employee management\n- Attendance tracking\n- Leave management\n- Performance evaluation\n- Announcements\n\nPlease take some time to explore the new system and familiarize yourself with its features. If you have any questions, please don't hesitate to contact the HR department.",
                'department_id' => null, // All departments
                'created_by' => 1,
            ],
            [
                'title' => 'Monthly Team Meeting Schedule',
                'description' => "Our monthly team meetings will be held on the first Monday of each month at 10:00 AM in the conference room.\n\nAgenda items should be submitted to your department head by the Friday before the meeting.\n\nPlease make sure to attend as important updates and announcements will be shared during these sessions.",
                'department_id' => null, // All departments
                'created_by' => 1,
            ],
            [
                'title' => 'Updated Company Policies',
                'description' => "Please review the updated company policies that have been posted in the employee portal. Key changes include:\n\n- Updated dress code policy\n- New remote work guidelines\n- Revised leave request procedures\n- Updated performance evaluation criteria\n\nAll employees are required to acknowledge these changes by the end of the month.",
                'department_id' => null, // All departments
                'created_by' => 1,
            ],
            [
                'title' => 'IT Department Maintenance Notice',
                'description' => "The IT department will be performing system maintenance on Saturday, 15th of this month from 2:00 PM to 6:00 PM.\n\nDuring this time, some systems may be temporarily unavailable. We apologize for any inconvenience this may cause.\n\nPlease plan your work accordingly and contact the IT helpdesk if you have any urgent issues.",
                'department_id' => 1, // Assuming IT is department ID 1
                'created_by' => 1,
            ],
            [
                'title' => 'Employee Wellness Program',
                'description' => "We are launching a new employee wellness program to promote health and well-being in the workplace.\n\nProgram features:\n- Weekly yoga sessions\n- Health screenings\n- Mental health support\n- Fitness challenges\n- Nutrition workshops\n\nRegistration opens next week. Stay tuned for more details!",
                'department_id' => null, // All departments
                'created_by' => 1,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
