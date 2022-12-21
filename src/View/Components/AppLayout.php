<?php

namespace Vesaka\Core\View\Components;

class AppLayout extends BaseComponent
{
    public function setup() {
        $this->auth = auth()->user();
    }
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('admin::layouts.admin', [
            'items' => config('navbar.menu')
        ]);
    }
}
