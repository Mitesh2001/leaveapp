<?php
namespace App\Http\Controllers;
 
use App\Models\Role;
use App\Models\Permission;
use App\Http\Requests\Role\StoreRoleRequest;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Ramsey\Uuid\Uuid;

class RolesController extends Controller
{
    public $user_types = [
        0 => "super_admin",
        1 => "admin",
        2 => "hr",
        3 => "employee"
    ];
    /**
     * RolesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:role-view', ['only' => ['index','show']]);
		$this->middleware('permission:role-create', ['only' => ['create','store']]);
		$this->middleware('permission:role-update', ['only' => ['update']]);
    }

    /**
     * Make json respnse for datatables
     * @return mixed
     */
    public function indexData()
    {
        $roles = Role::select(['id', 'name', 'user_type','external_id', 'display_name'])->get();
        return Datatables::of($roles)
            ->addColumn('namelink', function ($roles) {
                return '<a href="'.route('roles.show', $roles->external_id).'">'.$roles->display_name.'</a>';
            })
            ->editColumn('permissions', function ($roles) {
                return $roles->permissions->map(function ($permission) {
                    return $permission->display_name;
                })->implode("<br>");
            }) 
            ->editColumn('user_type', function ($roles) {
                return ucwords($this->user_types[$roles->user_type]);
            })
            ->editColumn('view_button', function ($roles) {
                return '<a href="'.route('roles.show', $roles->external_id).'"><i class="flaticon-eye text-success text-hover-primary" data-enquiry-id="'.$roles->external_id.'" data-toggle="tooltip" title="View Details"></i></a>';
            }) 
            ->rawColumns(['namelink','view_button','user_type'])
            ->make(true);
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return view('roles.index');
    }

    /**
     * @return mixed
     */
    public function create()
    {
        return view('roles.create')->with('user_types',$this->user_types);
    }

    /**
     * @return mixed
     */
    public function show($external_id)
    {
        $permissions_grouping = Permission::all()->groupBy('grouping');        
        $data['permissions_grouping'] = $permissions_grouping;
        $data['user_types'] = $this->user_types;
        return view('roles.show')
        ->withRole(Role::whereExternalId($external_id)->first())
        ->with($data);
    }

    /**
     * @param StoreRoleRequest $request
     * @return mixed
     */ 
    public function store(StoreRoleRequest $request)
    {
        $roleName = $request->name;
        $roleDescription = $request->description;
        Role::create([
            'external_id' => Uuid::uuid4()->toString(),
            'name' => strtolower($roleName),
            'display_name' => ucfirst($roleName),
            'user_type' => $request->user_type,
            'description' => $roleDescription
        ]);
        Session()->flash('flash_message', __('Role successfully created!'));
        return redirect()->route('roles.index');
    }

    /**
     * @param $external_id
     * @return mixed
     */
    public function destroy($external_id)
    { 
        //
    }

    /**
     * @param Request $request
     * @return mixed
     */ 
    public function update(Request $request, $external_id)
    {
        $allowed_permissions = [];

        if ($request->input('permissions') != null) {
            foreach ($request->input('permissions')
                     as $permissionId => $permission) {
                if ($permission === '1') {
                    $allowed_permissions[] = (int)$permissionId;
                }
            }
        } else {
            $allowed_permissions = [];
        }

        $role = Role::whereExternalId($external_id)->first();
        $role->permissions()->sync($allowed_permissions);
        $role->display_name = $request->display_name;
        $role->user_type = $request->user_type;
        $role->description = $request->description;
        $role->save();
        Session()->flash('flash_message', __('Role successfully updated!'));
        return redirect()->route('roles.index');
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
    public function checkNameRepeat(Request $request)
    {
        $name = $request->name; 

        $role = Role::where('name', $name)->first();
 
        if(!empty($role)) {
            echo  "false";
        } else {
            echo "true";
        } 
    }

    public function findByExternalId($external_id)
    {
        return Role::where('external_id', $external_id)->firstOrFail();
    }
}
