<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTable();

        $status = array(
            array('name' => 'cart'),
            array('name' => 'checkout'),
            array('name' => 'pay'),
            array('name' => 'cancel'),
        );
        $assetTypesave = Status::insert($status);
    }

    public function truncateTable()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('statuses')->truncate();
        Schema::enableForeignKeyConstraints();
    }
}
