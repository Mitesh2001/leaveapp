<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Datatables;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Models\User;
use Ramsey\Uuid\Uuid; 
use App\Models\State;
use App\Models\Country;
use App\Helpers\Helper;

class ClientsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:client-view', ['only' => ['index', 'show']]);
		$this->middleware('permission:client-create', ['only' => ['create','store']]);
		$this->middleware('permission:client-update', ['only' => ['edit','assignAdmins','update']]);  
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        return view('clients.index');
    }

    /**
     * Make json respnse for datatables
     * @return mixed
     */
    public function anyData()
    {
        $user_type = Helper::getUserType(auth()->user()->id);   
        switch ($user_type){
            case "super_admin":
                $clients = Client::latest();
                break;
            case "admin":
                $clients = auth()->user()->clients;
                break;
        }    
        return Datatables::of($clients)
            ->addColumn('namelink', function ($clients) {
                $url = route('clients.show', $clients->external_id);
                return '<a data-search="' . $clients->name . '" href="'.$url.'" ">' . $clients->name . '</a>';
            })
            ->addColumn('name', function ($clients) {
                return $clients->name ;
            })
            ->addColumn('primary_email', function ($clients) {
                return $clients->primary_email ?? "";
            }) 
            ->addColumn('secondary_email', function ($clients) {
                return $clients->secondary_email ?? "";
            }) 
            ->addColumn('primary_number', function ($clients) {
                return $clients->primary_number ?? "";
            }) 
            ->addColumn('secondary_number', function ($clients) {
                return $clients->secondary_number ?? "";
            }) 
            ->addColumn('primary_contact_person', function ($clients) {
                return $clients->primary_contact_person ?? "";
            }) 
            ->addColumn('secondary_contact_person', function ($clients) {
                return $clients->secondary_contact_person ?? "";
            }) 
            ->addColumn('city', function ($clients) {
                return $clients->city ?? "";
            })
            ->addColumn('zipcode', function ($clients) {
                return $clients->zipcode ?? "";
            })
            ->addColumn('assigned_admins', function ($clients) {
                $admins = $clients->users->pluck('first_name','last_name')->toArray();
                return implode(", ",$admins); 
            })
            ->addColumn('action', function ($clients) {
                $html = "";
                $html .= '<form action="'.route('clients.destroy', $clients->external_id).'" class="d-flex" method="POST">';
                $html .= method_field("DELETE");
                $html .= '<div class="dropdown"><button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button> <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">';  
                    $html .=    '<a href="'.route('clients.show', $clients->external_id).'" class="dropdown-item">View Details</a>';
                    if (auth()->user()->can("client-update")) {
                        $html .=    '<a href="'.route('clients.admins', $clients->external_id).'" class="dropdown-item">Assigned Admins</a>';   
                    }
                    if (auth()->user()->can("client-update")) {
                        $html .=    '<a href="'.route('clients.edit', $clients->external_id).'" class="dropdown-item">Edit Client</a>';                   
                    }
                    if (auth()->user()->can("client-delete")) {
                        $html .= '<button type="submit" name="submit" value="' . __('Delete') . '" class="btn btn-default btn-sm text-danger"  data-toggle="tooltip" title="Delete Client"><i class="flaticon2-trash text-danger  text-hover-warning"></i>Remove</button>'; 
                        $html .= csrf_field();
                    }
				    $html .= '</form>';                   
                $html .= '</div>';
                        
                return $html;
            })
            ->rawColumns([
                'namelink',
                'name',
                'primary_email',
                'secondary_email',
                'primary_number',
                'secondary_number',
                'primary_contact_person',
                'secondary_contact_person',
                'city',
                'zipcode',
                'assigned_admins',
                'action'
            ])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {		  
        $data['users'] = User::all();
        $data['countries'] = Country::where('deleted', 0)->pluck('name', 'country_id');
        $data['states'] = State::where('country_id', '101')->where('deleted', 0)->pluck('name', 'state_id');
        return view('clients.create')->with($data);
    }

    /**
     * @param StoreClientRequest $request
     * @return mixed
     */
    public function store(StoreClientRequest $request)
    {    
        $user = Auth::user()->id;
        $store_data = [
            'external_id' => Uuid::uuid4()->toString(),
            'name' => $request->name,
            'service_type' => $request->service_type,
            'project_name' => $request->project_name,
            'primary_number'=>$request->primary_number,
            'secondary_number'=>$request->secondary_number,
            'primary_email'=>$request->primary_email,
            'secondary_email'=>$request->secondary_email,
            'primary_contact_person'=>$request->primary_contact_person, 
            'secondary_contact_person'=>$request->secondary_contact_person,
            'company_name' => $request->company_name,
            'company_type' => $request->company_type,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'country_id' => $request->country_id,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'notes' => $request->notes,
        ];

        if(auth()->user()->roles->first()->id == 2){
            return $store_data['users_list'] = $request->users_list;
        }

        if($request->country_id == 101){
            $store_data['state_id'] = $request->state_id;
            $store_data['state_name'] = "";
        }else{
            $store_data['state_id'] = "";
            $store_data['state_name'] = $request->state_name;
        } 
        $client = Client::create($store_data);

        $client->users()->sync($request->admins_list);   

        Session()->flash('flash_message', __('Client successfully added'));
        return redirect()->route('clients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $external_id
     * @return mixed
     */

    public function assignAdmins(Request $request,$external_id)
    {
        $client = $this->findByExternalId($external_id);
        $data['client'] = $client;
        $data['admins_list'] = $client->users;
        return view('clients.admins')->with($data);
    }

    public function updateAssignedAdmins(Request $request,$external_id)
    {
        $request->validate([
            'admins_list' => 'required'
        ]);
        $client = $this->findByExternalId($external_id);
        $client->users()->sync($request->admins_list);
        Session()->flash('flash_message', __('Admins successfully Assigned'));
        return redirect()->route('clients.index');
    }

    public function show(Request $request,$external_id)
    {   
        $data['client'] = $this->findByExternalId($external_id);
        return view('clients.show')->with($data); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $external_id
     * @return mixed
     */
    public function edit($external_id)
    { 
        $data['countries'] = Country::where('deleted', 0)->pluck('name', 'country_id');
        $data['states'] = State::where('country_id', '101')->where('deleted', 0)->pluck('name', 'state_id');
        $client = Client::where('external_id',$external_id)->first();
        $data['admins_list'] = $client->users;
        $data['client'] = $client;
        return view('clients.edit')->with($data); 
    }

    /**
     * @param $external_id
     * @param UpdateClientRequest $request
     * @return mixed
     */
    public function update($external_id, UpdateClientRequest $request)
    {
        $client = $this->findByExternalId($external_id);
        $update_arr = [
            'name' => $request->name,
            'service_type' => $request->service_type,
            'project_name' => $request->project_name,
            'primary_number'=>$request->primary_number,
            'secondary_number'=>$request->secondary_number,
            'primary_email'=>$request->primary_email,
            'secondary_email'=>$request->secondary_email,
            'primary_contact_person'=>$request->primary_contact_person, 
            'secondary_contact_person'=>$request->secondary_contact_person,
            'company_name' => $request->company_name,
            'company_type' => $request->company_type,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'country_id' => $request->country_id,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'notes' => $request->notes,
        ]; 
        
        $client->fill($update_arr)->save();
        
        $client->users()->sync($request->admins_list);

        Session()->flash('flash_message', __('Client successfully updated'));
        return redirect()->route('clients.index');
    }

    public function destroy(Request $request, $external_id)
    {
        $client = $this->findByExternalId($external_id);
        $client->delete();
        Session()->flash('flash_message', __('Client Deleted !'));
        return redirect()->route('clients.index');
    }

    /**
     * @param $external_id
     * @param Request $request
     * @return mixed
     */
    public function updateAssign($external_id, Request $request)
    {
    
        $user = User::where('external_id', $request->user_external_id)->first();
        $client = Client::with('user')->where('external_id', $external_id)->first();
        $client->updateAssignee($user);

        Session()->flash('flash_message', __('New user is assigned'));
        return redirect()->back();
    }

    public function findByExternalId($external_id)
    {
        return Client::where('external_id', $external_id)->firstOrFail();
    }

    public function checkPrimaryNumberRepeat(Request $request)
    {    
        echo "false"; 
        // $id = $request->id;
        // $number = $request->number;  

        // $contact = Contact::where('primary_number', $number)->first();

        // if(!empty($contact)) {
        //     if($contact->client_id == $id) {
        //         echo  "true";
        //     } else {
        //         echo  "false";
        //     }
        // } else {
        //     echo "true";
        // }  
    }

    // Check repeat email of client
    public function checkEmailRepeat(Request $request)
    {    
        echo "false";
        // $id = $request->id;
        // $email = $request->email; 

        // $client = Client::where('primary_email', $email)->first();
 
        // if(!empty($contact)) {   
        //     if($contact->client_id == $id) {
        //         echo  "true";
        //     } else {
        //         echo  "false";
        //     }
        // } else {
        //     echo "true";
        // } 
    }
  
    public function getAdminsList(Request $request)
    {
        $name = $request->get('q');
        $admins = User::where('first_name', 'like', "%{$name}%")->whereHas(
            'roles', function($q){
                $q->where('name', 'admin');
            }
        )->get();
        return response()->json($admins);
    }
}
