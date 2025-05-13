<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FAQ;
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
        // Check if the same order already exists
        $existingFAQ = FAQ::where('order', $request->order)->first();
        $user = Auth::user(); // This will get the authenticated user
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //before submit check roleid and set flag value
        // Determine flag based on role_id
        if ($user->role_id == 2) { //if admin upload 
            $flag = 'A';
        } elseif ($user->role_id == 3) { //if contentcreator upload
            $flag = 'N';
        }
        if ($existingFAQ) {
            return response()->json([
                'message' => 'Same order number already exists.',
                'status' => 'warning'
            ], 409);
        }

        // Ensure only English fields are required; convert empty values to null
        $faqData = [
            'english_title_question' => $request->english_question,
            'hindi_title_question' => $request->hindi_question ?: null,
            'khasi_title_question' => $request->khasi_question ?: null,
            'english_answer' => $request->english_answer,
            'hindi_answer' => $request->hindi_answer ?: null,
            'khasi_answer' => $request->khasi_answer ?: null,
            'user_id' => $user->id,
            'flag' => $flag,
            'role_id' => $user->role_id
        ];

        // Insert the data
        FAQ::create($faqData);

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
        if (in_array($user->role_id, [3, 4])) {
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
        return response()->json(['success' => true, 'message' => 'FAQ approved successfully']);
    }
}
