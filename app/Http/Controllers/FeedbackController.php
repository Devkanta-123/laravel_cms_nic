<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Helpers\HtmlSanitizer;

class FeedbackController extends Controller
{
    public function getFeedBacks()
    {
        $feedbacks = Feedback::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($feedback) {
                return [
                    'id'        => $feedback->id,
                    'name'      => strip_tags($feedback->name),
                    'phone_no'  => strip_tags($feedback->phone_no),
                    'email'     => strip_tags($feedback->email),
                    'message'   => strip_tags($feedback->message),
                    'created_at' => $feedback->created_at,
                    'updated_at' => $feedback->updated_at,
                ];
            });

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

        // ✅ Sanitize all validated fields
        $sanitized = array_map(function ($value) {
            return HtmlSanitizer::sanitize($value ?? '');
        }, $validated);

        // ✅ Create feedback entry with sanitized data
        $feedback = Feedback::create([
            'name'     => $sanitized['name'],
            'phone_no' => $sanitized['phone'],
            'email'    => $sanitized['email'],
            'message'  => $sanitized['message'],
        ]);

        // ✅ Return JSON response to Vue
        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully! We will get back to you as soon as possible.',
            'data'    => $feedback
        ], 201);
    }
}
