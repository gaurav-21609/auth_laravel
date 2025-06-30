<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Mark;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use App\Models\User;

class MarkController extends Controller
{
    public function index($id)
    {
        $user = Auth::user();
        if (!$user || $user->role->name !== 'admin') {
            abort(403);
        }

        $student = Student::with('marks')->findOrFail($id);
        return view('student_marks', compact('student', 'user'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'score' => 'required|integer|min:0|max:100',
            'grade' => 'nullable|string|max:5',
        ]);

        Mark::create([
            'student_id' => $id,
            'subject' => $request->subject,
            'score' => $request->score,
            'grade' => $request->grade,
        ]);

        return redirect()->route('student.marks', $id)->with('success', 'Mark added.');
    }

    public function destroy($id)
    {
        Mark::destroy($id);
        return back()->with('success', 'Mark deleted.');
    }
}
