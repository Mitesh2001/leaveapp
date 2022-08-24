<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DailytaskController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;


    Route::auth();
    Route::get('logout','Auth\LoginController@logout');

    Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
    Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

    Route::group(['middleware' => ['auth']], function () { 
        /**
         * Main
         */
        Route::get('/', 'PagesController@dashboard');
        Route::get('dashboard', 'PagesController@dashboard')->name('dashboard');

        /**
         * Notifications
         */
        Route::group(['prefix' => 'notifications'], function () {
            Route::get('/', 'NotificationsController@index')->name('notifications.index');
            Route::get('/markall', 'NotificationsController@markAll')->name('notification.markAllAsRead');
            Route::get('/{id}', 'NotificationsController@markRead')->name('notification.markRead');
        });
    
        /**
         * Users
         */
        Route::group(['prefix' => 'users'], function () {
            Route::get('/data', 'UsersController@anyData')->name('users.data');
            Route::get('/taskdata/{id}', 'UsersController@taskData')->name('users.taskdata');
            Route::get('/clientdata/{id}', 'UsersController@clientData')->name('users.clientdata');
            Route::get('/users', 'UsersController@users')->name('users.users');
            Route::get('/calendar-users', 'UsersController@calendarUsers')->name('users.calendar');
     
            Route::get('users/professional_details/{external_id}/', 'UsersController@showProfessionalDetails')->name('users.showProfessionalDetails');
      
            Route::get('/ajax-users', 'UsersController@getUserByName')->name('users.userbyname');  
            Route::post('/check-repeat-email', 'UsersController@checkEmailRepeat')->name('users.checkemail'); 
            Route::post('/check-repeat-number', 'UsersController@checkPrimaryNumberRepeat')->name('users.checkPrimaryNumber'); 
            
           
            Route::post('/check-user-delete', 'UsersController@checkUserDelete')->name('users.checkdelete');
            Route::post('/ajax-user-delete', 'UsersController@ajaxUserDelete')->name('users.delete'); 


            // Profile
            Route::get('/profile', 'UsersController@profile')->name('users.profile');
            Route::post('/update-personal', 'UsersController@updatePersonal')->name('users.updatePersonal');
        });
        Route::resource('users', 'UsersController');
    
        /**
        * Roles
        */ 
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/data', 'RolesController@indexData')->name('roles.data');
            Route::patch('/update/{external_id}', 'RolesController@update')->name('roles.update');
            Route::post('/ajax-role-checkName', 'RolesController@checkNameRepeat')->name('roles.check_name');
        });
        Route::resource('roles', 'RolesController', ['except' => [
            'update'
        ]]);

        /**
         * Clients
         */
        Route::group(['prefix' => 'clients'], function () {
            Route::get('/data', 'ClientsController@anyData')->name('clients.data');
            Route::get('/taskdata/{external_id}', 'ClientsController@taskDataTable')->name('clients.taskDataTable');
            Route::get('/projectdata/{external_id}', 'ClientsController@projectDataTable')->name('clients.projectDataTable');
            Route::get('/leaddata/{external_id}', 'ClientsController@leadDataTable')->name('clients.leadDataTable');
            Route::get('/invoicedata/{external_id}', 'ClientsController@invoiceDataTable')->name('clients.invoiceDataTable');
            Route::post('/create/cvrapi', 'ClientsController@cvrapiStart');
            Route::post('/upload/{external_id}', 'DocumentsController@upload')->name('document.upload');
            Route::patch('/updateassign/{external_id}', 'ClientsController@updateAssign');
            Route::post('/updateassign/{external_id}', 'ClientsController@updateAssign');
            Route::post('/updateassign/{external_id}', 'ClientsController@updateAssign');
    
            Route::post('/check-repeat-email', 'ClientsController@checkEmailRepeat')->name('clients.checkemail'); 
            Route::post('/check-repeat-number', 'ClientsController@checkPrimaryNumberRepeat')->name('clients.checkPrimaryNumber'); 
    
            // Ajax (Dropdown)
            Route::get('/ajax-industries', 'ClientsController@getIntustryByName')->name('products.industrybyname');
            Route::post('/find_by_id', 'ClientsController@findById')->name('clients.findbyid');
            Route::get('/admins/{external_id}','ClientsController@assignAdmins')->name('clients.admins');
            Route::get('/admins_list','ClientsController@getAdminsList')->name('clients.admins_list');
            Route::get('/users_list', 'ClientsController@getUserByName')->name('clients.users_list');  

            Route::POST('/admins_list_update/{external_id}','ClientsController@updateAssignedAdmins')->name('clients.admins_list_update');
            
            // Ajax delete enquiry type
            Route::post('/check-clients-delete', 'ClientsController@checkClientDelete')->name('clients.checkdelete');
            Route::post('/ajax-clients-delete', 'ClientsController@ajaxClientDelete')->name('clients.delete'); 
     
        });
        Route::resource('clients', 'ClientsController');

        Route::group(['prefix' => 'leaves'], function () {
            Route::get('/persons_list','LeaveController@personsList')->name('leaves.persons_list');
            Route::patch('/update/{external_id}', 'LeaveController@update')->name('leaves.update');   
            Route::patch('/update/{external_id}', 'LeaveController@update')->name('leaves.update');
            Route::get('/data', 'LeaveController@anyData')->name('leaves.data');
        });

        Route::resource('leaves', 'LeaveController',['except' => [
            'update'
        ]]);

        Route::group(['prefix' => 'manage_leaves'], function () {
            Route::get('/data', 'LeaveManageController@anyData')->name('manage_leaves.data');
            Route::get('/manage/{leave_external_id}', 'LeaveManageController@manage')->name('manage_leaves.manage');
            Route::patch('/update/{external_id}', 'LeaveManageController@update')->name('manage_leaves.update');   
            Route::get('/clients', 'LeaveManageController@getClients')->name('manage_leaves.clients');
            Route::get('/alternate_person', 'LeaveManageController@personsList')->name('manage_leaves.persons_list');
        });

        Route::resource('manage_leaves', 'LeaveManageController',['except' => [
            'update'
        ]]);

        Route::group(['prefix' => 'leave-types'], function () {
            Route::get('/data', 'LeaveTypeController@indexData')->name('leave_types.data');
            Route::patch('/update/{external_id}', 'LeaveTypeController@update')->name('leave_types.update');
        });
        Route::resource('leave-types', 'LeaveTypeController',['except' => [
            'update'
        ]]);    
 
        Route::get('document/{external_id}', 'DocumentsController@view')->name('document.view');
        Route::get('document/download/{external_id}', 'DocumentsController@download')->name('document.download');
        Route::resource('documents', 'DocumentsController');

        /**
         * Daily Tasks
         */
        
        Route::group(['prefix' => 'my-task'], function () { 
            Route::get('daily-tasks-list',[DailytaskController::class,'dailyTasksList'])->name('my_tasks.daily_tasks_list');
            Route::get('daily-tasks-data',[DailytaskController::class,'tasksData'])->name('my_tasks.daily_tasks.data');
            Route::get('employee-daily-tasks',[DailytaskController::class,'employeeDailyTasks'])->name('my_tasks.employee_tasks_list');
            Route::get('daily-tasks',[DailytaskController::class,'EmployeetasksData'])->name('employee-daily-tasks.data');
        });

        Route::resource('my-task','DailytaskController');
        
        /**
         * Tasks
         */
        
        Route::group(['prefix' => 'tasks'], function () {
            Route::get("/work/{external_id}",'TasksController@works')->name('tasks.my-work');
            Route::post("/work-update/{external_id}",'TasksController@update')->name('tasks.update');
            Route::get('/data', 'TasksController@anyData')->name('tasks.data');
            Route::get('/completed-task-data', 'TasksController@completedTaskData')->name('tasks.completed_task_data');
            Route::get('submitted-task','TasksController@submitedTasks')->name('tasks.submited_task');
            Route::get('submited-task-data','TasksController@submitedTaskData')->name('tasks.submited_task_data');
        });
        Route::resource('tasks', 'TasksController',['except' => [
            'update'
        ]]);
   
        //email template
		Route::group(['prefix' => 'emails'], function () {
            Route::get('data','EmailsController@anydata')->name('emails.data'); 
		});	 
        
        Route::get('emails/edit/{id}','EmailsController@edit')->name('emails.edit_template'); 
		Route::resource('emails','EmailsController'); 

        //calendar 
        Route::group(['prefix' => 'calendar'], function () {
            Route::get('/holidays-calender','CalendarController@HolidaysCalender')->name("holidays.calender");
        });

        Route::resource('calendar', 'CalendarController');

        //Holidays

        Route::group(['prefix' => 'holidays'], function () {
            Route::get('/data','HolidayController@anydata')->name('holidays.data'); 
            Route::patch('/update/{external_id}', 'HolidayController@update')->name('holidays.update');
		});

        Route::resource('holidays','HolidayController',['except' => [
            'update'
        ]]);

        //LogS

        Route::group(['prefix' => 'logs'], function () {
            Route::get('/leaves','LogController@leaves')->name('logs.leaves');
            Route::get('/leaves-data','LogController@leaveData')->name('logs.leaves-data');
            Route::get('/emails','LogController@emails')->name('logs.emails');
            Route::get('/emails-data','LogController@emailsData')->name('logs.emails-data');
            Route::get('/tasks','LogController@tasks')->name('logs.tasks');
            Route::get('/tasks-data','LogController@tasksData')->name('logs.tasks-data');
        });

    });
  
Route::group(['middleware' => ['auth']], function () {
    Route::get('/dropbox-token', 'CallbackController@dropbox')->name('dropbox.callback');
    Route::get('/googledrive-token', 'CallbackController@googleDrive')->name('googleDrive.callback');
});
