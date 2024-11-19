<x-app-layout>
    <h1 class="text-4xl lg:text-5xl font-extrabold text-center text-blue-700 mb-6 lg:mb-8">
        Rendez-vous de {{ now()->toFormattedDateString() }}
    </h1>

    @if($rdvs->isEmpty())
        <p class="text-center text-gray-600">Pas de rendez-vous aujourd'hui.</p>
    @else
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left">Motif</th>
                        <th class="px-6 py-4 text-left">Patient Name</th>
                        <th class="px-6 py-4 text-left">Date</th>
                        <th class="px-6 py-4 text-left">Etat</th>
                        <th class="px-6 py-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rdvs as $rdv)
                        <tr class="border-t border-gray-200">
                            <td class="px-6 py-4">{{ $rdv->title }}</td>
                            <td class="px-6 py-4">{{ $rdv->patient->id }}-{{ $rdv->patient->nom }} {{ $rdv->patient->prenom }}</td>
                            <td class="px-6 py-4">{{ $rdv->start->format('d-m-Y H:i') }}</td>
                            <td class="px-6 py-4">
                                @if($rdv->visite_exists)
                                    <span class="text-green-600 font-semibold">Visite déjà faite</span>
                                @else
                                    <span class="text-yellow-500 font-semibold">En attente</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($rdv->visite_exists)
                                    @php
                                        $visite = \App\Models\Visite::where('id_rdv', $rdv->id)->first();
                                    @endphp
                                    <a href="{{ route('visites.show', ['visite' => $visite->id]) }}" 
                                        class="inline-block px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400">
                                        Voir Visite
                                    </a>
                                @else
                                    <a href="{{ route('visites.create', ['id_rdv' => $rdv->id]) }}" 
                                        class="inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        Créer Visite
                                    </a>
                                @endif
                            </td>  
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-app-layout>
