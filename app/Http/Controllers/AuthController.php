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
            'email' => 'required|email|max:255|unique:users,email,' . auth()->user()->id,
            'phone' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'cie' => 'nullable|string|max:255', // Validate CIE
            'fiscal_id' => 'nullable|string|max:255', // Validate Identifiant Fiscal
            'register_number' => 'nullable|string|max:255', // Validate Numéro de Registre
            'password' => 'nullable|confirmed|min:8',
        ]);
    
        $user = auth()->user();
        $changesDetected = false;
    
        // Check for changes
        if ($user->cie != $request->cie) {
            $user->cie = $request->cie;
            $changesDetected = true;
        }
    
        if ($user->fiscal_id != $request->fiscal_id) {
            $user->fiscal_id = $request->fiscal_id;
            $changesDetected = true;
        }
    
        if ($user->register_number != $request->register_number) {
            $user->register_number = $request->register_number;
            $changesDetected = true;
        }
        if ($user->adresse != $request->adresse) {
            $user->adresse = $request->adresse;
            $changesDetected = true;
        }
        if ($user->phone != $request->phone) {
            $user->phone = $request->phone;
            $changesDetected = true;
        }
    
        // (Other fields as before)
    
        // Save changes if detected
        if ($changesDetected) {
            $user->save();
            return redirect()->back()->with('success', 'Informations modifiées avec succès.');
        }
    
        return redirect()->back()->with('info', 'Aucune modification détectée.');
    }
    
    
    

}
