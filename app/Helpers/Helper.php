<?php

namespace App\Helpers;

// use App\Mail\SendEmail;
// use App\Mail\SendEmailViaQueue;
// use Storage, Mail;
// use Carbon\Carbon;
 
use App\Models\Client; 
use App\Models\User; 
use App\Models\Contacts;
use App\Models\Products; 
use App\Models\Holiday;

use Mail;
use App\Notifications\LeaveNotification;
use Illuminate\Support\Facades\Notification;

use App\Models\EmailLog;

use App\Models\SMSLog;
use App\Models\SMSTemplate;
use App\Models\SmsSettings;
use Auth;

class Helper
{

    public static function getUserType($user_id)
    {
        $user_types = [
            0 => "super_admin",
            1 => "admin",
            2 => "hr",
            3 => "employee"
        ];
        $user = User::find($user_id);
        return $user_types[$user->roles->first()->user_type];
    }

    public static function getUserFullName($user_id)
    {
        $user = User::find($user_id);
        return $user->first_name." ".$user->last_name;
    }

    public static function notifyToOwner($data)
    {
        $owner = User::find(1);
        Notification::send($owner, new LeaveNotification($data));
    }

    public static function replace_view_status($status, $type = 'ACTIVE', $default = '-')
    {
        $status_array = [];
        switch ($type) {
            case 'ACTIVE':
                $status_array = config('global.status_array');
                break;
        }

        return (isset($status_array[$status]) ? $status_array[$status] : $default);
    }

    public static function get_new_filename($file)
    {
        $actual_name = \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '-');
        $original_name = $actual_name;
        $extension = $file->getClientOriginalExtension();
        $i = 1;
        while ($exists = Storage::has($actual_name . "." . $extension)) {
            $actual_name = (string) $original_name . $i;
            $i++;
        }
        return $actual_name . "." . $extension;
    }
	
	public static function encrypt($string)
    {
        return $string;
        return $this->encrypt_decrypt("E", $string);
    }

    public static function decrypt($string)
    {
        return $string;
        return $this->encrypt_decrypt("D", $string);
    }
	
	private function encrypt_decrypt($action, $string)
    {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = env('APP_KEY');
        $secret_iv = 'RKCRM';

        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'E') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
	
	public static function getRecordOrder($sortOrder)
    {
        switch ($sortOrder) {
            case 'descend':
                $sortOrder = 'DESC';
                break;
            case 'ascend':
                $sortOrder = 'ASC';
                break;
            default:
                $sortOrder = 'DESC';
                break;
        }
        return $sortOrder;
    }
	
	public static function paginationData($request, $sortField = false)
    {
        if (!$request->size) {
            $request->size = 10;
        }
        if (!$request->sortField && !$sortField) {
            $request->sortField = 'created_at';
        }
        if ($sortField) {
            $request->sortField = $sortField;
        }
        if (!$request->sortOrder) {
            $request->sortOrder = "DESC";
        } else {
            $request->sortOrder = $this->getRecordOrder($request->sortOrder);
        }
        return $request;
    }
	
    public static function createImageFromBase64($file)
    {
        $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($file, 0, strpos($file, ',') + 1);
        // find substring fro replace here eg: data:image/png;base64,
        $newFile = str_replace($replace, '', $file);
        $newFile = str_replace(' ', '+', $newFile);
        $fileName = \Str::random(10) . '.' . $extension;

        Storage::put($fileName, base64_decode($newFile));

        return $fileName;
    }

    public static function createDocFromBase64($file, $old_filename)
    {
        $info = pathinfo($old_filename);
        $extension = $info['extension'];

        // $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($file, 0, strpos($file, ',') + 1);
        // find substring fro replace here eg: data:image/png;base64,
        $newFile = str_replace($replace, '', $file);
        $newFile = str_replace(' ', '+', $newFile);
        $fileName = \Str::random(10) . '.' . $extension;

        Storage::put($fileName, base64_decode($newFile));

        return $fileName;
    }

    public static function createBase64FromImage($imageName)
    {
        if (Storage::has($imageName)) {
            $image_parts = explode(".", $imageName);
            $img_extension = $image_parts[1];
            $imageString = 'data:image/' . $img_extension . ';base64,' . base64_encode(Storage::get($imageName));
            return $imageString;
        }
        return $imageString = null;
    }

    public static function transformShortcodeValue($item, $obj)
    {
        $column = $item['column'];
        $shortcode = $item['shortcode'];

        if (strpos($column, '->') !== false) {
            $properties = explode("->", $column);
            $value = $shortcode;
            if (is_array($properties) && count($properties)) {
                $tmpObj = true;
                foreach ($properties as $key) {
                    if ($tmpObj && !is_object($tmpObj)) {
                        $tmpObj = (isset($obj->{$key}) ? $obj->{$key} : false);
                        if (!$tmpObj) break;
                    } else if (is_object($tmpObj)) {
                        $tmpObj = (isset($tmpObj->{$key}) ? $tmpObj->{$key} : false);
                        if (!$tmpObj) break;
                    }
                }
                $value = (!empty($tmpObj) ? $tmpObj : $shortcode);
            }
        } else {
            $value = ($obj && isset($obj->{$column}) ? $obj->{$column} : $shortcode);
        }

        switch ($column) {
            case 'login_url':
                $value = '#';
                break;
        }
        return $value;
    }
    

   public static function sendEmail($user, $template_id, $shortcodes = [], $queue = false, $minute = 1)
    {
        if (!$template_id || !$user) return false;

        //Disable for Mayank
        if($user->user_id == 8) return true;

        if ($queue) {
            $when = now()->addMinutes($minute);
            try {
                Mail::to($user)->later($when, new SendEmailViaQueue($template_id, $shortcodes));

                $log = new EmailSmsLog;
                $log->user_id = $user->user_id;
                $log->template_id = $template_id;
                $log->type = 'EMAIL';
                $log->response = null;
                $log->save();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        } else {
            try {
                Mail::to($user)->send(new SendEmail($template_id, $shortcodes));

                $log = new EmailSmsLog;
                $log->user_id = $user->user_id;
                $log->template_id = $template_id;
                $log->type = 'EMAIL';
                $log->response = null;
                $log->save();
                return true;
            } catch (\Exception $e) {
                return $e;
            }
        }

        return false;
    }

    public static function shortcodes($user = false)
    {
        if (!$user) {
            return [];
        }

        $shortcodes = config()->get('shortcodes.magic_keywords');

        $keywords = [];

        $magic_keyword_keys = array_map(function ($item) {
            return $item['shortcode'];
        }, $shortcodes);

        $magic_keyword_values = [];

        $magic_keyword_values = array_map(function ($item) use ($user) {
            return $this->transformShortcodeValue($item, $user);
        }, $shortcodes);

        if (count($magic_keyword_values) == count($magic_keyword_keys)) {
            $keywords = array_combine($magic_keyword_keys, $magic_keyword_values);
        }

        return $keywords;
    }

    public static function profileProgress($u)
    {
        $progress = 0;

        $profiles = [
            'photo' => 15,
            'alt_mobileno' => 10,
            'address' => 15,
            'pincode' => 10,
            'city' => 10,
            'state_id' => 10,
            'country_id' => 10,
            'emr_contact_person' => 10,
            'emr_contact_number' => 10,
        ];

        foreach ($profiles as $column => $perc) {
            if ($u->{$column} != '') {
                $progress += $perc;
            }
        }

        return $this->decimal($progress);
    }

    public static function decimal($number, $decimal = 2)
    {
        $number = (float) $number;
        if (strpos($number, ".") === true) {
            //return sprintf('%0.2f', $number);
        }
        return round(number_format($number, $decimal));
    }
    
    public static function decimalNumber($nunber, $zero = 2, $dot = '.')
    {
        return number_format($nunber, $zero, $dot,'');
    }
	
	public static function addActionLog($user_id, $module, $module_id, $action, $old = [], $new = [])
    {
        $log = new ActionLog;
        $log->user_id = $user_id;
        $log->module = $module;
        $log->module_id = $module_id;
        $log->action = $action;
        if (!empty($old)) {
            $log->oldData = $old;
        }
        if (!empty($new)) {
            $log->newData = $new;
        }
        $log->save();
    }
	function get_client_info($cid){
		return Client::find($cid);
	}
	
	function getRows($file)
    {
        $replace = substr($file, 0, strpos($file, ',') + 1);
        $newFile = str_replace($replace, '', $file);
        $newFile = str_replace(' ', '+', $newFile);
        $rows = explode("\n", base64_decode($newFile));
        $array = array_map('str_getcsv', $rows);
        return $array;
    }

    function checkUserLimit($companyId)
    {
        $company = Company::find($companyId);
        $status = 1;
        if($company->plan_id)
        {
            $noOfUsers = Plan::find($company->plan_id);
            if(!$noOfUsers)
            {   
                return ['status'=>2];
            }
            $noOfUsers = $noOfUsers->no_of_users;
            $usersCount = User::where('company_id',$companyId)->count();
            $status = 1;
            if(($usersCount + 1) > $noOfUsers)
            {
               $status = 0;
            }
        }
        return ['status'=>$status];
    }

    // get section model to model id wise
    function sectionModel($modelId)
    {
        $sectionModel = null;
        switch ($modelId) {
            case 'contact':
                $sectionModel = new ContactSection;
                break;
            case 'product':
                $sectionModel = new ProductSection;
                break;
            case 'employee':
                $sectionModel = new EmployeeSection;
                break;
        }
        return $sectionModel;
    }

    // get field model to model id wise
    function fieldModel($modelId)
    {
        $fieldModel = null;
        switch ($modelId) {
            case 'contact':
                $fieldModel = new ContactField;
                break;
            case 'product':
                $fieldModel = new ProductField;
                break;
            case 'employee':
                $fieldModel = new EmployeeField;
                break;
        }
        return $fieldModel;
    }

    // get field value model to model id wise
    function fieldValueModel($modelId)
    {
        $fieldValueModel = null;
        switch ($modelId) {
            case 'contact':
            $fieldValueModel = new ContactValue;
                break;
            case 'product':
            $fieldValueModel = new ProductValue;
                break;
            case 'employee':
            $fieldValueModel = new EmployeeValue;
                break;
        }
        return $fieldValueModel;
    }

    // get field value
    function getFieldValue($type,$typeId)
    {
        $fieldValue = FieldValue::where('type',$type)->where('type_id',$typeId)->pluck('values')->toArray();
        // $fieldValue = implode('<br />',$fieldValue);
        return $fieldValue;
    }

    // get primary company contact
    function getCompanyContact($companyId)
    {
        $contact = User::where('company_id',$companyId)->where('company_contact_type','1')->orderBy('updated_at','DESC')->first();
        return $contact;
    }

    function getModelName($modelId)
    {
        $modelName = null;
        switch ($modelId) {
            case 'contact':
                $modelName = new Contacts;
                break;
            case 'product':
                $modelName = new Products;
                break;
            case 'employee':
                $modelName = new User;
                break;
        }
        return $modelName;
    }

    public static function storeEmailHistory($templateId,$type,$typeId,$senderId,$receiverId,$receiverEmail,$ccMail=null,$bccMail=null,$subject=null,$content=null)
    {
        $emailHistory = new EmailHistory();
        $emailHistory->email_template_id = $templateId;
        $emailHistory->type  = $type;
        $emailHistory->type_id = $typeId;
        $emailHistory->sender_id = $senderId;
        $emailHistory->receiver_id = $receiverId;
        $emailHistory->receiver_email = $receiverEmail;
        $emailHistory->is_send = 0;
        $emailTemplate = EmailTemplate::find($templateId);
        if($emailTemplate)
        {
            if(!$subject){
                $subject = $emailTemplate->subject;
            }
            if(!$content){
                $content = $emailTemplate->content;
            }
            $data['messagecontent'] = $content;
            $data['subject'] = $subject;
            Mail::send('emails.email', $data, function($message) use($receiverEmail,$ccMail,$bccMail,$subject){
                $message->to($receiverEmail);
                if($ccMail){
                    $message->cc($ccMail);
                }
                if($bccMail){
                    $message->bcc($bccMail);
                }
                $message->subject($subject);
                $message->from('info@unicepts.in');
             });
             if (Mail::failures()) {
                 $emailHistory->is_send = 0;
             }else{
                $emailHistory->is_send = 1;
             }
        }
        $emailHistory->save();
        return true;
    }

    public static function getPermissionIds($companyId)
    {
        $assignedPermssion = CompanyPermission::where('company_id',$companyId)->pluck('permission_id')->toArray();
        return $assignedPermssion;
    }

    public static function usersType($type=null)
    {
        $type_array = array(
            1 => 'Admin',
            2 => 'Normal',
            3 => 'Dealer',
            4 => 'Distributor'	
        );

        if($type){
           return $type_array[$type];
        }else{
            return $type_array;
        }
    }

    public static function emailTemplateCss()
    {
        return '/* -------------------------------------
                    GLOBAL RESETS
                ------------------------------------- */
                
                /*All the styling goes here*/
                
                img {
                border: none;
                -ms-interpolation-mode: bicubic;
                max-width: 100%; 
                }

                body {
                background-color: #f6f6f6;
                font-family: sans-serif;
                -webkit-font-smoothing: antialiased;
                font-size: 14px;
                line-height: 1.4;
                margin: 0;
                padding: 0;
                -ms-text-size-adjust: 100%;
                -webkit-text-size-adjust: 100%; 
                }

                table {
                border-collapse: separate;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border: none !important;
                width: 100%; }
                table td {
                    font-family: sans-serif;
                    font-size: 14px;
                    vertical-align: top; 
                }

                /* -------------------------------------
                    BODY & CONTAINER
                ------------------------------------- */

                .body {
                background-color: #f6f6f6;
                width: 100%; 
                }

                /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                .container {
                display: block;
                margin: 0 auto !important;
                /* makes it centered */
                max-width: 580px;
                padding: 10px;
                }

                /* This should also be a block element, so that it will fill 100% of the .container */
                .content {
                box-sizing: border-box;
                display: block;
                margin: 0 auto;
                max-width: 580px;
                padding: 10px; 
                }

                /* -------------------------------------
                    HEADER, FOOTER, MAIN
                ------------------------------------- */
                .main {
                background: #ffffff;
                border-radius: 3px;
                width: 100%; 
                }

                .wrapper {
                box-sizing: border-box;
                padding: 20px; 
                }

                .content-block {
                padding-bottom: 10px;
                padding-top: 10px;
                }

                .footer {
                clear: both;
                margin-top: 10px;
                text-align: center;
                width: 100%; 
                }
                .footer td,
                .footer p,
                .footer span,
                .footer a {
                    color: #999999;
                    font-size: 12px;
                    text-align: center; 
                }

                /* -------------------------------------
                    TYPOGRAPHY
                ------------------------------------- */
                h1,
                h2,
                h3,
                h4 {
                color: #000000;
                font-family: sans-serif;
                font-weight: 400;
                line-height: 1.4;
                margin: 0;
                margin-bottom: 30px; 
                }

                h1 {
                font-size: 35px;
                font-weight: 300;
                text-align: center;
                text-transform: capitalize; 
                }

                p,
                ul,
                ol {
                font-family: sans-serif;
                font-size: 14px;
                font-weight: normal;
                margin: 0;
                margin-bottom: 15px; 
                }
                p li,
                ul li,
                ol li {
                    list-style-position: inside;
                    margin-left: 5px; 
                }

                a {
                color: #3498db;
                text-decoration: underline; 
                }

                /* -------------------------------------
                    BUTTONS
                ------------------------------------- */
                .btn {
                box-sizing: border-box;
                width: 100%; }
                .btn > tbody > tr > td {
                    padding-bottom: 15px; }
                .btn table {
                    width: auto; 
                }
                .btn table td {
                    background-color: #ffffff;
                    border-radius: 5px;
                    text-align: center; 
                }
                .btn a {
                    background-color: #ffffff;
                    border: solid 1px #3498db;
                    border-radius: 5px;
                    box-sizing: border-box;
                    color: #3498db;
                    cursor: pointer;
                    display: inline-block;
                    font-size: 14px;
                    font-weight: bold;
                    margin: 0;
                    padding: 12px 25px;
                    text-decoration: none;
                    text-transform: capitalize; 
                }

                .btn-primary table td {
                background-color: #3498db; 
                }

                .btn-primary a {
                background-color: #3498db;
                border-color: #3498db;
                color: #ffffff; 
                }

                /* -------------------------------------
                    OTHER STYLES THAT MIGHT BE USEFUL
                ------------------------------------- */
                .last {
                margin-bottom: 0; 
                }

                .first {
                margin-top: 0; 
                }

                .align-center {
                text-align: center; 
                }

                .align-right {
                text-align: right; 
                }

                .align-left {
                text-align: left; 
                }

                .clear {
                clear: both; 
                }

                .mt0 {
                margin-top: 0; 
                }

                .mb0 {
                margin-bottom: 0; 
                }

                .powered-by a {
                text-decoration: none; 
                }

                hr {
                border: 0;
                border-bottom: 1px solid #f6f6f6;
                margin: 20px 0; 
                }

                /* -------------------------------------
                    RESPONSIVE AND MOBILE FRIENDLY STYLES
                ------------------------------------- */
            @media only screen and (max-width: 620px) {
                table[class=body] h1 {
                    font-size: 28px !important;
                    margin-bottom: 10px !important; 
                }
                table[class=body] p,
                table[class=body] ul,
                table[class=body] ol,
                table[class=body] td,
                table[class=body] span,
                table[class=body] a {
                    font-size: 16px !important; 
                }
                table[class=body] .wrapper,
                table[class=body] .article {
                    padding: 10px !important; 
                }
                table[class=body] .content {
                    padding: 0 !important; 
                }
                table[class=body] .container {
                    padding: 0 !important;
                    width: 100% !important; 
                }
                table[class=body] .main {
                    border-left-width: 0 !important;
                    border-radius: 0 !important;
                    border-right-width: 0 !important; 
                }
                table[class=body] .btn table {
                    width: 100% !important; 
                }
                table[class=body] .btn a {
                    width: 100% !important; 
                }
                table[class=body] .img-responsive {
                    height: auto !important;
                    max-width: 100% !important;
                    width: auto !important; 
                }
            }

                /* -------------------------------------
                    PRESERVE THESE STYLES IN THE HEAD
                ------------------------------------- */
            @media all {
                .ExternalClass {
                    width: 100%; 
                }
                .ExternalClass,
                .ExternalClass p,
                .ExternalClass span,
                .ExternalClass font,
                .ExternalClass td,
                .ExternalClass div {
                    line-height: 100%; 
                }
                .apple-link a {
                    color: inherit !important;
                    font-family: inherit !important;
                    font-size: inherit !important;
                    font-weight: inherit !important;
                    line-height: inherit !important;
                    text-decoration: none !important; 
                }
                #MessageViewBody a {
                    color: inherit;
                    text-decoration: none;
                    font-size: inherit;
                    font-family: inherit;
                    font-weight: inherit;
                    line-height: inherit;
                }
                .btn-primary table td:hover {
                    background-color: #34495e !important; 
                }
                .btn-primary a:hover {
                    background-color: #34495e !important;
                    border-color: #34495e !important; 
                } 
            }';
    }

    public static function emailTemplateBody()
    {
        return '<!doctype html>
                    <html>
                    <head>
                        <meta name="viewport" content="width=device-width" />
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <title>Email</title>
                        <style>
                        {{#all_css}}
                        </style>
                    </head>
                    <body>
                        {{#template_body}}
                    </body>
                </html>';
    }

    // For Deals & Discounts
    public static function is_weekend()
    {
        return in_array(date("l"), ["Saturday", "Sunday"]);
    }

    public static function is_weekday($weekday, $date = false)
    {
        if($weekday == "All") {
            return true;
        } 

        $date = date('Y-m-d');
        if($date != false) {
            $date = date('Y-m-d', strtotime($date));
        }

        $day_name = date("l", strtotime($date));

        return strtoupper($day_name) == strtoupper($weekday);
    }

    public static function is_holiday($date = false, $distributor_id)
    {  
        $date = date('Y-m-d');
        if($date != false) {
            $date = date('Y-m-d', strtotime($date));
        }  

        $holiday = Holiday::where('date', $date)->where('distributor_id', $distributor_id)->count(); 

        if($holiday > 0)  {
            return true;
        } else {
            return false;
        }
    }

    public static function is_event($client_id, $date, $field, $distributor_id) 
    {
        $date = date('Y-m-d');
        if($date != false) {
            $date = date('Y-m-d', strtotime($date));
        }

        $client = Client::where('id', $client_id)
        ->whereRaw("DAYOFMONTH($field) =?",  date('d', strtotime($date)))
        ->whereRaw("MONTH($field) =?", date('m', strtotime($date)))
        ->where('distributor_id', $distributor_id)->count(); 
        if($client > 0)  {
            return true;
        } else {
            return false;
        }
    }

    
}
