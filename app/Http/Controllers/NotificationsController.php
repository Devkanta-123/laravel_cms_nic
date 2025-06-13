<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\CategoryMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Make sure this is imported

class NotificationsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    }



    public function submitNotificationsData(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer',
            'title' => 'required|array',
            'title.*' => 'required|string',
            'date' => 'required|array',
            'date.*' => 'required|date',
            'file' => 'required|array',
            'file.*.*' => 'required|mimes:pdf', // 6 MB max file size
        ]);


        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        // Determine flag based on role_id
        if ($user->role_id == 2) {
            $flag = 'A'; // Admin update - auto approved
        } elseif ($user->role_id == 3) {
            $flag = 'U'; // Content creator update - mark for review/update
        } else {
            return response()->json(['message' => 'Invalid role'], 403);
        }

        // Process each row
        foreach ($request->title as $index => $title) {
            // Check if files exist for this row
            if ($request->hasFile("file.$index")) {
                foreach ($request->file("file.$index") as $pdf) {
                    $filename = time() . '_' . $pdf->getClientOriginalName();
                    $filePath = $pdf->storeAs('notifications', $filename, 'public');

                    // Store row-wise data in the database
                    Notifications::create([
                        'category_id' => $request->category_id,
                        'title' => $title,
                        'date' => $request->date[$index],
                        'file' => $filePath,
                        'user_id' => $user->id,
                        'flag' => $flag,
                        'role_id' => $user->role_id
                    ]);
                }
            } else {
                // Insert a row without a file if no file exists for this row
                Notifications::create([
                    'category_id' => $request->category_id,
                    'title' => $title,
                    'date' => $request->date[$index],
                    'file' => null, // No file uploaded
                    'user_id' => $user->id,
                    'flag' => $flag,
                    'role_id' => $user->role_id
                ]);
            }
        }

        return response()->json(['message' => 'Notifications save successfully'], 201);
    }

    public function updateNotice(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:notifications,id',
            'title' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'date' => 'nullable|date',
            'file' => 'nullable|file|mimes:pdf,docx,jpeg,png,jpg|max:2048'
        ]);

        $notice = Notifications::findOrFail($request->id);
        $notice->title = $request->title;
        $notice->category_id = $request->category_id;
        $notice->date = $request->date;

        if ($request->hasFile('file')) {
            //delete old fil
            if ($notice->file && Storage::disk('public')->exists($notice->file)) {
                Storage::disk('public')->delete($notice->file);
            }

            $path = $request->file('file')->store('public/notifications');
            $notice->file = str_replace('public/', '', $path); // store path without "public/"
        }

        $notice->save();

        return response()->json(['status' => 'success']);
    }

    public function getAllNotifications(Request $request)
    {
        $user = Auth::user();
        // if (!$user) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        $flag = $request->query('flag');

        // If flag=A is explicitly passed, return only approved notifications
        if ($flag === 'A') {
            $notifications = Notifications::where('flag', 'A')
                ->orderBy('date', 'desc')
                ->get();
            return response()->json($notifications, 200);
        }


        // Role-based logic
        if ($user->role_id == 2) {
            // Admin - all notifications
            $notifications = DB::select("
                SELECT ns.*, cm.category_name, u.name as addedby 
                FROM notifications ns
                INNER JOIN category_master cm ON cm.id = ns.category_id
                INNER JOIN users u ON u.id = ns.user_id
            ");
        } elseif (in_array($user->role_id, [3, 4])) {
            // Content Creator and Publisher
            $notifications = DB::select("
                SELECT ns.*, cm.category_name, u.name as addedby 
                FROM notifications ns
                INNER JOIN category_master cm ON cm.id = ns.category_id
                INNER JOIN users u ON u.id = ns.user_id
            ");
        } else {
            return response()->json(['message' => 'Invalid role'], 403);
        }

        return response()->json($notifications, 200);
    }


    public function approveNoticeBoard(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:notifications,id',
        ]);

        $updated = DB::table('notifications')
            ->where('id', $request->id)
            ->update([
                'flag' => 'A',
                'updated_at' => Carbon::now()
            ]);

        if ($updated) {
            return response()->json(['message' => 'Notice approved successfully.'], 200);
        } else {
            return response()->json(['message' => 'Approval failed or already approved.'], 400);
        }
    }

    public function getNotificationsByCategory($category_name)
    {
        // First, check if the category name exists and get its ID
        $category_id = CategoryMaster::where('category_name', $category_name)->value('id');

        if ($category_id) {
            // Fetch notifications where category_id matches the retrieved category ID
            $notifications = Notifications::where('category_id', $category_id)
              ->orderBy('date', 'desc')
              ->get();
            return response()->json($notifications, 200);
        } else {
            return response()->json(['message' => 'Data for this Category not found'], 404);
        }
    }

    public function getNotificationsForCurrentMonth(Request $request)
    {
        $flag = $request->query('flag', 'A'); // default to 'A'

        $notifications = Notifications::whereMonth('date', date('m'))
            ->where('flag', $flag)
            ->get();

        return response()->json($notifications, 200);
    }

    public function getRecruitmentsForCurrentMonth()
    {
        $notifications = Notifications::where('category_id', 3)->whereMonth('date', date('m'))->get();
        return response()->json($notifications, 200);
    }

    public function deleteNotification(Request $request)
    {
        $id = $request->input('id');
        $notice = Notifications::find($id);

        if (!$notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }

        // Delete the file if it exists
        if ($notice->file && Storage::disk('public')->exists($notice->file)) {
            Storage::disk('public')->delete($notice->file);
        }

        // Delete the record
        $notice->delete();

        return response()->json(['message' => 'Notice deleted successfully']);
    }
}
