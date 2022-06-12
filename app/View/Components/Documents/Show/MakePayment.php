<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;

class MakePayment extends Component
{
    public $description;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->description = trans('general.amount_due') . ': ' . '<span class="font-medium">' . money($this->document->amount, $this->document->currency_code, true) . '</span>';

        return view('components.documents.show.make-payment');
    }
}
