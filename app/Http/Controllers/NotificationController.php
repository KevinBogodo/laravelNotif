<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = $this->user->notifications()->orderBy('read')->get(['id','title','content','read','created_at']);
        return response()->json($notifications->toArray());
    }

    public function show($id)
    {
        $notifications = Notification::findOrFail($id);
        //  update read state
        $notifications->read = 1;
        $notifications->save();
        return response()->json($notifications->toArray());
        
        
    }
    
   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
        $notification->read = $request->read;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
        if($notification->delete()) {
            return response()->json(
                [
                    'status' => true,
                    'notification' => $notification,
                ]);
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'can not be deleted',
                ]);
        }
    }


    protected function guard()
    {
        return Auth::guard();
    }
}
