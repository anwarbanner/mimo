<title>Visites</title>
<x-app-layout>
    <h1 class="text-lg sm:text-xl md:text-2xl lg:text-5xl text-center text-blue-700 mb-4 lg:mb-8">
        Rendez-vous de {{ now()->toFormattedDateString() }}
    </h1>

    @if($rdvs->isEmpty())
        <p class="text-center text-gray-600 text-sm sm:text-base">Pas de rendez-vous aujourd'hui.</p>
    @else
        <div class="max-w-4xl mx-auto p-4 sm:p-6 bg-white shadow-lg rounded-lg my-6">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-xs sm:text-sm md:text-base border-collapse">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left">Motif</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left">Patient</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left">Date</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left">Etat</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($rdvs as $rdv)
                            <tr class="hover:bg-gray-100">
                                <td class="px-2 py-2 sm:px-4 sm:py-3">{{ $rdv->title }}</td>
                                <td class="px-2 py-2 sm:px-4 sm:py-3">
                                    {{ $rdv->patient->id }} - {{ $rdv->patient->nom }} {{ $rdv->patient->prenom }}
                                </td>
                                <td class="px-2 py-2 sm:px-4 sm:py-3">{{ $rdv->start->format('d-m-Y H:i') }}</td>
                                <td class="px-2 py-2 sm:px-4 sm:py-3">
                                    @if($rdv->visite_exists)
                                        <span class="text-green-600 font-semibold">Visite déjà faite</span>
                                    @else
                                        <span class="text-yellow-500 font-semibold">En attente</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2 sm:px-4 sm:py-3">
                                    @if($rdv->visite_exists)
                                        @php
                                            $visite = \App\Models\Visite::where('id_rdv', $rdv->id)->first();
                                        @endphp
                                        <a href="{{ route('visites.show', ['visite' => $visite->id]) }}"
                                           class="inline-block px-3 py-1 sm:px-4 sm:py-2 bg-green-500 text-white rounded-lg text-xs sm:text-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400">
                                            Voir Visite
                                        </a>
                                    @else
                                        <a href="{{ route('visites.create', ['id_rdv' => $rdv->id]) }}"
                                           class="inline-block px-3 py-1 sm:px-4 sm:py-2 bg-blue-500 text-white rounded-lg text-xs sm:text-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            Créer Visite
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>
