<?php

namespace App\View\Components;

use App\Abstracts\View\Component;
use Illuminate\Support\Str;

class Link extends Component
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
        return view('components.link');
    }

    protected function getOverride($override)
    {
        return explode(',', $override);
    }

    protected function getClass($class)
    {
        $default = 'px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6';

        switch ($this->kind) {
            case 'primary':
                $default .= ' bg-green hover:bg-green-700 text-white disabled:bg-green-100';
                break;
            case 'secondary':
                $default .= ' bg-purple hover:bg-purple-700 text-white disabled:bg-purple-100';
                break;
            default:
                $default .= ' bg-gray-100 hover:bg-gray-200 disabled:bg-gray-50';
        }

        if (in_array('class', $this->override)) {
            return $class;
        }

        return $default;
    }
}
