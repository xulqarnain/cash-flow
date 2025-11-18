<?php

namespace App\Livewire;

use App\Models\Person;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class PersonLedger extends Component
{
    use WithPagination;

    public $personId;
    public $person;

    // Transaction form fields
    public $amount = '';
    public $direction = 'get';
    public $method = 'cash';
    public $description = '';
    public $due_date = '';
    public $transactionId = null;
    public $isEditing = false;
    public $showForm = false;

    // Filters
    public $filterDirection = '';
    public $filterMethod = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'direction' => 'required|in:give,get,both',
        'method' => 'required|in:cash,online',
        'description' => 'nullable|string|max:500',
        'due_date' => 'nullable|date|after_or_equal:today',
    ];

    public function mount($personId)
    {
        $this->personId = $personId;
        $this->person = Person::findOrFail($personId);
    }

    public function updatingFilterDirection()
    {
        $this->resetPage();
    }

    public function updatingFilterMethod()
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
        $this->amount = '';
        $this->direction = 'get';
        $this->method = 'cash';
        $this->description = '';
        $this->due_date = '';
        $this->transactionId = null;
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'person_id' => $this->personId,
            'amount' => $this->amount,
            'direction' => $this->direction,
            'method' => $this->method,
            'description' => $this->description,
            'due_date' => $this->due_date ?: null,
            'saved_at' => now(),
        ];

        if ($this->isEditing && $this->transactionId) {
            Transaction::findOrFail($this->transactionId)->update($data);
            session()->flash('message', 'Transaction updated successfully.');
        } else {
            Transaction::create($data);
            session()->flash('message', 'Transaction added successfully.');
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $this->transactionId = $transaction->id;
        $this->amount = $transaction->amount;
        $this->direction = $transaction->direction;
        $this->method = $transaction->method;
        $this->description = $transaction->description;
        $this->due_date = $transaction->due_date?->format('Y-m-d');
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function delete($id)
    {
        Transaction::findOrFail($id)->delete();
        session()->flash('message', 'Transaction deleted successfully.');
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function clearFilters()
    {
        $this->filterDirection = '';
        $this->filterMethod = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->resetPage();
    }

    public function render()
    {
        $transactions = Transaction::where('person_id', $this->personId)
            ->when($this->filterDirection, function ($query) {
                $query->where('direction', $this->filterDirection);
            })
            ->when($this->filterMethod, function ($query) {
                $query->where('method', $this->filterMethod);
            })
            ->when($this->filterDateFrom, function ($query) {
                $query->whereDate('saved_at', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function ($query) {
                $query->whereDate('saved_at', '<=', $this->filterDateTo);
            })
            ->latest('saved_at')
            ->paginate(15);

        $balance = $this->person->balance();

        return view('livewire.person-ledger', [
            'transactions' => $transactions,
            'balance' => $balance,
        ])->layout('layouts.app');
    }
}
