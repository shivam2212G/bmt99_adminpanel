<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::all();
        return view('pages.notifications.index', compact('notifications'));
    }

    public function update(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);

        $notification->title = $request->title;
        $notification->message = $request->message;

        if ($request->hasFile('image')) {

            // Delete old image if exists
            if ($notification->image && file_exists(public_path($notification->image))) {
                unlink(public_path($notification->image));
            }

            // Upload new image
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            // FIXED ✔
            $file->move(public_path('notifications'), $filename);

            $notification->image = 'notifications/' . $filename;
        }

        $notification->save();

        return redirect()->back()->with('success', 'Notification updated successfully!');
    }

    public function welcomeNotification()
    {
        $welcomeNotification = Notification::find(1); // Assuming the welcome notification has ID 1
        return response()->json($welcomeNotification);
    }
}
