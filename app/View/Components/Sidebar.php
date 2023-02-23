<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * The application name.
     * 
     * @var string
     */
    public $applicationName;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($applicationName)
    {
        $this->applicationName = $applicationName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (auth()->user()->role === 'Admin') {
            return view('components.admin-sidebar');
        }

        return view('components.operator-sidebar');
    }
}
