<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    // add testimonial
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ], [
            'rating.required' => 'يجب اختيار التقييم',
            'rating.min' => 'التقييم يجب أن يكون بين 1 و 5',
            'rating.max' => 'التقييم يجب أن يكون بين 1 و 5',
            'comment.required' => 'يجب إدخال التعليق',
            'comment.max' => 'التعليق لا يجب أن يتجاوز 500 حرف',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Testimonial::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('index')
            ->with('success', 'تم إضافة تعليقك بنجاح! شكراً لك.');
    }

    // delete comment
    public function delete($id){
        $testimonial = Testimonial::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $testimonial->delete();

        return redirect()->route('index')
            ->with('success', 'تم حذف التعليق بنجاح.');
    }
}
