<div class="pb-6">
    <!-- Month Selector -->
    <div class="bg-white rounded-3xl shadow-xl shadow-blue-500/20 p-1 mb-6 overflow-x-auto">
        <div class="flex space-x-2 min-w-max px-2">
            @foreach(['January', 'February', 'March', 'April', 'May', 'June'] as $month)
                <button
                    wire:click="selectMonth('{{ $month }}')"
                    class="px-5 py-2.5 rounded-2xl text-sm font-medium whitespace-nowrap btn-press {{ $selectedMonth === $month ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/50' : 'text-gray-600 hover:bg-gray-50' }}"
                >
                    {{ $month }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- In Flow / Out Flow Card -->
    <div class="bg-white rounded-3xl shadow-xl shadow-blue-500/20 p-6 sm:p-8 mb-6">
        <div class="grid grid-cols-2 gap-6 mb-8">
            <!-- In Flow -->
            <div>
                <div class="flex items-center space-x-2 mb-2">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                    </div>
                    <span class="text-gray-500 text-sm font-medium">In Flow</span>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">₹{{ number_format($totalCashIn + $totalOnlineIn, 0) }}</p>
            </div>

            <!-- Out Flow -->
            <div>
                <div class="flex items-center space-x-2 mb-2">
                    <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                        </svg>
                    </div>
                    <span class="text-gray-500 text-sm font-medium">Out Flow</span>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">₹{{ number_format($totalCashOut + $totalOnlineOut, 0) }}</p>
            </div>
        </div>

        <!-- Net Balance -->
        <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-6 mb-6">
            <p class="text-gray-600 text-sm font-medium mb-1">Net Balance</p>
            <p class="text-4xl sm:text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                ₹{{ number_format(($totalCashIn + $totalOnlineIn) - ($totalCashOut + $totalOnlineOut), 0) }}
            </p>
        </div>

        <!-- Simple Chart Representation -->
        <div class="relative h-48 bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-4">
            <div class="absolute inset-0 flex items-end justify-around p-4">
                @for($i = 1; $i <= 7; $i++)
                    <div class="flex flex-col items-center space-y-2">
                        <div class="w-8 sm:w-10 bg-gradient-to-t from-pink-400 to-pink-300 rounded-t-lg shadow-lg" style="height: {{ rand(40, 90) }}%"></div>
                        <span class="text-xs text-gray-500 font-medium">{{ $i }}</span>
                    </div>
                @endfor
            </div>
            <!-- Chart Lines Overlay -->
            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <polyline
                    points="10,60 25,45 40,55 55,35 70,50 85,30"
                    fill="none"
                    stroke="#60a5fa"
                    stroke-width="0.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    opacity="0.6"
                />
                <polyline
                    points="10,70 25,60 40,65 55,50 70,60 85,45"
                    fill="none"
                    stroke="#f472b6"
                    stroke-width="0.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    opacity="0.8"
                />
            </svg>
        </div>
    </div>

    <!-- Transactions Section -->
    <div class="bg-white rounded-3xl shadow-xl shadow-blue-500/20 p-6 sm:p-8 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Transactions</h2>
            <button wire:click="openForm" class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-500/50 hover:bg-blue-600 btn-press">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </button>
        </div>

        <!-- People List -->
        @forelse($people as $person)
            <button
                wire:click="selectPerson({{ $person->id }})"
                class="w-full mb-3 bg-gradient-to-br from-gray-50 to-blue-50/30 hover:from-blue-50 hover:to-purple-50/30 rounded-2xl p-4 text-left shadow-sm hover:shadow-md card-hover shadow-smooth"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 flex-1">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center shadow-lg">
                            <span class="text-white text-lg font-bold">{{ substr($person->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $person->name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ $person->transactions_count }} transactions</p>
                        </div>
                    </div>
                    @php $balance = $person->balance(); @endphp
                    <div class="text-right ml-3">
                        <p class="font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-pink-600' }}">
                            {{ $balance >= 0 ? '+' : '–' }} ₹{{ number_format(abs($balance), 0) }}
                        </p>
                        <svg class="w-5 h-5 text-gray-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </button>

            <!-- Expanded Transactions -->
            @if($selectedPersonId == $person->id && $showTransactions && $transactions)
                <div class="ml-4 pl-4 border-l-2 border-blue-200 mb-4 space-y-2" wire:loading.class="opacity-50">
                    @forelse($transactions as $transaction)
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $transaction->description ?: 'Transaction' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $transaction->saved_at->format('d M Y') }} •
                                        <span class="capitalize">{{ $transaction->method }}</span>
                                    </p>
                                </div>
                                <p class="font-bold {{ $transaction->direction === 'get' ? 'text-green-600' : 'text-pink-600' }} ml-3">
                                    {{ $transaction->direction === 'get' ? '+' : '–' }} ₹{{ number_format($transaction->amount, 0) }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 text-sm py-4">No transactions yet</p>
                    @endforelse

                    @if($transactions->hasPages())
                        <div class="mt-4">{{ $transactions->links() }}</div>
                    @endif

                    <button wire:click="closeTransactions" class="text-sm text-blue-600 hover:text-blue-700 font-medium mt-2">
                        Close
                    </button>
                </div>
            @endif
        @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <p class="text-gray-600 mb-4">No people added yet</p>
                <a href="/people" wire:navigate class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-2xl font-medium shadow-lg shadow-blue-500/50 transition-all">
                    Add First Person
                </a>
            </div>
        @endforelse
    </div>

    <!-- Transaction Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center z-50 p-0 sm:p-4 modal-backdrop" wire:click.self="cancel">
            <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full sm:max-w-lg sm:mx-4 max-h-[90vh] overflow-y-auto shadow-2xl animate-slide-up">
                <div class="sticky top-0 bg-white px-6 py-5 border-b border-gray-100 flex items-center justify-between rounded-t-3xl">
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
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Amount</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">₹</span>
                                <input type="number" step="0.01" wire:model="amount" class="w-full pl-8 pr-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg font-semibold" placeholder="0.00">
                            </div>
                            @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Direction</label>
                                <select wire:model="direction" class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent font-medium">
                                    <option value="get">Receive</option>
                                    <option value="give">Pay</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Method</label>
                                <select wire:model="method" class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent font-medium">
                                    <option value="cash">Cash</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Due Date (Optional)</label>
                            <input type="date" wire:model="due_date" class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea wire:model="description" rows="3" class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Add a note..."></textarea>
                        </div>
                    </div>

                    <div class="sticky bottom-0 bg-white px-6 py-5 border-t border-gray-100 flex gap-3 rounded-b-3xl">
                        <button type="button" wire:click="cancel" class="flex-1 px-6 py-4 border-2 border-gray-200 rounded-2xl text-base font-semibold text-gray-700 hover:bg-gray-50 btn-press">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-6 py-4 bg-blue-500 hover:bg-blue-600 text-white rounded-2xl text-base font-semibold shadow-lg shadow-blue-500/50 btn-press">
                            {{ $isEditing ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="fixed top-24 left-1/2 -translate-x-1/2 bg-white rounded-2xl shadow-xl px-6 py-4 z-50 success-message border-l-4 border-green-500">
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
            <svg class="animate-spin h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
</div>
