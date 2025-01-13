<?php

namespace Database\Seeders;

use App\Models\Contractor;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Skill;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Initial skills available
        $intialSkills = Skill::$defaultValues;
        foreach ($intialSkills as $skill) {
            $intialSkills[$skill] = Skill::create([
                'name' => $skill
            ]);
        }

        // 2. Contractors, Customers and Locations

        /** @var Contractor $contractor */
        Contractor::factory(100)->create()->each(function($contractor) use ($intialSkills) {
            $rand_keys = array_rand($intialSkills, 3);
            foreach ($rand_keys as $skillName) {
                $contractor->skills()->attach(
                    Skill::where(
                        [
                            'name' => $intialSkills[$skillName]
                        ]
                    )->first()
                );
            }
        });
        Customer::factory(100)->create();
        Location::factory(100)->create();
    }
}
