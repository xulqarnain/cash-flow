<?php

namespace App\Livewire;

use App\Models\Person;
use App\Models\Transaction;
use Livewire\Component;

class Dashboard extends Component
{
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

        $upcomingDues = Transaction::where('direction', 'get')
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->with('person')
            ->take(5)
            ->get();

        $overdueDues = Transaction::overdue()
            ->with('person')
            ->orderBy('due_date')
            ->take(5)
            ->get();

        $recentPeople = Person::latest()
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'totalCashIn' => $totalCashIn,
            'totalCashOut' => $totalCashOut,
            'totalOnlineIn' => $totalOnlineIn,
            'totalOnlineOut' => $totalOnlineOut,
            'upcomingDues' => $upcomingDues,
            'overdueDues' => $overdueDues,
            'recentPeople' => $recentPeople,
        ])->layout('layouts.app');
    }
}
