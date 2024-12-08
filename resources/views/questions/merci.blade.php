
<x-app-layout>
    <div class="text-center mt-20">
        <h1 class="text-3xl font-semibold">Merci !</h1>
        <p class="text-lg mt-4">Vos réponses ont été enregistrées avec succès.</p>
        
    </div>
    <script>
        function toggleInfoField(input, questionId) {
            const infoField = document.getElementById(`info_${questionId}`);
            if (input.value === 'Oui' && input.checked) {
                infoField.classList.remove('hidden'); // Afficher le champ
            } else if (!input.checked) {
                infoField.classList.add('hidden'); // Cacher si décoché
            }
        }
    </script>
</x-app-layout>

