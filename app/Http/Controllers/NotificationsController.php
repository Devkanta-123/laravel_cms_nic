<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\CategoryMaster;
use Auth;
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
            'file.*.*' => 'required|mimes:pdf|max:6144', // 6 MB max file size
        ]);

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
                    ]);
                }
            } else {
                // Insert a row without a file if no file exists for this row
                Notifications::create([
                    'category_id' => $request->category_id,
                    'title' => $title,
                    'date' => $request->date[$index],
                    'file' => null, // No file uploaded
                ]);
            }
        }

        return response()->json(['message' => 'Notifications save successfully'], 201);
    }

    public function getAllNotifications()
    {
        $notifications = Notifications::all();
        return response()->json($notifications, 200);
    }
    public function getNotificationsByCategory($category_name)
    {
        // First, check if the category name exists and get its ID
        $category_id = CategoryMaster::where('category_name', $category_name)->value('id');

        if ($category_id) {
            // Fetch notifications where category_id matches the retrieved category ID
            $notifications = Notifications::where('category_id', $category_id)->get();
            return response()->json($notifications, 200);
        } else {
            return response()->json(['message' => 'Data for this Category not found'], 404);
        }
    }

    public function getNotificationsForCurrentMonth()
    {
        $notifications = Notifications::whereMonth('date', date('m'))->get();
        return response()->json($notifications, 200);
    }
    public function getRecruitmentsForCurrentMonth()
    {
        $notifications = Notifications::where('category_id', 3)->whereMonth('date', date('m'))->get();
        return response()->json($notifications, 200);
    }



}
