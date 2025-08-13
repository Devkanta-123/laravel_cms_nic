<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function getFeedBacks()
    {
        $feedbacks = Feedback::orderBy('created_at', 'desc')->get();

        return response()->json($feedbacks);
    }

    public function addFeedback(Request $request)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|digits:10',
            'message' => 'required|string',
        ]);

        // ✅ Create feedback entry
        $feedback = Feedback::create([
            'name'     => $validated['name'],
            'phone_no' => $validated['phone'], 
            'email'    => $validated['email'],
            'message'  => $validated['message'],
        ]);

        // ✅ Return JSON response to Vue
        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully! We will get back to you as soon as possible.',
            'data'    => $feedback
        ], 201);
    }
}
