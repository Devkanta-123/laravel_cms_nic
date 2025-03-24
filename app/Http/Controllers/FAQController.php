<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FAQ;

class FAQController extends Controller
{
    //
    public function index()
    {
        return view('faq');
    }
    public function submitFAQData(Request $request)
    {
        // Validate request data
        // $request->validate([
        //     'english_question' => 'required|string',
        //     'english_answer' => 'required|string',
        //     'hindi_question' => 'required|string',
        //     'hindi_answer' => 'required|string',
        //     'khasi_question' => 'required|string',
        //     'khasi_answer' => 'required|string',
        //     'order' => 'required|integer'
        // ]);

        // Check if the same order already exists
        $existingFAQ = FAQ::where('order', $request->order)->first();

        if ($existingFAQ) {
            return response()->json([
                'message' => 'same order number already exists.',
                'status' => 'warning'
            ], 409);
        }

        // Insert data if order doesn't exist
        FAQ::create([
            'english_title_question' => $request->english_question,
            'hindi_title_question' => $request->hindi_question,
            'khasi_title_question' => $request->khasi_question,
            'english_answer' => $request->english_answer,
            'hindi_answer' => $request->hindi_answer,
            'khasi_answer' => $request->khasi_answer,
            'order' => $request->order
        ]);

        return response()->json([
            'message' => 'Data saved successfully!',
            'status' => 'success'
        ], 200);
    }


    public function getFAQData(){
        $faqs = FAQ::orderBy('order', 'asc')->get();
        return response()->json($faqs);
    }

}
