<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Auth;
use Validator;

class EmailsController extends Controller
{
    public function __construct(){      
		$this->middleware('permission:email-templates-view', ['only' => ['index']]); 
        $this->middleware('permission:email-template-create', ['only' => ['create', 'store']]); 
		$this->middleware('permission:email-template-update', ['only' => ['edit','update']]); 
    }
 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        return view('email.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('email.create');
    }    
        /**
     * Make json respnse for datatables
     * @return mixed
     */
    public function anyData()
    {
        $emailTemplate = EmailTemplate::select(['email_template_id', 'name', 'subject'])->get();  
  
        return Datatables::of($emailTemplate)
            ->addColumn('action', function ($emailTemplate) {
                $html = ""; 
                if(\Entrust::can('email-template-update'))
                $html .= '<a href="'.route('emails.edit_template',encrypt($emailTemplate->email_template_id)).'" class="btn btn-link" data-toggle="tooltip" title="Edit Email"><i class="flaticon2-pen text-success"></i></a>';
                return $html;
            })  
            ->rawColumns(['action'])
            ->make(true);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'subject' => 'required|max:200',
            'content' => 'required',
        ],
            ['content.required' => 'The email template field is required.']
        );
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }
        
        $id = decrypt($id); 
        $user_id = Auth::user()->id;
        $user = User::find($user_id); 
        $emailTemplate = EmailTemplate::find($id);
 
        $emailTemplate->event_type = $request->event_type;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->content = $request->content;
  
        $emailTemplate->save();

        Session()->flash('flash_message', __('Email template successfully updated'));
        return redirect()->route('emails.index');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        EmailTemplate::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'content' => $request->content,           
            'event_type' => $request->event_type              
        ]);
          
        Session()->flash('flash_message', __('Email template successfully added'));
        return redirect()->route('emails.index');
    }

    public function edit($id)
    {
        
        $id = decrypt($id); 

        $template = EmailTemplate::where('email_template_id', $id)->firstOrFail();
        
        $data['email'] = $template;

        return view('email.edit')->with($data);
    } 
}
