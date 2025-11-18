<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\PeopleList;
use App\Livewire\PersonLedger;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/people', PeopleList::class)->name('people.index');
Route::get('/people/{personId}', PersonLedger::class)->name('people.show');
