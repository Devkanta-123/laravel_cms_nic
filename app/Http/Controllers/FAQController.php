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
        // Check if the same order already exists
        $existingFAQ = FAQ::where('order', $request->order)->first();

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
            'order' => $request->order
        ];

        // Insert the data
        FAQ::create($faqData);

        return response()->json([
            'message' => 'Data saved successfully!',
            'status' => 'success'
        ], 200);
    }



    public function getFAQData()
    {
        $faqs = FAQ::orderBy('order', 'asc')->get();
        return response()->json($faqs);
    }
}
