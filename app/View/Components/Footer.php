<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Footer extends Component
{
    /**
     * The application copyright.
     * 
     * @var string
     */
    public $applicationCopyright;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($applicationCopyright)
    {
        $this->applicationCopyright = $applicationCopyright;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.footer');
    }
}
