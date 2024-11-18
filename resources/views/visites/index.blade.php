<x-app-layout>

   
    <h1>Rendez-vous de {{ now()->toFormattedDateString() }}</h1>

    @if($rdvs->isEmpty())
        <p>pas de rendez-vous aujourd'hui.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>motif </th>
                    <th>Patient Name</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rdvs as $rdv)
                    <tr>
                        <td>{{ $rdv->title }}</td>
                        <td>{{ $rdv->patient->id }}-{{ $rdv->patient->nom }} {{ $rdv->patient->prenom }}</td>
                        <td>{{ $rdv->start->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('visites.create', ['id_rdv' => $rdv->id]) }}" class="btn btn-primary">
                                Create Visite
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</x-app-layout>