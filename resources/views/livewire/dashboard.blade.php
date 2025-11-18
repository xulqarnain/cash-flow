<div class="space-y-4">
    <!-- Main Card -->
    <div class="bg-white rounded-[28px] shadow-xl p-6">
        <!-- Header with Your Money -->
        <div class="flex items-start justify-between mb-6">
            <div>
                <div class="flex items-center space-x-2 mb-1">
                    <div class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-gray-500 text-sm">Your Money</span>
                </div>
                <p class="text-3xl font-bold text-gray-900">$ {{ number_format(($totalCashIn + $totalOnlineIn) - ($totalCashOut + $totalOnlineOut), 0) }}</p>
            </div>
            <button class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                </svg>
            </button>
        </div>

        <!-- Month Selector Tabs -->
        <div class="flex space-x-2 mb-6 overflow-x-auto pb-1">
            @foreach(['January', 'February', 'March', 'April', 'May'] as $month)
                <button
                    wire:click="selectMonth('{{ $month }}')"
                    class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-all {{ $selectedMonth === $month ? 'bg-[#4A9FD8] text-white' : 'text-gray-500 hover:bg-gray-50' }}"
                >
                    {{ $month }}
                </button>
            @endforeach
        </div>

        <!-- In Flow / Out Flow -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <!-- In Flow -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    </div>
                    <span class="text-gray-600 text-xs font-medium">In Flow</span>
                </div>
                <p class="text-xl font-bold text-gray-900">$ {{ number_format($totalCashIn + $totalOnlineIn, 0) }}</p>
            </div>

            <!-- Out Flow -->
            <div class="bg-gradient-to-br from-pink-50 to-red-50 rounded-2xl p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <div class="w-6 h-6 rounded-full bg-red-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                    </div>
                    <span class="text-gray-600 text-xs font-medium">Out Flow</span>
                </div>
                <p class="text-xl font-bold text-gray-900">$ {{ number_format($totalCashOut + $totalOnlineOut, 0) }}</p>
            </div>
        </div>

        <!-- Net Balance -->
        <div class="mb-6">
            <p class="text-gray-500 text-xs font-medium mb-1">Net Balance</p>
            <div class="flex items-baseline space-x-3">
                <p class="text-4xl font-bold text-gray-900">$ {{ number_format(($totalCashIn + $totalOnlineIn) - ($totalCashOut + $totalOnlineOut), 0) }}</p>
                @php
                    $income = $totalCashIn + $totalOnlineIn;
                    $expense = $totalCashOut + $totalOnlineOut;
                    $percentage = $income > 0 ? round(($expense / $income) * 100) : 0;
                @endphp
                <span class="text-[#4A9FD8] text-sm font-medium">You spent {{ $percentage }}% income</span>
            </div>
        </div>

        <!-- Chart -->
        <div class="relative h-48 mb-4">
            <svg class="w-full h-full" viewBox="0 0 350 180" preserveAspectRatio="xMidYMid meet">
                <!-- Y-axis labels -->
                <text x="5" y="20" class="text-xs fill-gray-400" font-size="10">$ 7K</text>
                <text x="5" y="65" class="text-xs fill-gray-400" font-size="10">$ 5K</text>
                <text x="5" y="110" class="text-xs fill-gray-400" font-size="10">$ 3K</text>
                <text x="5" y="155" class="text-xs fill-gray-400" font-size="10">$ 1K</text>

                <!-- X-axis labels -->
                <text x="45" y="175" class="text-xs fill-gray-400" font-size="10">10</text>
                <text x="95" y="175" class="text-xs fill-gray-400" font-size="10">11</text>
                <text x="145" y="175" class="text-xs fill-gray-400" font-size="10">12</text>
                <text x="195" y="175" class="text-xs fill-gray-400" font-size="10">13</text>
                <text x="245" y="175" class="text-xs fill-gray-400" font-size="10">14</text>
                <text x="295" y="175" class="text-xs fill-gray-400" font-size="10">15</text>

                <!-- Smooth curved line -->
                <path
                    d="M 40 100 Q 70 70, 90 60 T 140 80 T 190 40 T 240 65 T 290 90"
                    fill="none"
                    stroke="#EC4899"
                    stroke-width="3"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />

                <!-- Dots on the line -->
                <circle cx="40" cy="100" r="4" fill="#EC4899"/>
                <circle cx="90" cy="60" r="4" fill="#EC4899"/>
                <circle cx="140" cy="80" r="4" fill="#EC4899"/>
                <circle cx="190" cy="40" r="4" fill="#EC4899"/>
                <circle cx="240" cy="65" r="4" fill="#EC4899"/>
                <circle cx="290" cy="90" r="4" fill="#EC4899"/>
            </svg>
        </div>
    </div>

    <!-- Transactions Section -->
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-xl font-bold text-gray-900">Transactions</h2>
        <button wire:click="$set('showAllTransactions', true)" class="text-[#4A9FD8] text-sm font-medium hover:underline">
            View All
        </button>
    </div>

    <!-- Transaction Cards -->
    <div class="space-y-3">
        @php
            $recentTransactions = \App\Models\Transaction::with('person')
                ->latest('saved_at')
                ->take(5)
                ->get()
                ->groupBy(function($transaction) {
                    return $transaction->saved_at->format('d M Y');
                });
        @endphp

        @forelse($recentTransactions as $date => $transactions)
            <div class="space-y-3">
                <p class="text-sm font-semibold text-gray-700 px-1">{{ $date }}</p>
                @foreach($transactions as $transaction)
                    <div class="bg-white rounded-2xl shadow-md p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 flex-1">
                                <!-- Icon -->
                                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 {{ $transaction->direction === 'give' ? 'bg-orange-100' : 'bg-green-100' }}">
                                    <span class="text-lg font-bold {{ $transaction->direction === 'give' ? 'text-orange-600' : 'text-green-600' }}">
                                        {{ substr($transaction->person->name ?? 'T', 0, 1) }}
                                    </span>
                                </div>

                                <!-- Details -->
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm">{{ $transaction->person->name ?? 'Transaction' }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $transaction->description ?? ucfirst($transaction->method) . ' payment' }}</p>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="text-right ml-3">
                                <p class="font-bold text-sm {{ $transaction->direction === 'get' ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $transaction->direction === 'get' ? '+' : '-' }} $ {{ number_format($transaction->amount, 0) }}
                                </p>
                            </div>
                        </div>

                        <!-- Progress Bar (optional decoration) -->
                        <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-[#4A9FD8] rounded-full" style="width: {{ rand(30, 90) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-md p-8 text-center">
                <p class="text-gray-500">No transactions yet</p>
            </div>
        @endforelse
    </div>

    <!-- Transaction Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center z-50 p-0 sm:p-4" wire:click.self="cancel">
            <div class="bg-white rounded-t-[28px] sm:rounded-[28px] w-full sm:max-w-lg sm:mx-4 max-h-[90vh] overflow-y-auto shadow-2xl">
                <div class="sticky top-0 bg-white px-6 py-5 border-b border-gray-100 flex items-center justify-between rounded-t-[28px]">
                    <h3 class="text-xl font-bold text-gray-900">{{ $isEditing ? 'Edit Transaction' : 'New Transaction' }}</h3>
                    <button wire:click="cancel" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="px-6 py-6 space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Person</label>
                            <select wire:model="selectedPersonId" class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#4A9FD8] focus:border-transparent font-medium">
                                <option value="">Select person</option>
                                @foreach(\App\Models\Person::all() as $person)
                                    <option value="{{ $person->id }}">{{ $person->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Amount</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">$</span>
                                <input type="number" step="0.01" wire:model="amount" class="w-full pl-8 pr-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#4A9FD8] focus:border-transparent text-lg font-semibold" placeholder="0.00">
                            </div>
                            @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Direction</label>
                                <select wire:model="direction" class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#4A9FD8] focus:border-transparent font-medium">
                                    <option value="get">Receive</option>
                                    <option value="give">Pay</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Method</label>
                                <select wire:model="method" class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#4A9FD8] focus:border-transparent font-medium">
                                    <option value="cash">Cash</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea wire:model="description" rows="3" class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#4A9FD8] focus:border-transparent resize-none" placeholder="Add a note..."></textarea>
                        </div>
                    </div>

                    <div class="sticky bottom-0 bg-white px-6 py-5 border-t border-gray-100 flex gap-3 rounded-b-[28px]">
                        <button type="button" wire:click="cancel" class="flex-1 px-6 py-4 border-2 border-gray-200 rounded-2xl text-base font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-6 py-4 bg-[#4A9FD8] hover:bg-[#3A8FC8] text-white rounded-2xl text-base font-semibold shadow-lg transition-all">
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
