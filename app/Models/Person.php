<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
    ];

    /**
     * Get all transactions for this person.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Calculate the balance for this person.
     * Balance = total 'get' - total 'give'
     */
    public function balance(): float
    {
        $totalGet = $this->transactions()
            ->whereIn('direction', ['get', 'both'])
            ->sum('amount');

        $totalGive = $this->transactions()
            ->whereIn('direction', ['give', 'both'])
            ->sum('amount');

        return $totalGet - $totalGive;
    }

    /**
     * Get overdue transactions (where due_date < today and direction is 'get').
     */
    public function overdueTransactions()
    {
        return $this->transactions()
            ->where('direction', 'get')
            ->where('due_date', '<', now())
            ->whereNotNull('due_date')
            ->get();
    }
}
