<x-app-layout>

    <h1>Liste des Rendez-vous</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID Patient</th>
                <th>Nom du Patient</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rdvs as $rdv)
                <tr>
                    <td>{{ $rdv->patient->id }}</td>
                    <td>{{ $rdv->patient->nom }} {{ $rdv->patient->prenom }}</td>
                    <td>
                        <a href="{{ route('visites.create', ['id_patient' => $rdv->patient->id, 'id_rdv' => $rdv->id]) }}" class="btn btn-primary">Créer Visite</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>