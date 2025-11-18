<?php

namespace App\Livewire;

use App\Models\Person;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $selectedPersonId = null;
    public $selectedPerson = null;
    public $showTransactions = false;

    // Transaction form
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

    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'direction' => 'required|in:give,get,both',
        'method' => 'required|in:cash,online',
        'description' => 'nullable|string|max:500',
        'due_date' => 'nullable|date|after_or_equal:today',
    ];

    public function selectPerson($personId)
    {
        $this->selectedPersonId = $personId;
        $this->selectedPerson = Person::find($personId);
        $this->showTransactions = true;
        $this->resetPage();
    }

    public function closeTransactions()
    {
        $this->showTransactions = false;
        $this->selectedPersonId = null;
        $this->selectedPerson = null;
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
            'person_id' => $this->selectedPersonId,
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

    public function render()
    {
        $totalCashIn = Transaction::where('method', 'cash')
            ->whereIn('direction', ['get', 'both'])
            ->sum('amount');

        $totalCashOut = Transaction::where('method', 'cash')
            ->whereIn('direction', ['give', 'both'])
            ->sum('amount');

        $totalOnlineIn = Transaction::where('method', 'online')
            ->whereIn('direction', ['get', 'both'])
            ->sum('amount');

        $totalOnlineOut = Transaction::where('method', 'online')
            ->whereIn('direction', ['give', 'both'])
            ->sum('amount');

        $people = Person::withCount('transactions')->latest()->get();

        $transactions = null;
        $balance = 0;

        if ($this->selectedPersonId) {
            $transactions = Transaction::where('person_id', $this->selectedPersonId)
                ->when($this->filterDirection, function ($query) {
                    $query->where('direction', $this->filterDirection);
                })
                ->when($this->filterMethod, function ($query) {
                    $query->where('method', $this->filterMethod);
                })
                ->latest('saved_at')
                ->paginate(10);

            $balance = $this->selectedPerson?->balance() ?? 0;
        }

        return view('livewire.dashboard', [
            'totalCashIn' => $totalCashIn,
            'totalCashOut' => $totalCashOut,
            'totalOnlineIn' => $totalOnlineIn,
            'totalOnlineOut' => $totalOnlineOut,
            'people' => $people,
            'transactions' => $transactions,
            'balance' => $balance,
        ])->layout('layouts.app');
    }
}
