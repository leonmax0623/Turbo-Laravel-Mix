<?php

namespace Database\Seeders\Fakes;

use App\Models\OrderStage;
use Illuminate\Database\Seeder;

class FakeOrderStagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStage::factory()->count(5)->create();
    }
}
