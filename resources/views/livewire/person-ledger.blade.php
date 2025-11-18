<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header with Person Info -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <a href="/people" class="text-sm text-blue-600 hover:text-blue-700 mb-2 inline-block">&larr; Back to People</a>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $person->name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        @if($person->phone)
                            {{ $person->phone }}
                        @endif
                        @if($person->email)
                            {{ $person->phone ? ' • ' : '' }}{{ $person->email }}
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600 mb-1">Current Balance</p>
                    <p class="text-3xl font-bold {{ $balance > 0 ? 'text-green-600' : ($balance < 0 ? 'text-red-600' : 'text-gray-500') }}">
                        ₹{{ number_format(abs($balance), 2) }}
                    </p>
                    <p class="text-sm text-gray-600">
                        @if($balance > 0)
                            To receive
                        @elseif($balance < 0)
                            To pay
                        @else
                            Settled
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Transaction Form Modal -->
        @if($showForm)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $isEditing ? 'Edit Transaction' : 'Add New Transaction' }}
                        </h3>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="px-6 py-4 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                                        Amount <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        id="amount"
                                        wire:model="amount"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="0.00"
                                    >
                                    @error('amount')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">
                                        Due Date
                                    </label>
                                    <input
                                        type="date"
                                        id="due_date"
                                        wire:model="due_date"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    >
                                    @error('due_date')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Direction <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="direction" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                        <option value="get">I will receive</option>
                                        <option value="give">I will pay</option>
                                        <option value="both">Both</option>
                                    </select>
                                    @error('direction')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Method <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                        <option value="cash">Cash</option>
                                        <option value="online">Online</option>
                                    </select>
                                    @error('method')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description
                                </label>
                                <textarea
                                    id="description"
                                    wire:model="description"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Reason or context for this transaction"
                                ></textarea>
                                @error('description')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-3">
                            <button
                                type="button"
                                wire:click="cancel"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium"
                            >
                                {{ $isEditing ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Filters and Add Button -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Transactions</h3>
                <button wire:click="openForm" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Add Transaction
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Direction</label>
                    <select wire:model.live="filterDirection" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">All</option>
                        <option value="get">Receive</option>
                        <option value="give">Pay</option>
                        <option value="both">Both</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Method</label>
                    <select wire:model.live="filterMethod" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">All</option>
                        <option value="cash">Cash</option>
                        <option value="online">Online</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" wire:model.live="filterDateFrom" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" wire:model.live="filterDateTo" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            @if($filterDirection || $filterMethod || $filterDateFrom || $filterDateTo)
                <div class="mt-4">
                    <button wire:click="clearFilters" class="text-sm text-blue-600 hover:text-blue-700">
                        Clear Filters
                    </button>
                </div>
            @endif
        </div>

        <!-- Transactions List -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            @if($transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direction</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50 {{ $transaction->isOverdue() ? 'bg-red-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->saved_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $transaction->description ?: 'No description' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            {{ $transaction->direction === 'get' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $transaction->direction === 'give' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $transaction->direction === 'both' ? 'bg-blue-100 text-blue-800' : '' }}">
                                            {{ ucfirst($transaction->direction) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            {{ $transaction->method === 'cash' ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ ucfirst($transaction->method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                        ₹{{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($transaction->due_date)
                                            <span class="{{ $transaction->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                                {{ $transaction->due_date->format('M d, Y') }}
                                                @if($transaction->isOverdue())
                                                    <br><span class="text-xs">(Overdue)</span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <button wire:click="edit({{ $transaction->id }})" class="text-yellow-600 hover:text-yellow-900">Edit</button>
                                        <button
                                            wire:click="delete({{ $transaction->id }})"
                                            onclick="return confirm('Are you sure you want to delete this transaction?')"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <p class="text-gray-500 mb-4">No transactions found</p>
                    <button wire:click="openForm" class="text-blue-600 hover:text-blue-700 font-medium">
                        Add first transaction
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
