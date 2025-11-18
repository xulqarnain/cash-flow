<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'person_id',
        'amount',
        'direction',
        'method',
        'description',
        'due_date',
        'saved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'saved_at' => 'datetime',
    ];

    /**
     * Get the person that owns the transaction.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Check if the transaction is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->direction === 'get'
            && $this->due_date
            && $this->due_date->isPast();
    }

    /**
     * Scope to filter transactions by direction.
     */
    public function scopeDirection($query, string $direction)
    {
        return $query->where('direction', $direction);
    }

    /**
     * Scope to filter transactions by method.
     */
    public function scopeMethod($query, string $method)
    {
        return $query->where('method', $method);
    }

    /**
     * Scope to get overdue transactions.
     */
    public function scopeOverdue($query)
    {
        return $query->where('direction', 'get')
            ->where('due_date', '<', now())
            ->whereNotNull('due_date');
    }
}
