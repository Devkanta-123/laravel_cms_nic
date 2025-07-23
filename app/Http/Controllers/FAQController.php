<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FAQ;
use App\Models\User;
use App\Models\AppTrack;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FAQController extends Controller
{
    //
    public function index()
    {
        return view('faq');
    }
    public function submitFAQData(Request $request)
    {
        $user = Auth::user(); // Get authenticated user
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Determine flag based on role_id
        if ($user->role_id == 2) {
            $flag = 'A';
        } elseif ($user->role_id == 3) {
            $flag = 'N';
        } else {
            $flag = null;
        }

        // Get the next order number by finding the max order and adding 1
        $maxOrder = FAQ::max('order');
        $nextOrder = $maxOrder ? $maxOrder + 1 : 1;

        $applicationId = 'FAQ' . now()->format('YmdHis');
        $publisher = User::find($request->publisher_id);

        $faqData = [
            'english_title_question' => $request->english_question,
            'hindi_title_question' => $request->hindi_question ?: null,
            'khasi_title_question' => $request->khasi_question ?: null,
            'english_answer' => $request->english_answer,
            'hindi_answer' => $request->hindi_answer ?: null,
            'khasi_answer' => $request->khasi_answer ?: null,
            'order' => $nextOrder,
            'user_id' => $user->id,
            'flag' => $flag,
            'role_id' => $user->role_id,
            'publisher_id' => $request->publisher_id ?? null,
            'application_id' => $applicationId
        ];

        // Insert into FAQ
        FAQ::create($faqData);

        // Track submission
        AppTrack::create([
            'application_id' => $applicationId,
            'menu_id' => $request->menu_id,
            'page_section_master_id' => $request->page_section_master_id,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $request->publisher_id,
            'user_to_name' => $publisher ? $publisher->name : null,
            'remarks' => 'FAQ submitted: ' . $request->english_question,
            'flag' => $flag,
            'action' => 'Add',
        ]);

        return response()->json([
            'message' => 'Data saved successfully!',
            'status' => 'success'
        ], 200);
    }




    public function getFAQData(Request $request)
    {
        $flag = (int) $request->query('flag');
        $user = Auth::user();
        if ($flag === 1) {
            $faqs = FAQ::where('flag', 'A')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
            return response()->json(data: $faqs);
        } elseif ($flag === 2) {
            $faqs = FAQ::where('flag', 'A')
                ->orderBy('order', 'asc')
                ->get();
            return response()->json(data: $faqs);
        }

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        // For role_id 3 or 4 (admin or moderator)
        if (in_array($user->role_id, [2,3, 4])) {
            $faqs = DB::table('faqs as f')
                ->join('users as u', 'u.id', '=', 'f.user_id')
                ->select('f.*', 'u.name as addedby')
                ->orderBy('f.created_at', 'desc')
                ->get();
        } else {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json(data: $faqs);
    }

    public function approveFAQ(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:faqs,id'
        ]);
        $faq = FAQ::find($request->id);
        $faq->flag = 'A'; // Approve
        $faq->save();
        if (!$faq) {
            return response()->json(['message' => 'FAQ not found'], 404);
        }
        $user = Auth::user(); // This will get the authenticated user
        $userTo = User::find($faq->user_id);
        AppTrack::create([
            'application_id' => $faq->application_id,
            'menu_id' => $request->menu_id,
            'page_section_master_id' => $request->page_section_master_id,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $faq->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'FAQ Approved: ' . $faq->english_question,
            'flag' => 'A',
            'action' => 'Approved',
        ]);
        return response()->json(['success' => true, 'message' => 'FAQ approved successfully']);
    }


    public function updateFAQ(Request $request, $id)
    {
        $request->validate([
            'english_question' => 'required|string',
            'english_answer' => 'required|string',
            'menu_id' => 'required|integer',
            'page_section_master_id' => 'required|integer',
        ]);
        $faq = FAQ::find($id);
        if (!$faq) {
            return response()->json(['message' => 'FAQ not found'], 404);
        }
        // Check for duplicate order within the same section
        $duplicate = FAQ::where('order', $request->order)
            ->where('id', '!=', $id)
            ->first();
        $user = Auth::user(); // This will get the authenticated user
        $publisher = User::find($request->publisher_id);

        if ($duplicate) {
            return response()->json(['message' => 'Same order number already exists'], 409);
        }

        $faq->update([
            'english_title_question' => $request->english_question,
            'english_answer' => $request->english_answer,
            'hindi_title_question' => $request->hindi_question,
            'hindi_answer' => $request->hindi_answer,
            'khasi_title_question' => $request->khasi_question,
            'khasi_answer' => $request->khasi_answer,
            'order' => $faq->order,
            'menu_id' => $request->menu_id,
            'page_section_master_id' => $request->page_section_master_id,
            'publisher_id' => $request->publisher_id ?? 0,
            'flag' => 'U',
        ]);
        AppTrack::create([
            'application_id' => $faq->application_id,
            'menu_id' => $request->menu_id,
            'page_section_master_id' => $request->page_section_master_id,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $request->publisher_id,
            'user_to_name' => $publisher ? $publisher->name : null,
            'remarks' => 'FAQ Updated: ' . $faq->english_question,
            'flag' => 'U',
            'action' => 'Updated',
        ]);

        return response()->json(['message' => 'FAQ updated successfully']);
    }
    public function deleteFAQ(Request $request)
    {
        $id = $request->input('id');
        $faq = FAQ::find($id);

        if (!$faq) {
            return response()->json(['message' => 'FAQ Data not found'], 404);
        }
        // Delete the  faq record
        $faq->delete();
        $user = Auth::user();
        $userTo = User::find($faq->user_id);
        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $faq->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'FAQ Deleted: ' . $faq->english_title_question,
            'action' => 'Deleted',
            'flag' => 'D',
            'application_id' => $faq->application_id

        ]);

        return response()->json(['message' => 'FAQ has been deleted']);
    }

    public function rejectedFAQ(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'remarks' => 'required|string',
        ]);

        $faq = FAQ::find($request->id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'faq not found',
            ], 404);
        }

        $faq->flag = 'R';
        $faq->rejected_remarks = $request->remarks;
        $faq->save();

        $user = Auth::user();
        $userTo = User::find($faq->user_id); // Get user_to from users table
        // Log into AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $faq->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'FAQ Rejected: ' . $request->remarks,
            'action' => 'Rejected',
            'flag' => 'R',
            'application_id' => $faq->application_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'faq rejected and tracked successfully',
        ]);
    }
}
