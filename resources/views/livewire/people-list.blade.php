<div class="space-y-4">
    <!-- Header Card -->
    <div class="bg-white rounded-[28px] shadow-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <button onclick="window.history.back()" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <h1 class="text-3xl font-bold text-gray-900 flex-1 text-center -mr-10">History<br/>Transactions</h1>
        </div>

        <!-- Month Dropdown -->
        <div class="relative">
            <select class="w-full px-4 py-3 bg-gradient-to-br from-blue-50 to-blue-100/50 border-0 rounded-2xl text-gray-900 font-semibold focus:ring-2 focus:ring-[#4A9FD8] appearance-none cursor-pointer">
                <option>January</option>
                <option selected>February</option>
                <option>March</option>
                <option>April</option>
                <option>May</option>
                <option>June</option>
            </select>
            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none flex items-center space-x-2">
                <div class="w-5 h-5 rounded-full bg-[#4A9FD8] flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="space-y-3">
        @php
            $allTransactions = \App\Models\Transaction::with('person')
                ->latest('saved_at')
                ->get()
                ->groupBy(function($transaction) {
                    return $transaction->saved_at->format('d M Y');
                });

            $iconColors = ['orange', 'green', 'blue', 'purple', 'red', 'yellow', 'pink', 'indigo'];
        @endphp

        @forelse($allTransactions as $date => $transactions)
            <!-- Date Header -->
            <div class="flex items-center space-x-2 px-1">
                <p class="text-sm font-bold text-gray-700">{{ $date }}</p>
            </div>

            <!-- Transactions for this date -->
            @foreach($transactions as $index => $transaction)
                @php
                    $color = $iconColors[$index % count($iconColors)];
                    $colorMap = [
                        'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'ring' => 'ring-orange-200'],
                        'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'ring' => 'ring-green-200'],
                        'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'ring' => 'ring-blue-200'],
                        'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'ring' => 'ring-purple-200'],
                        'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'ring' => 'ring-red-200'],
                        'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'ring' => 'ring-yellow-200'],
                        'pink' => ['bg' => 'bg-pink-100', 'text' => 'text-pink-600', 'ring' => 'ring-pink-200'],
                        'indigo' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-600', 'ring' => 'ring-indigo-200'],
                    ];
                    $colors = $colorMap[$color];
                @endphp

                <div class="bg-white rounded-2xl shadow-md p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 flex-1">
                            <!-- Colored Icon -->
                            <div class="w-12 h-12 rounded-full {{ $colors['bg'] }} ring-4 {{ $colors['ring'] }} flex items-center justify-center flex-shrink-0">
                                <span class="text-lg font-bold {{ $colors['text'] }}">
                                    {{ substr($transaction->person->name ?? 'T', 0, 1) }}
                                </span>
                            </div>

                            <!-- Details -->
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 text-base">{{ $transaction->person->name ?? 'Transaction' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $transaction->description ?? ucfirst($transaction->method) . ' payment' }}</p>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="text-right ml-3">
                            <p class="font-bold text-base {{ $transaction->direction === 'get' ? 'text-green-600' : 'text-red-500' }}">
                                {{ $transaction->direction === 'get' ? '+' : '-' }} $ {{ number_format($transaction->amount, 0) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        @empty
            <div class="bg-white rounded-2xl shadow-md p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-gray-600 font-medium mb-2">No transactions yet</p>
                <p class="text-gray-400 text-sm">Start adding transactions to track your cash flow</p>
            </div>
        @endforelse
    </div>

    <!-- Add Person Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center z-50 p-0 sm:p-4" wire:click.self="cancel">
            <div class="bg-white rounded-t-[28px] sm:rounded-[28px] w-full sm:max-w-lg sm:mx-4 max-h-[90vh] overflow-y-auto shadow-2xl">
                <div class="sticky top-0 bg-white px-6 py-5 border-b border-gray-100 flex items-center justify-between rounded-t-[28px]">
                    <h3 class="text-xl font-bold text-gray-900">{{ $isEditing ? 'Edit Person' : 'Add New Person' }}</h3>
                    <button wire:click="cancel" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="px-6 py-6 space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Name *</label>
                            <input
                                type="text"
                                wire:model="name"
                                class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#4A9FD8] focus:border-transparent font-medium"
                                placeholder="Enter person's name"
                            >
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                            <input
                                type="text"
                                wire:model="phone"
                                class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#4A9FD8] focus:border-transparent font-medium"
                                placeholder="Enter phone number"
                            >
                            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input
                                type="email"
                                wire:model="email"
                                class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#4A9FD8] focus:border-transparent font-medium"
                                placeholder="Enter email address"
                            >
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="sticky bottom-0 bg-white px-6 py-5 border-t border-gray-100 flex gap-3 rounded-b-[28px]">
                        <button
                            type="button"
                            wire:click="cancel"
                            class="flex-1 px-6 py-4 border-2 border-gray-200 rounded-2xl text-base font-semibold text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="flex-1 px-6 py-4 bg-[#4A9FD8] hover:bg-[#3A8FC8] text-white rounded-2xl text-base font-semibold shadow-lg transition-all"
                        >
                            {{ $isEditing ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="fixed top-6 left-1/2 -translate-x-1/2 bg-white rounded-2xl shadow-xl px-6 py-4 z-50 border-l-4 border-green-500">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-gray-900 font-medium">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <!-- Loading Overlay -->
    <div wire:loading class="fixed inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl p-6 shadow-2xl">
            <svg class="animate-spin h-10 w-10 text-[#4A9FD8]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
</div>
