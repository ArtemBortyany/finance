<?php

namespace App\View\Components\Table;

use App\Abstracts\View\Component;

class Th extends Component
{
    public $class;

    public $override;

    public $kind;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $class = '', string $override = '', string $kind = ''
    ) {
        $this->override = $this->getOverride($override);

        $this->kind = $kind;
        $this->class = $this->getClass($class);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.table.th');
    }

    protected function getOverride($override)
    {
        return explode(',', $override);
    }

    protected function getClass($class)
    {
        if (in_array('class', $this->override)) {
            return $class;
        }

        $default = 'py-3 text-xs font-medium text-black tracking-wider';

        switch ($this->kind) {
            case 'amount':
                $default = $class . ' ltr:pl-6 rtl:pr-6 ltr:text-right rtl:text-left ' . $default;
                break;
            case 'right':
                $default = $class . ' ltr:pl-6 rtl:pr-6 ltr:text-right rtl:text-left' . $default;
                break;
            default:
                $default = $class . ' ltr:pr-6 rtl:pl-6 ltr:text-left rtl:text-right ' . $default;
        }

        return $default;
    }
}
