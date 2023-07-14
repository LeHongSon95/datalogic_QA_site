<?php

namespace Database\Seeders;

use App\Models\FeaturedTag;
use Illuminate\Database\Seeder;

class FeaturedTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    FeaturedTag::factory(11)->create();
    }
}
