<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class AuthController extends Controller
{
    function register()
    {
        return view('auth.register');
    }

    function login()
    {
        return view('auth.login');
    }

    public function storeLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role->name === 'admin') {
                return redirect()->route('admin_dashborad');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Credentials do not match our records.',
        ])->onlyInput('email');
    }


    function  storeRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:admin,user',
        ]);

        $role = Role::where('name', $validated['role'])->firstOrFail();
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role->id,
        ]);
        Auth::login($user);

        if ($user->role->name === 'admin') {
            return redirect()->route('admin_dashborad');
        }

        return redirect()->route('dashboard');
    }

    function index()
    {
        $user = Auth::user();
        return view('dashboard', compact('user'));
    }

    function admin(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->role || $user->role->name !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        if ($request->ajax()) {
            $students = Student::select(['id', 'name', 'email', 'age'])->get();

            return datatables()->of($students)
                ->addColumn('actions', function ($student) {
                    return '
                     <a href="' . route('student.marks', $student->id) . '" class="btn btn-sm btn-info">View</a>
                        <button class="btn btn-sm btn-primary edit-btn" data-id="' . $student->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $student->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin_dashborad', compact('user'));
    }

    public function getStudent($id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    public function updateStudent(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'age' => 'required|integer|min:1',
        ]);

        $student = Student::findOrFail($id);
        $student->update($request->only(['name', 'email', 'age']));

        return response()->json(['success' => true]);
    }


    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json(['success' => true]);
    }
}
