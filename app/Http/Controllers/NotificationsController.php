<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\CategoryMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Make sure this is imported
use App\Models\AppTrack;
use App\Models\User;

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
            'menu_id' => 'required|integer',
            'page_section_master_id' => 'required|integer',
            'title' => 'required|array',
            'title.*' => 'required|string',
            'date' => 'required|array',
            'date.*' => 'required|date',
            'file' => 'required|array',
            'file.*.*' => 'required|mimes:pdf',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $flag = match ($user->role_id) {
            2 => 'A', // Admin
            3 => 'N', // Content Creator
            default => null
        };

        if (!$flag) {
            return response()->json(['message' => 'Invalid role'], 403);
        }

        $publisher = User::find($request->publisher_id);

        foreach ($request->title as $index => $title) {
            $filePath = null;

            // ✅ Generate unique application_id: NOTI + current datetime + index for uniqueness
            $applicationId = 'NOTI' . now()->format('YmdHis');

            if ($request->hasFile("file.$index")) {
                foreach ($request->file("file.$index") as $pdf) {
                    $filename = time() . '_' . $pdf->getClientOriginalName();
                    $filePath = $pdf->storeAs('notifications', $filename, 'public');

                    // ✅ Insert into Notifications
                    Notifications::create([
                        'application_id' => $applicationId,
                        'category_id' => $request->category_id,
                        'title' => $title,
                        'date' => $request->date[$index],
                        'file' => $filePath,
                        'user_id' => $user->id,
                        'flag' => $flag,
                        'role_id' => $user->role_id,
                        'publisher_id' => $request->publisher_id ?? null,
                    ]);

                    // ✅ Insert into AppTrack
                    AppTrack::create([
                        'application_id' => $applicationId,
                        'menu_id' => $request->menu_id,
                        'page_section_master_id' => $request->page_section_master_id,
                        'user_from' => $user->id,
                        'user_from_name' => $user->name,
                        'user_to' => $request->publisher_id,
                        'user_to_name' => $publisher ? $publisher->name : null,
                        'remarks' => 'Notification submitted: ' . $title,
                        'flag' => $flag,
                        'action' => 'Add',
                    ]);
                }
            } else {
                // ✅ Insert without file
                Notifications::create([
                    'application_id' => $applicationId,
                    'category_id' => $request->category_id,
                    'title' => $title,
                    'date' => $request->date[$index],
                    'file' => null,
                    'user_id' => $user->id,
                    'flag' => $flag,
                    'role_id' => $user->role_id,
                    'publisher_id' => $request->publisher_id ?? null,
                ]);

                AppTrack::create([
                    'application_id' => $applicationId,
                    'menu_id' => $request->menu_id,
                    'page_section_master_id' => $request->page_section_master_id,
                    'user_from' => $user->id,
                    'user_from_name' => $user->name,
                    'user_to' => $request->publisher_id,
                    'user_to_name' => $publisher ? $publisher->name : null,
                    'remarks' => 'Notification submitted: ' . $title,
                    'flag' => $flag,
                    'action' => 'Add',
                ]);
            }
        }

        return response()->json(['message' => 'Notifications saved successfully'], 201);
    }




    public function updateNotice(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:notifications,id',
            'title' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'date' => 'nullable|date',
            'menu_id' => 'required|integer',
            'page_section_master_id' => 'required|integer',
            'file' => 'nullable|file|mimes:pdf,docx,jpeg,png,jpg|max:2048'
        ]);
        $user = Auth::user();
        $newflag = match ($user->role_id) {
            2 => 'A', // Admin
            3 => 'U', // Content Creator
            default => null
        };
        $notice = Notifications::findOrFail($request->id);
        $notice->title = $request->title;
        $notice->category_id = $request->category_id;
        $notice->date = $request->date;
        $notice->flag = $newflag;
        if ($request->hasFile('file')) {
            //delete old fil
            if ($notice->file && Storage::disk('public')->exists($notice->file)) {
                Storage::disk('public')->delete($notice->file);
            }

            $path = $request->file('file')->store('public/notifications');
            $notice->file = str_replace('public/', '', $path); // store path without "public/"
        }

        $notice->save();
        // Log update in AppTrack
        $user = Auth::user();
        $userTo = User::find($notice->user_id); // assuming user_id exists in Notifications

        AppTrack::create([
            'menu_id' => $request->menu_id,
            'page_section_master_id' => $request->page_section_master_id,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $notice->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Notice updated: ' . $notice->title,
            'action' => 'Updated',
            'flag' => $newflag,
            'application_id' => $notice->application_id
        ]);


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
                LEFT JOIN users u ON u.id = ns.user_id order by ns.date desc
            ");
        } elseif (in_array($user->role_id, [3, 4])) {
            // Content Creator and Publisher
            $notifications = DB::select("
                SELECT ns.*, cm.category_name, u.name as addedby,u2.name as approver 
                FROM notifications ns
                INNER JOIN category_master cm ON cm.id = ns.category_id
                INNER JOIN users u ON u.id = ns.user_id
				LEFT JOIN users u2 on u2.id=ns.publisher_id order by ns.date desc    
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
            // Fetch the notice after approval
            $notice = Notifications::find($request->id);

            // Get current user (approver)
            $user = Auth::user();

            // Get notice creator
            $userTo = User::find($notice->user_id);

            // Add entry to AppTrack
            AppTrack::create([
                'menu_id' => $request->menu_id ?? null,
                'page_section_master_id' => $request->page_section_master_id ?? null,
                'user_from' => $user->id,
                'user_from_name' => $user->name,
                'user_to' => $notice->user_id,
                'user_to_name' => $userTo ? $userTo->name : null,
                'remarks' => 'Notice approved: ' . $notice->title,
                'action' => 'Approved',
                'flag' => 'A',
                'application_id' => $notice->application_id

            ]);

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
        $user = Auth::user();

        $userTo = User::find($notice->user_id);

        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $notice->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Notice Deleted: ' . $notice->title,
            'action' => 'Deleted',
            'flag' => 'D',
            'application_id' => $notice->application_id

        ]);

        return response()->json(['message' => 'Notice deleted successfully']);
    }


    public function rejectedNotification(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'remarks' => 'required|string',
        ]);

        $notice = Notifications::find($request->id);

        if (!$notice) {
            return response()->json([
                'success' => false,
                'message' => 'Notice not found',
            ], 404);
        }

        $notice->flag = 'R';
        $notice->rejected_remarks = $request->remarks;
        $notice->save();

        $user = Auth::user();
        $userTo = User::find($notice->user_id); // Get user_to from users table
        // Log into AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $notice->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Notification Rejected: ' . $request->remarks,
            'action' => 'Rejected',
            'flag' => 'R',
            'application_id' => $notice->application_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notice rejected and tracked successfully',
        ]);
    }

    public function getActivityLog()
    {
        $logs = DB::table('app_track as apt')
            ->select(
                'apt.*',
                'psm.page_section_name',
                'm.menu_name'
            )
            ->leftJoin('page_section_master as psm', 'psm.id', '=', 'apt.page_section_master_id')
            ->leftJoin('menu as m', 'm.id', '=', 'apt.menu_id')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $logs
        ]);
    }


}
