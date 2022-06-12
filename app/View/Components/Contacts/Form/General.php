<?php

namespace App\View\Components\Contacts\Form;

use App\Abstracts\View\Components\Contacts\Form as Component;

class General extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.contacts.form.general');
    }
}
