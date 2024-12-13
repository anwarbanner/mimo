<x-app-layout>
    <x-slot name="title">Profile</x-slot> <!-- Set the page title if your layout includes it -->

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
                            <input type="text" name="adresse" class="form-control" value="{{ auth()->user()->adresse }}" placeholder="Adresse">
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
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmer le mot de passe">
                        </div>
                    </div>

                    <hr class="border-2">

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="labels">CIE</label>
                            <input type="text" name="cie" class="form-control" placeholder="CIE" value="{{ auth()->user()->cie }}">
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Identifiant Fiscal</label>
                            <input type="text" name="fiscal_id" class="form-control" placeholder="Identifiant Fiscal" value="{{ auth()->user()->fiscal_id }}">
                        </div>
                    </div>
                    <hr class="border-2">
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="labels">Numéro de Registre</label>
                            <input type="text" name="register_number" class="form-control" placeholder="Numéro de Registre" value="{{ auth()->user()->register_number }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="labels">TVA</label>
                            <input type="text" name="tva" class="form-control" placeholder="TVA" value="{{ auth()->user()->tva }}">
                        </div>
                    </div>
                    </div>
                    

                    <hr class="border-2">
                    
                    

                    <div class="mt-5 text-center">
                        <button id="btn" class="btn btn-success profile-button" type="submit">Modifier</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
