<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class News extends Component
{
    use WithPagination, WithFileUploads;

    public $formData = [
        'title' => '',
        'url' => '',
        'language' => '',
        'summary' => '',
        'image' => '',
        'companies' => [],
    ];
    public $editMode = false;
    public $selectedId;

    public function render()
    {
        return view('livewire.News.index' , [
            'allNews' => \App\Models\News::query()->orderBy('id')->paginate(25),
            'companies' => Company::query()->select('id','name')->get()
        ]);
    }

    public function validations()
    {
        $rules = [
            'title' => ['required','string','max:255', 'unique:news'],
            'url' => ['required','string','max:255'],
            'language' => ['required','string'],
            'summary' => ['required','string'],
            'image' => ['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'companies' => ['required','array'],
        ];
        if($this->editMode)
        {
            $rules['title'] = ['required','string','max:255', 'unique:news,title,'.$this->selectedId];
        }
        if($this->editMode && !isset($this->formData['image']))
        {
            $rules['image'] = ['sometimes','image','mimes:jpeg,png,jpg,gif,svg','max:2048'];
        }

        Validator::make(
            $this->formData,
            $rules
        )->validate();
    }

    public function resetForm()
    {
        $this->formData = [
            'title' => '',
            'url' => '',
            'language' => '',
            'summary' => '',
            'image' => '',
            'companies' => [],
        ];
        $this->resetValidation();
        $this->selectedId = null;
    }

    public function create()
    {
        $this->editMode = true;
        $this->resetForm();
    }

    /**
     * @throws Exception
     */
    public function store()
    {
        $this->validations();
        try {
            DB::beginTransaction();
            $filePath = $this->formData['image']->store('public/photos');
            $this->formData['image'] = str_replace('public/','',$filePath);

            $news = \App\Models\News::query()
                ->create($this->formData);
            $news->companies()->attach($this->formData['companies']);
            DB::commit();

            $this->editMode = false;
            $this->resetForm();
            session()->flash('success', 'News Added Successfully');
        }catch ( Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function edit($id)
    {
        $news = \App\Models\News::query()
            ->with(['companies' => function($q){
                $q->select('companies.id');
            }])
            ->findOrFail($id);

        $companies = $news->companies->map(function($row){
            return $row->id;
        });

        $this->selectedId = $id;
        $this->formData = [
            'title' => $news->title,
            'url' => $news->url,
            'language' => $news->language,
            'summary' => $news->summary,
            'companies' => $companies
        ];

        $this->editMode = true;
        $this->resetValidation();
    }

    /**
     * @param $id
     * @throws Exception
     */
    public function update($id)
    {
        $this->validations();
        try {
            DB::beginTransaction();
            if(isset($this->formData['image']))
            {
                $filePath = $this->formData['image']->store('public/photos');
                $this->formData['image'] = str_replace('public/','',$filePath);
            }

            $news = \App\Models\News::query()
                ->findOrFail($id);
            $news->update($this->formData);
            $news->companies()->sync($this->formData['companies']);
            DB::commit();

            $this->editMode = false;
            $this->reset();
            session()->flash('success', 'News Updated Successfully');
        }catch ( Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        \App\Models\News::query()->findOrFail($id)->delete();
        session()->flash('success', 'News Deleted Successfully');
    }
}
