<div>
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg shadow-sm animate-fade-in">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('message') }}
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600">Track your transactions at a glance</p>
    </div>

    <!-- Summary Stats Grid - Mobile First -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <!-- Cash In -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-green-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">💵</span>
                <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full">Cash</span>
            </div>
            <p class="text-xs text-gray-600 mb-1">Cash In</p>
            <p class="text-lg sm:text-xl font-bold text-gray-900">₹{{ number_format($totalCashIn, 0) }}</p>
        </div>

        <!-- Cash Out -->
        <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-red-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">💸</span>
                <span class="text-xs font-medium text-red-600 bg-red-100 px-2 py-1 rounded-full">Cash</span>
            </div>
            <p class="text-xs text-gray-600 mb-1">Cash Out</p>
            <p class="text-lg sm:text-xl font-bold text-gray-900">₹{{ number_format($totalCashOut, 0) }}</p>
        </div>

        <!-- Online In -->
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-blue-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">💳</span>
                <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Online</span>
            </div>
            <p class="text-xs text-gray-600 mb-1">Online In</p>
            <p class="text-lg sm:text-xl font-bold text-gray-900">₹{{ number_format($totalOnlineIn, 0) }}</p>
        </div>

        <!-- Online Out -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-purple-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">🌐</span>
                <span class="text-xs font-medium text-purple-600 bg-purple-100 px-2 py-1 rounded-full">Online</span>
            </div>
            <p class="text-xs text-gray-600 mb-1">Online Out</p>
            <p class="text-lg sm:text-xl font-bold text-gray-900">₹{{ number_format($totalOnlineOut, 0) }}</p>
        </div>
    </div>

    <!-- People Cards with Inline Transactions -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">People & Transactions</h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Select a person to view their transactions</p>
                </div>
                <a href="/people" wire:navigate class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center space-x-1">
                    <span class="hidden sm:inline">Manage</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($people as $person)
                <div class="hover:bg-gray-50 transition-colors duration-150">
                    <!-- Person Header -->
                    <button
                        wire:click="selectPerson({{ $person->id }})"
                        wire:loading.attr="disabled"
                        class="w-full px-4 sm:px-6 py-4 text-left flex items-center justify-between"
                    >
                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-base sm:text-lg font-bold">{{ substr($person->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm sm:text-base font-semibold text-gray-900 truncate">{{ $person->name }}</p>
                                <p class="text-xs text-gray-500 truncate">
                                    @if($person->phone || $person->email)
                                        {{ $person->phone ?? $person->email }}
                                    @else
                                        No contact info
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 flex-shrink-0 ml-3">
                            @php
                                $personBalance = $person->balance();
                            @endphp
                            <div class="text-right">
                                <p class="text-sm sm:text-base font-bold {{ $personBalance > 0 ? 'text-green-600' : ($personBalance < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                    ₹{{ number_format(abs($personBalance), 0) }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    @if($personBalance > 0)
                                        To receive
                                    @elseif($personBalance < 0)
                                        To pay
                                    @else
                                        Settled
                                    @endif
                                </p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200 {{ $selectedPersonId == $person->id ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>

                    <!-- Transactions Panel -->
                    @if($selectedPersonId == $person->id && $showTransactions)
                        <div class="bg-gray-50 px-4 sm:px-6 py-4 border-t border-gray-200" wire:loading.class="opacity-50">
                            <!-- Person Balance Header -->
                            <div class="mb-4 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Current Balance</p>
                                        <p class="text-2xl font-bold {{ $balance > 0 ? 'text-green-600' : ($balance < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                            ₹{{ number_format(abs($balance), 2) }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            @if($balance > 0)
                                                You will receive
                                            @elseif($balance < 0)
                                                You will pay
                                            @else
                                                All settled
                                            @endif
                                        </p>
                                    </div>
                                    <button wire:click="openForm" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg transition-all duration-200 flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        <span class="hidden sm:inline">Add Transaction</span>
                                        <span class="sm:hidden">Add</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Filters -->
                            <div class="mb-4 flex flex-wrap gap-2">
                                <select wire:model.live="filterDirection" class="text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">All Directions</option>
                                    <option value="get">Receive</option>
                                    <option value="give">Pay</option>
                                    <option value="both">Both</option>
                                </select>
                                <select wire:model.live="filterMethod" class="text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">All Methods</option>
                                    <option value="cash">Cash</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>

                            <!-- Transactions List -->
                            @if($transactions && $transactions->count() > 0)
                                <div class="space-y-2 mb-4">
                                    @foreach($transactions as $transaction)
                                        <div class="bg-white rounded-lg p-3 sm:p-4 shadow-sm border border-gray-200 {{ $transaction->isOverdue() ? 'border-l-4 border-l-red-500' : '' }}">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                                            {{ $transaction->direction === 'get' ? 'bg-green-100 text-green-700' : '' }}
                                                            {{ $transaction->direction === 'give' ? 'bg-red-100 text-red-700' : '' }}
                                                            {{ $transaction->direction === 'both' ? 'bg-blue-100 text-blue-700' : '' }}">
                                                            {{ ucfirst($transaction->direction) }}
                                                        </span>
                                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                                            {{ $transaction->method === 'cash' ? 'bg-yellow-100 text-yellow-700' : 'bg-purple-100 text-purple-700' }}">
                                                            {{ ucfirst($transaction->method) }}
                                                        </span>
                                                        @if($transaction->isOverdue())
                                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-700">Overdue</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm text-gray-700 mb-1">{{ $transaction->description ?: 'No description' }}</p>
                                                    <p class="text-xs text-gray-500">{{ $transaction->saved_at->format('M d, Y • h:i A') }}</p>
                                                    @if($transaction->due_date)
                                                        <p class="text-xs {{ $transaction->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }} mt-1">
                                                            Due: {{ $transaction->due_date->format('M d, Y') }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                                    <p class="text-lg font-bold text-gray-900">₹{{ number_format($transaction->amount, 2) }}</p>
                                                    <div class="flex gap-2">
                                                        <button wire:click="edit({{ $transaction->id }})" class="text-xs text-yellow-600 hover:text-yellow-800 font-medium">Edit</button>
                                                        <button wire:click="delete({{ $transaction->id }})" onclick="return confirm('Delete this transaction?')" class="text-xs text-red-600 hover:text-red-800 font-medium">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                <div class="mt-4">
                                    {{ $transactions->links() }}
                                </div>
                            @else
                                <div class="bg-white rounded-lg p-8 text-center">
                                    <p class="text-gray-500 mb-3">No transactions yet</p>
                                    <button wire:click="openForm" class="text-blue-600 hover:text-blue-700 font-medium text-sm">Add First Transaction</button>
                                </div>
                            @endif

                            <div class="mt-4">
                                <button wire:click="closeTransactions" class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                                    ← Close Transactions
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="px-4 sm:px-6 py-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-600 mb-4">No people added yet</p>
                    <a href="/people" wire:navigate class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Your First Person
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Transaction Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-end sm:items-center justify-center z-50 p-0 sm:p-4" wire:click.self="cancel">
            <div class="bg-white rounded-t-2xl sm:rounded-2xl w-full sm:max-w-lg sm:mx-4 max-h-[90vh] overflow-y-auto animate-slide-up">
                <div class="sticky top-0 bg-white px-4 sm:px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">{{ $isEditing ? 'Edit Transaction' : 'Add Transaction' }}</h3>
                    <button wire:click="cancel" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="px-4 sm:px-6 py-4 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                                <input type="number" step="0.01" wire:model="amount" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                                @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                                <input type="date" wire:model="due_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('due_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Direction *</label>
                                <select wire:model="direction" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="get">I will receive</option>
                                    <option value="give">I will pay</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Method *</label>
                                <select wire:model="method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="cash">Cash</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Reason or note..."></textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="sticky bottom-0 bg-gray-50 px-4 sm:px-6 py-4 border-t border-gray-200 flex gap-3">
                        <button type="button" wire:click="cancel" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg text-sm font-medium shadow-md hover:shadow-lg transition-all duration-200">
                            {{ $isEditing ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Loading Overlay -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-4 shadow-xl">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
</div>
