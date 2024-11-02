<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth/register');
    }

    public function registerSave(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ])->validate();
  
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
  
        return redirect()->route('login');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function loginAction(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required'
        ])->validate();
  
        if (!Auth::attempt($request->only('name', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'name' => trans('auth.failed')
            ]);
        }
  
        $request->session()->regenerate();
  
        return redirect()->route('dashboard');
    }
  
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
  
        $request->session()->invalidate();
  
        return redirect('/');
    }
 
    public function profile()
    {
        return view('profile');
    }


    
    public function updateProfile(Request $request)
    {
        // Validation rules
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->user()->id, // Validate email update
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|confirmed|min:8', 
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
             
        ]);
    
        $user = auth()->user();
        $changesDetected = false; // Initialize the flag to track changes
    
        // Check if any fields have changed
        if ($user->name != $request->name) {
            $user->name = $request->name;
            $changesDetected = true;
        }
        
        if ($user->email != $request->email) {
            $user->email = $request->email;
            $changesDetected = true;
        }
    
        if ($user->phone != $request->phone) {
            $user->phone = $request->phone;
            $changesDetected = true;
        }
    
        if ($user->address != $request->address) {
            $user->address = $request->address;
            $changesDetected = true;
        }
    
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $changesDetected = true;
        }
    
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $profileImageName = time() . '.' . $request->profile_image->extension();
            $request->profile_image->move(public_path('images/profile'), $profileImageName);
            $user->profile_image = $profileImageName;
            $changesDetected = true;
        }
    
        
    
        // Save the updated user data if any changes were detected
        if ($changesDetected) {
            $user->save();
            return redirect()->back()->with('success', 'Informations modifiées avec succès.'); // Success message
        }
    
        // If no changes were detected
        return redirect()->back()->with('info', 'Aucune modification détectée.'); // No changes detected message
    }
    
    

}
