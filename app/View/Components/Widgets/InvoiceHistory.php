<?php

namespace App\View\Components\Widgets;

use App\Abstracts\View\Component;

class InvoiceHistory extends Component
{
    public $contact;

    public $model;

    public $invoices;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $model = false, $invoices = false, $contact = false
    ) {
        $this->contact = ! empty($contact) ? $contact : user()->contact;
        $this->model = $model;
        $this->invoices = $this->getPayment($model, $invoices);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.widgets.invoice_history');
    }

    protected function getPayment($model, $invoices)
    {
        if (! empty($model)) {
            return $model;
        }

        if (! empty($invoices)) {
            return $invoices;
        }

        return $this->contact->invoices()->orderBy('created_at', 'desc')->limit(3)->get();
    }
}
