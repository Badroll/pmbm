<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectSearchable extends Component
{
    public function __construct(
        public string $label,
        public string $name,
        public string $id,
        public $options,        // collection atau array
        public string $placeholder = '-- Pilih --',
        public string $searchPlaceholder = 'Cari...',
    ) {}

    public function render()
    {
        return view('components.select-searchable');
    }
}