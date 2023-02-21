<?php

namespace Database\Seeders\Fakes;

use App\Models\ProcessTask;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class FakeProcessTasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProcessTask::factory()->count(40)
            ->state(
                new Sequence(
                    function ($sequence) {
                        return [
                            'role_id' => Arr::random([3, 4]),
                            'position' => $sequence->index
                        ];
                    },
                )
            )->create();
    }
}
