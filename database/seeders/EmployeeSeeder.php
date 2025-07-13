<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = Division::all();

        for ($i = 1; $i <= 5; $i++) {
            $division = $divisions->random(); 

            Employee::create([
                'id' => Str::uuid(),
                'image' => 'https://via.placeholder.com/150',
                'name' => "Employee $i",
                'phone' => "0812345678$i",
                'division_id' => $division->id,
                'position' => "Staff",
            ]);
        }
    }
}
