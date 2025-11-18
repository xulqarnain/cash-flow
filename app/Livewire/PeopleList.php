<?php

namespace App\Livewire;

use App\Models\Person;
use Livewire\Component;
use Livewire\WithPagination;

class PeopleList extends Component
{
    use WithPagination;

    public $name = '';
    public $phone = '';
    public $email = '';
    public $personId = null;
    public $isEditing = false;
    public $showForm = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->phone = '';
        $this->email = '';
        $this->personId = null;
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing && $this->personId) {
            $person = Person::findOrFail($this->personId);
            $person->update([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
            ]);
            session()->flash('message', 'Person updated successfully.');
        } else {
            Person::create([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
            ]);
            session()->flash('message', 'Person added successfully.');
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        $person = Person::findOrFail($id);
        $this->personId = $person->id;
        $this->name = $person->name;
        $this->phone = $person->phone;
        $this->email = $person->email;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function delete($id)
    {
        Person::findOrFail($id)->delete();
        session()->flash('message', 'Person deleted successfully.');
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {
        $people = Person::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.people-list', [
            'people' => $people,
        ])->layout('layouts.app');
    }
}
