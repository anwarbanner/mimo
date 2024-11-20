<x-app-layout>
    <x-slot name="title">Questionnaire</x-slot>

    <x-question-form :currentQuestion="$currentQuestion" :choices="$choices" />


    
</x-app-layout>
