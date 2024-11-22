<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-6">
            Créer une Visite pour {{ $rdv->patient->nom }} {{ $rdv->patient->prenom }}
        </h1>

        <form action="{{ route('visites.store') }}" method="POST" class="space-y-6">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-md">
                    <strong class="font-bold">Whoops!</strong>
                    <p class="mt-2">There were some problems with your submission:</p>
                    <ul class="mt-2 list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <input type="hidden" name="id_rdv" value="{{ $rdv->id }}">

            <!-- Observation -->
            <div>
                <label for="observation" class="block text-gray-700 font-medium mb-2">Observation</label>
                <textarea name="observation" id="observation" rows="4"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Écrivez vos observations ici..."></textarea>
            </div>

            <!-- Products Section -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Produits</label>
                <div id="products" class="space-y-4">
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4" data-index="0">
                        <select name="products[0][id]"
                            class="w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="products[0][quantity]" placeholder="Quantité"
                            class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" onclick="removeProduct(0)" class="mt-2 text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
                    </div>
                </div>
                <button type="button" onclick="addProduct()"
                    class="mt-4 text-sm text-blue-500 hover:text-blue-700 focus:outline-none">
                    + Ajouter un produit
                </button>
            </div>

            <!-- Soins Section -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Soins</label>
                <div id="soins" class="space-y-4">
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4" data-index="0">
                        <select name="soins[0][id]" class="w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($soins as $soin)
                                <option value="{{ $soin->id }}">{{ $soin->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="soins[0][quantity]" placeholder="Quantité"
                            class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <input type="timer" name="soins[0][timer]" class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Timer (MM:SS)">
                        <button type="button" onclick="startTimer(0)" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Start Timer</button>
                        <button type="button" onclick="removeSoin(0)" class="mt-2 text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
                    </div>
                </div>
                <button type="button" onclick="addSoin()"
                    class="mt-4 text-sm text-blue-500 hover:text-blue-700 focus:outline-none">
                    + Ajouter un soin
                </button>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium transition-all duration-200">
                Soumettre la Visite
            </button>
        </form>
    </div>

    <!-- Scripts -->
    <script>
        let productIndex = 1;
        let soinIndex = 1;

        function addProduct() {
            const productDiv = document.createElement('div');
            productDiv.classList.add('flex', 'flex-col', 'sm:flex-row', 'space-y-4', 'sm:space-y-0', 'sm:space-x-4');
            productDiv.setAttribute('data-index', productIndex);
            productDiv.innerHTML = `
                <select name="products[${productIndex}][id]" class="w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="products[${productIndex}][quantity]" placeholder="Quantité"
                    class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="button" onclick="removeProduct(${productIndex})" class="mt-2 text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
            `;
            document.getElementById('products').appendChild(productDiv);
            productIndex++;
        }

        function addSoin() {
            const soinDiv = document.createElement('div');
            soinDiv.classList.add('flex', 'flex-col', 'sm:flex-row', 'space-y-4', 'sm:space-y-0', 'sm:space-x-4');
            soinDiv.setAttribute('data-index', soinIndex);
            soinDiv.innerHTML = `
                <select name="soins[${soinIndex}][id]" class="w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach ($soins as $soin)
                        <option value="{{ $soin->id }}">{{ $soin->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="soins[${soinIndex}][quantity]" placeholder="Quantité"
                    class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="timer" name="soins[${soinIndex}][timer]" class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Timer (MM:SS)">
                <button type="button" onclick="startTimer(${soinIndex})" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Start Timer</button>
                <button type="button" onclick="removeSoin(${soinIndex})" class="mt-2 text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
            `;
            document.getElementById('soins').appendChild(soinDiv);
            soinIndex++;
        }

        function startTimer(index) {
            const timerInput = document.querySelector(`input[name="soins[${index}][timer]"]`);
            const timerValue = timerInput.value.split(':');
            let minutes = parseInt(timerValue[0], 10);
            let seconds = parseInt(timerValue[1], 10);

            let countdown = minutes * 60 + seconds;
            const timerPopup = document.createElement('div');
            timerPopup.classList.add('fixed', 'top-1/2', 'left-1/2', 'transform', '-translate-x-1/2', '-translate-y-1/2', 'bg-white', 'p-4', 'rounded-lg', 'shadow-lg');
            const timerDisplay = document.createElement('p');
            timerDisplay.classList.add('text-2xl', 'font-bold', 'text-black');

            const countdownInterval = setInterval(() => {
                const minutesLeft = Math.floor(countdown / 60);
                const secondsLeft = countdown % 60;
                timerDisplay.textContent = `${minutesLeft}:${secondsLeft < 10 ? '0' : ''}${secondsLeft}`;
                countdown--;

                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    timerSound.play();
                }
            }, 1000);

            timerPopup.appendChild(timerDisplay);
            document.body.appendChild(timerPopup);
        }

        function removeProduct(index) {
            const productDiv = document.querySelector(`div[data-index="${index}"]`);
            productDiv.remove();
        }

        function removeSoin(index) {
            const soinDiv = document.querySelector(`div[data-index="${index}"]`);
            soinDiv.remove();
        }
    </script>
</x-app-layout>
