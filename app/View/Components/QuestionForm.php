<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class QuestionForm extends Component
{
    
    public $currentQuestion;
    public $choices;

    public function __construct($currentQuestion, $choices)
    {
        $this->currentQuestion = $currentQuestion;
        $this->choices = $choices;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.question-form');
    }
}
