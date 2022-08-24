<?php
namespace App\Http\Controllers;

use Datatables;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Client;
use App\Models\Role;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use Ramsey\Uuid\Uuid;
use App\Models\LeaveManage;
use Illuminate\Support\Facades\Crypt;
use Mail;
use phpDocumentor\Reflection\Types\Null_;

class UsersController extends Controller
{
    protected $users;
    protected $roles;

    public function __construct()
    {
        $this->middleware('permission:employees-view', ['only' => ['index', 'show']]);
		$this->middleware('permission:employee-create', ['only' => ['create','store']]);
		$this->middleware('permission:employee-update', ['only' => ['edit','update']]);  
    }

    /**
     * @return mixed
     */
    public function index(Request $request)
    {
        $data['roles'] = Role::where('name', '!=','owner')->pluck('name', 'id');
        $data['back_url'] = route('users.index');
        return view('users.index')->with($data);
    }

    public function anyData(Request $request)
    {
        $users = User::whereHas(
            'roles', function($q){
                $q->where('name',"!=",'owner');
            }
        )->latest();
        
        return Datatables::of($users)
            ->addColumn('namelink', function ($users) { 
                $first_name = $users->first_name ?? "";
                $last_name = $users->last_name ?? "";
                return $first_name ." ". $last_name;
            })             
            ->addColumn('primary_number', function ($user) {
                return $user->primary_number;
            })
            ->addColumn('email', function ($user) {
                return $user->email;
            })
            ->addColumn('role', function ($user) {
                return $user->roles->first()->name ?? "";
            })
            ->addColumn('action', function ($user) {
                $edit_url = route('users.edit', $user->external_id);
                $view_url = route('users.show' , $user->external_id);  
                $html ="";      
                $html .= '<form action="'.route('users.destroy', $user->external_id).'" class="d-flex" method="POST">';
                $html .= method_field("DELETE");         
                $html .= '<a href="'.$view_url.'" class="btn btn-link"><i class="flaticon-eye text-success text-hover-primary" data-toggle="tooltip" title="View Details"></i></a>';
                if (auth()->user()->can("employee-update")) {
                    $html .= '<a href="'.$edit_url.'" class="btn btn-link" data-toggle="tooltip" title="Edit Employee"><i class="flaticon2-pen text-success text-hover-primary"></i></a>';
                }
                if (auth()->user()->can("employee-delete")) {
                    $html .= '<button type="submit" name="submit" value="' . __('Delete') . '" class="btn btn-link"  data-toggle="tooltip" title="Delete User"><i class="flaticon2-trash text-danger text-hover-warning"></i></button>'; 
                    $html .= csrf_field();
                }
                $html .= '</form>';  
                return $html;
            }) 
            ->addColumn('roles', function ($user) {
                return $user->roles->first()->name ?? "";
            })
            ->rawColumns(['namelink', 'primary_number', 'email', 'role', 'action', 'roles'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $data['back_url'] = route('users.index'); 
        $data['employers'] = User::whereHas(
            'roles', function($q){
                $q->where('name', 'admin');
            }
        )->pluck('first_name', 'id');
        $data['roles'] = Role::where('name', '!=','owner')->pluck('name', 'id');  
        return view('users.create')->with($data);
    }

    
    /**
     * @param StoreUserRequest $userRequest
     * @return mixed
     */  
    public function store(StoreUserRequest $request)
    {      
        if($request->hasFile('profile_pic')) {

            $first_name  = strtolower(str_replace(' ', '_',$request->first_name));

            $pic_name = $first_name ."_". time() .".". $request->profile_pic->extension(); 
            $path_profile_pic = 'storage/assets/employees/profile_pics/';
            $returned = $request->profile_pic->move(public_path($path_profile_pic), $pic_name); 

            $profile_pic_name = $path_profile_pic . $pic_name;
        } else {
            $profile_pic_name = "";
        } 
         
        $created_by = Auth::id();
        $user = User::create([
            'external_id' => Uuid::uuid4()->toString(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'nick_name' => $request->nick_name,
            'email' => $request->email, 
            'primary_number' => $request->primary_number, 
            'secondary_number' => $request->secondary_number, 
            'expertise' => $request->expertise,  
            'password' => bcrypt($request->password), 
            'date_of_joining' => $request->date_of_joining, 
            'address' => $request->address, 
            'working_hours' => $request->working_hours, 
            'profile_pic' => $profile_pic_name, 
            'employer_id' => $request->employer,
            'created_by' => $created_by  
        ]);
        $user->roles()->sync([$request->role]);
        $this->sendCredentialEmailTouser($user,$request->email,$request->password);
        Session()->flash('flash_message', __('Employee successfully Created!'));
        return redirect()->to($request->back_url); 
    }

    public function profile(Request $request)
    {
        $data['back_url'] = false;
        $user = Auth::user(); 
        $data['user'] = $user;
        $data['is_profile'] = true;
        $data['roles'] = Role::where('name', '!=','owner')->pluck('name', 'id');
        
        return view('users.details.personal')->with($data);
    }

    /**
     * @param $external_id
     * @return mixed
     *    
     *  Show personal details
     */
    public function show($url_id, Request $request)
    { 
        $data['back_url'] = route('users.index');
        $user = $this->findByExternalId($url_id);
        $data['user'] = $user;
        $data['is_profile'] = $user->id == Auth::user()->id ? true : false; 
        $data['roles'] = Role::where('name', '!=','owner')->pluck('name', 'id');
        return view('users.details.personal')->with($data);
    } 
    /**
     * @param $external_id
     * @return mixed
     */
    public function edit($url_id, Request $request)
    {  
        $data['back_url'] = route('users.index'); 

        $user = $this->findByExternalId($url_id);
        
        $data['user'] = $user;
        $data['roles'] = Role::where('name', '!=','owner')->pluck('name', 'id');
        $data['years'] = $this->years();
        $data['months'] = $this->allMonths();
        $data['employers'] = User::whereHas(
            'roles', function($q){
                $q->where('name', 'admin');
            }
        )->pluck('first_name', 'id');
          
        return view('users.edit')->with($data);
    }

    /**
     * @param $external_id
     * @param UpdateUserRequest $request
     * @return mixed
     */
    public function update($external_id, UpdateUserRequest $request)
    { 
        $updated_by = Auth::id();
        $user = $this->findByExternalId($external_id);
        if($request->hasFile('profile_pic')){
            
            $first_name  = strtolower(str_replace(' ', '_',$request->first_name));

            $pic_name = $first_name ."_". time() .".". $request->profile_pic->extension(); 
            $path_profile_pic = 'storage/assets/employees/profile_pics/';
            $returned = $request->profile_pic->move(public_path($path_profile_pic), $pic_name); 

            $profile_pic_name = $path_profile_pic . $pic_name;
        } else {
            $profile_pic_name = $request->old_profile_pic;
        }

        $update_employee = [ 
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'nick_name' => $request->nick_name,
            'email' => $request->email, 
            'primary_number' => $request->primary_number, 
            'secondary_number' => $request->secondary_number, 
            'expertise' => $request->expertise,   
            'date_of_joining' => $request->date_of_joining, 
            'address' => $request->address, 
            'working_hours' => $request->working_hours, 
            'profile_pic' => $profile_pic_name, 
            'employer_id' => $request->employer,
            'updated_by' => $updated_by,
        ];

        
        if($request->password !== "") {
            $update_employee['password'] = bcrypt($request->password);
        }  
        $user->fill($update_employee)->save(); 
        $user->roles()->sync([$request->role]); 

        Session()->flash('flash_message', __('User successfully updated!'));
        return redirect()->to($request->back_url); 
    }

    public function destroy(Request $request,$external_id)
    {
        $employee = $this->findByExternalId($external_id);
        $employee->delete();
        Session()->flash('flash_message', __('User Delete!'));
        return redirect()->route('users.index'); 
    }

    public function updatePersonal(Request $request)
    {
        if($request->hasFile('profile_pic')){
            
            $first_name  = strtolower(str_replace(' ', '_',$request->first_name));

            $pic_name = $first_name ."_". time() .".". $request->profile_pic->extension(); 
            $path_profile_pic = 'storage/assets/employees/profile_pics/';
            $returned = $request->profile_pic->move(public_path($path_profile_pic), $pic_name); 

            $profile_pic_name = $path_profile_pic . $pic_name;

            // unlink(asset($request->old_profile_pic));
        } else {
            $profile_pic_name = $request->old_profile_pic;
        }

        $user = Auth::user();

        $user->update([
            'first_name' => $request->first_name ?? "",
            'last_name' => $request->last_name ?? "",
            'email' => $request->email ?? "",
            'expertise' => $request->expertise ?? "",
            'primary_number' => $request->primary_number ?? "",
            'secondary_number' => $request->secondary_number ?? "",
            'address' => $request->address ?? "",
            'profile_pic' => $profile_pic_name, 
        ]);

        return redirect()->back()->with('flash_message', 'Personal details successfully updated!');
    }
    
    /**
    * @param $external_id
    * @return mixed
    */
    public function findByExternalId($external_id)
    {
        return User::whereExternalId($external_id)->first();
    }
    
    /**
     *  Ajax delete (for sweet alert)
     */

    public function checkUserDelete(Request $request) 
    {
        $external_id = $request->external_id;
          
        $user = $this->findByExternalId($external_id);
  
        $user_enquiries = Enquiry::where('user_assigned_id', $user->id)->count(); 
        $user_clients = Client::where('user_id', $user->id)->count(); 

        if($user_enquiries === 0 && $user_clients === 0) {
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Sorry this user has entries in other moduels!",
            ]);
        }
    }
 
    public function ajaxUserDelete(Request $request)
    {
        $external_id = $request->external_id;  
        $user = $this->findByExternalId($external_id);
        $user->delete();

        Session()->flash('flash_message', __('Employee successfully deleted!'));
        return response()->json([
            'status' => true,
            'message' => "Employee deleted successfully!"
        ]);
    }

    /**
     *  @return mixed
     *  $users
     */
    public function getUserByName(Request $request)
    {
    
        $name = $request->get('q');
        $users = User::where('first_name', 'like', "%{$name}%")->where('last_name', 'like', "%{$name}%")->get();
        
        return response()->json($users);
    }
 
    public function checkEmailRepeat(Request $request)
    {    
        $id = $request->id;
        $email = $request->email;
        $email = User::where('email', $email)->first();

        if($email !== null) { 
            if($id == $email->id) { 
                echo "true";
            } else {
                echo "false";
            }
        } else {
            echo "true";
        } 
    }
    
    // Check repeat primary number of User/ Employee
    public function checkPrimaryNumberRepeat(Request $request)
    {     
        $id = $request->id;
        $number = $request->number;
        $number = User::where('primary_number', $number)->first();
  
        if($number !== null) { 
            if($id == $number->id) { 
                echo "true";
            } else {
                echo "false";
            }
        } else {
            echo "true";
        } 
    }

    public function sendCredentialEmailTouser($user,$email, $password, $email_template_id = 4)
    {
        $email_template = EmailTemplate::where('email_template_id', $email_template_id)->first();
        $template_variables = [
            '{#userid}' => $email,
            '{#password}' => $password
        ];

        foreach ($template_variables as $key => $value)
            $template_content = str_ireplace($key, $value, $email_template->content);

        $data['subject'] = $email_template->subject;
        $data['messagecontent'] = $template_content;
        $data['from_email'] = env('MAIL_FROM_EMAIL');
        $data['from_name'] = env('MAIL_FROM_NAME');        
        
        $email = $user->email;

        $emailSend = Mail::send('emails.email', $data, function($message) use($data, $email) {
            $message->subject($data['subject']);
            
            if(isset($data['from_email']) && isset($data['from_name'])){
                $message->from($data['from_email'], $data['from_name']);
            }
            $message->to($email);
        }); 
            
        $emailLogArray = [
            'template_id' => $email_template_id,
            'client_id' => 0,
            'client_email' => $email,
            'email_subject' => $data['subject'],
            'from_email' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'email_json' => json_encode($template_content)
        ];
                    
        EmailLog::create($emailLogArray);
                
    }
    
    private function allMonths(){
        $months = array(
            "1" => "January", "2" => "February", "3" => "March", "4" => "April",
            "5" => "May", "6" => "June", "7" => "July", "8" => "August",
            "9" => "September", "10" => "October", "11" => "November", "12" => "December",
        );
        return $months;
    }

    private function years(){

        $current_year = date('Y');
        $next_year = date('Y', strtotime(date("Y-m-d", time()) . " + 365 day"));
        
        $years = array(
            $current_year => $current_year,
            $next_year => $next_year
        );

        return $years;
    }
 
}
