<?php
namespace App\Http\Controllers;

use Notifynder;
use App\Models\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Log;

class NotificationsController extends Controller
{

    public function index()
    {
        return redirect()->route("dashboard");
    }
    /**
     * Mark a notification read
     * @param Request $request
     * @return mixed
     */
    public function markRead(Request $request, $redirect = false)
    {
        $user = auth()->user();
        $notification = $user->unreadNotifications()->where('id', $request->id)->first();
        $notification->markAsRead();        
        return redirect()->back();
    }

    /**
     * Mark all notifications as read
     * @return mixed
     */
    public function markAll()
    {
        $user = User::find(\Auth::id());
    
        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
        return redirect()->back();
    }
}
