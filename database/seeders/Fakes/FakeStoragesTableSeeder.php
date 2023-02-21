<?php

namespace Database\Seeders\Fakes;

use App\Models\Department;
use App\Models\Storage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class FakeStoragesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departmentIds = Department::pluck('id')->toArray();

        Storage::factory()->count(rand(3, 20))
            ->state(
                new Sequence(
                    function () use ($departmentIds) {
                        return ['department_id' => Arr::random($departmentIds)];
                    },
                )
            )->create();
    }
}
