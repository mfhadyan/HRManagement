<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = [
            [
                'name' => 'John Doe',
                'start_of_contract' => '2021-01-01',
                'end_of_contract' => '2025-12-31',
                'department_id' => 1,
                'position_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Jane Smith',
                'start_of_contract' => '2021-02-01',
                'end_of_contract' => '2025-12-31',
                'department_id' => 2,
                'position_id' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($employees as $employeeData) {
            // Create user first
            $user = User::create([
                'name' => $employeeData['name'],
                'email' => strtolower(str_replace(' ', '.', $employeeData['name'])) . '@example.com',
                'password' => bcrypt('password'),
            ]);
            
            // Create employee with user_id
            $employee = Employee::create([
                'user_id' => $user->id,
                'name' => $employeeData['name'],
                'start_of_contract' => $employeeData['start_of_contract'],
                'end_of_contract' => $employeeData['end_of_contract'],
                'department_id' => $employeeData['department_id'],
                'position_id' => $employeeData['position_id'],
                'is_active' => $employeeData['is_active'],
            ]);
        }
    }
}
