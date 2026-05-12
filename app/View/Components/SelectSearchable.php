<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectSearchable extends Component
{
    public $label;
    public $name;
    public $id;
    public $options;
    public $placeholder;
    public $searchPlaceholder;
    public $disabled;

    public function __construct(
        $label,
        $name,
        $id,
        $options,
        $placeholder = '-- Pilih --',
        $searchPlaceholder = 'Cari...',
        $disabled
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->id = $id;
        $this->options = $options;
        $this->placeholder = $placeholder;
        $this->searchPlaceholder = $searchPlaceholder;
        $this->disabled = $disabled;
    }

    public function render()
    {
        return view('components.select-searchable');
    }
}