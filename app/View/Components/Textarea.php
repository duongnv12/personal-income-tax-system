<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id = '',
        public string $name,
        public ?string $value = '', // THAY ĐỔI DÒNG NÀY: THÊM DẤU ? ĐỂ CHO PHÉP NULL
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.textarea');
    }
}