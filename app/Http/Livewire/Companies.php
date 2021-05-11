<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;

class Companies extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.Company.index',[
            'companies' => Company::query()->orderBy('id')->paginate(25)
        ]);
    }
}
