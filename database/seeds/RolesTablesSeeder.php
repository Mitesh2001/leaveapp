<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use Ramsey\Uuid\Uuid;

class RolesTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_adminRole = new Role;
        $super_adminRole->display_name = 'Owner';
        $super_adminRole->external_id = Uuid::uuid4();
        $super_adminRole->name = 'owner';
        $super_adminRole->description = 'Owner';
        $super_adminRole->save();
    }
}
