<?php

namespace App\View\Components\Documents;

use App\Abstracts\View\Component;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\ViewComponents;

class Script extends Component
{
    use ViewComponents;

    public const OBJECT_TYPE = 'document';
    public const DEFAULT_TYPE = 'invoice';
    public const DEFAULT_PLURAL_TYPE = 'invoices';

    /** @var string */
    public $type;

    public $document;

    public $items;

    public $currencies;

    public $currency_code;

    public $taxes;

    /** @var string */
    public $alias;

    /** @var string */
    public $folder;

    /** @var string */
    public $file;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type = '', $document = false, $items = [], $currencies = [], $taxes = [],
        string $alias = '', string $folder = '', string $file = ''
    ) {
        $this->type = $type;
        $this->document = $document;
        $this->items = $items;
        $this->currencies = $this->getCurrencies($currencies);
        $this->currency_code = ($document) ? $document->currency_code : setting('default.currency');
        $this->taxes = $this->getTaxes($taxes);

        $this->alias = $this->getAlias($type, $alias);
        $this->folder = $this->getScriptFolder($type, $folder);
        $this->file = $this->getScriptFile($type, $file);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.documents.script');
    }

    protected function getCurrencies($currencies)
    {
        if (!empty($currencies)) {
            return $currencies;
        }

        return Currency::enabled()->orderBy('name')->get()->makeHidden(['id', 'company_id', 'created_at', 'updated_at', 'deleted_at']);
    }

    protected function getTaxes($taxes)
    {
        if (!empty($taxes)) {
            return $taxes;
        }

        return Tax::enabled()->orderBy('name')->get()->makeHidden(['company_id', 'created_at', 'updated_at', 'deleted_at']);
    }
}
