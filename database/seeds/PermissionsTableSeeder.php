<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Employee Permission
         */
        
        $createEmployee = new Permission;
        $createEmployee->display_name = 'Create employee';
        $createEmployee->name = 'employee-create';
        $createEmployee->description = 'Be able to create a new employee';
        $createEmployee->grouping = 'employee';
        $createEmployee->save();
        

        $updateEmployee = new Permission;
        $updateEmployee->display_name = 'Update employee';
        $updateEmployee->name = 'employee-update';
        $updateEmployee->description = "Be able to update a employee's information";
        $updateEmployee->grouping = 'employee';
        $updateEmployee->save();

        $deleteEmployee = new Permission;
        $deleteEmployee->display_name = 'Delete employee';
        $deleteEmployee->name = 'employee-delete';
        $deleteEmployee->description = "Be able to delete a employee";
        $deleteEmployee->grouping = 'employee';
        $deleteEmployee->save();

        Permission::firstOrCreate([
            'display_name' => 'View Employee',
            'name' => 'employees-view',
            'description' => 'Be able to view employees listing page',
            'grouping' => 'employee',
        ]);

         /**
         *  Role Module Permissions
         */

        Permission::firstOrCreate([
            'display_name' => 'View Role',
            'name' => 'role-view',
            'description' => 'Be able to view roles listing page',
            'grouping' => 'role',
        ]);
        Permission::firstOrCreate([
            'display_name' => 'Create Role',
            'name' => 'role-create',
            'description' => 'Be able to create a new role',
            'grouping' => 'role',
        ]);
        Permission::firstOrCreate([
            'display_name' => 'Update Role',
            'name' => 'role-update',
            'description' => "Be able to update role's permissions",
            'grouping' => 'role',
        ]);

        // Permission::firstOrCreate([
        //     'display_name' => 'Delete Role',
        //     'name' => 'role-delete',
        //     'description' => "Be able to delete role",
        //     'grouping' => 'role',
        // ]);

        /*
        Leave Types Permissions
        */

        Permission::firstOrCreate([
            'display_name' => 'Leave Types View',
            'name' => 'leave-types-view',
            'description' => 'Be able to view Leave Types page',
            'grouping' => 'leave-types',
        ]);

        Permission::firstOrCreate([
            'display_name' => 'Create Leave Types',
            'name' => 'leave-types-create',
            'description' => 'Be able to create a new Leave Type',
            'grouping' => 'leave-types'
        ]);

        Permission::firstOrCreate([
            'display_name' => 'Update Leave Type',
            'name' => 'leave-type-update',
            'description' => "Be able to update role's Leave Type",
            'grouping' => 'leave-types'
        ]);

        /*
        Email Templates Permissions
        */

        Permission::firstOrCreate([
            'display_name' => 'Email Templates View',
            'name' => 'email-templates-view',
            'description' => 'Be able to view Email Templates page',
            'grouping' => 'email-templates',
        ]);

        Permission::firstOrCreate([
            'display_name' => 'Create Email Templates',
            'name' => 'email-template-create',
            'description' => 'Be able to create a new Email Templates',
            'grouping' => 'email-templates',
        ]);

        Permission::firstOrCreate([
            'display_name' => 'Update Email Templates',
            'name' => 'email-template-update',
            'description' => "Be able to update Email Templates",
            'grouping' => 'email-templates',
        ]);

        /*
        Leaves Permissions
        */

        Permission::firstOrCreate([
            'display_name' => 'Leave View',
            'name' => 'leaves-view',
            'description' => 'Be able to view Leaves page',
            'grouping' => 'leaves',
        ]);

        Permission::firstOrCreate([
            'display_name' => 'Create Leave',
            'name' => 'leave-create',
            'description' => 'Be able to create a new Leave',
            'grouping' => 'leaves'
        ]);

        Permission::firstOrCreate([
            'display_name' => 'Update Leave',
            'name' => 'leave-update',
            'description' => "Be able to update Leave",
            'grouping' => 'leaves'
        ]);

        Permission::firstOrCreate([
            'display_name' => 'Manage Leaves',
            'name' => 'leave-manage',
            'description' => "Be able to Manage his/her own Leave",
            'grouping' => 'leaves'
        ]);

        /*
        Task Permissions
        */

        Permission::firstOrCreate([
            'display_name' => 'Submited Task View',
            'name' => 'submited-tasks-view',
            'description' => 'Be able to view Submited Tasks page',
            'grouping' => 'tasks',
        ]);

        Permission::firstOrCreate([
            'display_name' => 'Tasks View',
            'name' => 'tasks-view',
            'description' => 'Be able to view Tasks page',
            'grouping' => 'tasks',
        ]);

        Permission::firstOrCreate([
            'display_name' => 'Create Task',
            'name' => 'task-create',
            'description' => 'Be able to create a new Task',
            'grouping' => 'tasks'
        ]);

        Permission::firstOrCreate([
            'display_name' => 'Update Task',
            'name' => 'task-update',
            'description' => "Be able to update Task",
            'grouping' => 'tasks'
        ]);

        // Permission::firstOrCreate([
        //     'display_name' => 'Delete Task',
        //     'name' => 'task-delete',
        //     'description' => "Be able to Delete Task",
        //     'grouping' => 'tasks'
        // ]);

        /**
        * Client Permission
        */
        
        $createClient = new Permission;
        $createClient->display_name = 'Create client';
        $createClient->name = 'client-create';
        $createClient->description = 'Permission to create client';
        $createClient->grouping = 'client';
        $createClient->save();

        $updateClient = new Permission;
        $updateClient->display_name = 'Update client';
        $updateClient->name = 'client-update';
        $updateClient->description = 'Permission to update client';
        $updateClient->grouping = 'client';
        $updateClient->save();

        $deleteClient = new Permission;
        $deleteClient->display_name = 'Delete client';
        $deleteClient->name = 'client-delete';
        $deleteClient->description = 'Permission to delete client';
        $deleteClient->grouping = 'client';
        $deleteClient->save();

        Permission::firstOrCreate([
            'display_name' => 'View Clients',
            'name' => 'client-view',
            'description' => 'Be able to view clients listing page',
            'grouping' => 'client',
        ]);

        /**
        * Tasks Permission
        */
        
        $createTask = new Permission;
        $createTask->display_name = 'Create task';
        $createTask->name = 'task-create';
        $createTask->description = 'Permission to create task';
        $createTask->grouping = 'task';
        $createTask->save();     
        
        /**
         * Holidays permissions
         */

        Permission::firstOrCreate([
            'display_name' => 'View Holidays',
            'name' => 'holidays-view',
            'description' => 'Be able to view holidays listing page',
            'grouping' => 'holiday',
        ]);
        Permission::firstOrCreate([
            'display_name' => 'Create Holidays',
            'name' => 'holiday-create',
            'description' => 'Be able to create a new holiday',
            'grouping' => 'holiday',
        ]);
        Permission::firstOrCreate([
            'display_name' => 'Update Holidays',
            'name' => 'holiday-update',
            'description' => "Be able to update holidays",
            'grouping' => 'holiday',
        ]);
        /**
         * other permissions
         */

        $createTask = new Permission;
        $createTask->display_name = 'Access Log';
        $createTask->name = 'log-access';
        $createTask->description = 'Permission to access Logs';
        $createTask->grouping = 'other';
        $createTask->save();

        Permission::firstOrCreate([
            'display_name' => 'see employee tasks',
            'name' => 'see-employee-tasks',
            'description' => "Be able to see employee daily tasks",
            'grouping' => 'tasks',
        ]);

    }

}
