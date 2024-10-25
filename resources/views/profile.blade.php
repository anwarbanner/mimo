@extends('layouts.app')

@section('title', 'Profile')

@section('contents')
    <hr />
    @if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info text-center">
        {{ session('info') }}
    </div>
    @endif

    <form method="POST" enctype="multipart/form-data" id="profile_setup_frm" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Profile</h4>
                    </div>
                    <div class="row" id="res"></div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="labels">Nom</label>
                            <input type="text" name="name" class="form-control" placeholder="nom et prenom" value="{{ auth()->user()->name }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="labels">Email</label>
                            <input type="text" name="email" class="form-control" value="{{ auth()->user()->email }}" placeholder="Email">
                        </div>
                       
                    </div>

                    <hr class="border-2">

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="labels">Numéro de téléphone</label>
                            <input type="text" name="phone" class="form-control" placeholder="numéro de téléphone" value="{{ auth()->user()->phone }}">
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Adresse</label>
                            <input type="text" name="address" class="form-control" value="{{ auth()->user()->address }}" placeholder="Adresse">
                        </div>
                    </div>

                    <hr class="border-2">

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label class="labels">Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-control" placeholder="nouveau mot de passe">
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Confirmer mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm le mot de passe">
                        </div>
                    </div>
                      
                    <hr class="border-2">

                    <div class="row mt-4">  
                        <div class="col-md-6">
                            <label class="labels">Profile Image</label>
                            <input type="file" name="logo_image" id="logo_image" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />                     
                                      @if(auth()->user()->profile_image)
                            <div class="mt-2">
                               <img src="{{ asset('images/profile/' . auth()->user()->profile_image) }}" alt="Profile Image" style="max-width: 200px; height: auto;">
                            </div>
                              @endif
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Logo Image</label>
                            <input type="file" name="logo_image" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                              @if(auth()->user()->logo_image)
                             <div class="mt-2">
                               <img src="{{ asset('images/logo/' . auth()->user()->logo_image) }}" alt="Logo Image" style="max-width: 200px; height: auto;">
                             </div>
                              @endif
                        </div>
                    </div>
                    
                    <div class="mt-5 text-center"><button id="btn" class="btn btn-success profile-button" type="submit">Modifier</button></div>
                </div>
            </div>
        </div>
    </form>
    
@endsection
