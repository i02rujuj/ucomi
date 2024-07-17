<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImgTooltip extends Component
{
    public function __construct(
        public string $text = '',
        public string $image = ''
    ){}

    public function render()
    {
        return view('components.imgTooltip');
    }
}