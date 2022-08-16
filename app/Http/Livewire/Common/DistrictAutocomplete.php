<?php

namespace App\Http\Livewire\Common;

use App\Models\District;

class DistrictAutocomplete extends Autocomplete
{
    protected $listeners = ['valueSelected'];

    public function valueSelected(District $distrito)
    {
        $this->emitUp('districtSelected', $distrito);
    }

    public function query()
    {
        return District::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name');
    }
}
