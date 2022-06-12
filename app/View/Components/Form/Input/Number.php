<?php

namespace App\View\Components\Form\Input;

use App\Abstracts\View\Components\Form;

class Number extends Form
{
    public $type = 'number';

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.input.number');
    }
}
