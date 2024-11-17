
<x-app-layout>
    
        <h1>Créer une Visite</h1>
    
        <form action="{{ route('visites.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_rdv" value="{{ $id_rdv }}">
            
            <div class="form-group">
                <label for="observation">Observation</label>
                <textarea name="observation" id="observation" class="form-control" rows="4"></textarea>
            </div>
    
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
   
</x-app-layout>
     