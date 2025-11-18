<div class="pb-6">
    <!-- Header with Month Selector -->
    <div class="bg-white rounded-3xl shadow-xl shadow-blue-500/20 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-900">History Transactions</h1>
            <button wire:click="openForm" class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-500/50 hover:bg-blue-600 btn-press">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </button>
        </div>

        <!-- Month Dropdown -->
        <div class="relative">
            <select class="w-full px-4 py-3 bg-gradient-to-br from-gray-50 to-blue-50/30 border-0 rounded-2xl text-gray-900 font-medium focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer">
                <option>January</option>
                <option selected>February</option>
                <option>March</option>
                <option>April</option>
                <option>May</option>
                <option>June</option>
            </select>
            <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

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

    <!-- Search Bar -->
    <div class="bg-white rounded-3xl shadow-xl shadow-blue-500/20 p-2 mb-6">
        <div class="relative">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search by name, phone, or email..."
                class="w-full px-4 py-3 pl-12 border-0 bg-gradient-to-br from-gray-50 to-blue-50/30 rounded-2xl focus:ring-2 focus:ring-blue-500 placeholder-gray-400 font-medium"
            >
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    <!-- People Cards -->
    @if($people->count() > 0)
        <div class="bg-white rounded-3xl shadow-xl shadow-blue-500/20 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">All Contacts</h2>
            <div class="space-y-3">
                @foreach($people as $person)
                    <div class="bg-gradient-to-br from-gray-50 to-blue-50/30 rounded-2xl p-4 hover:shadow-md card-hover shadow-smooth">
                        <div class="flex items-center gap-3 mb-3">
                            <!-- Avatar -->
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                <span class="text-white text-lg font-bold">{{ substr($person->name, 0, 1) }}</span>
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-semibold text-gray-900 truncate">{{ $person->name }}</h3>
                                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                    @if($person->phone)
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            {{ $person->phone }}
                                        </span>
                                    @elseif($person->email)
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $person->email }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">No contact info</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Balance -->
                            @php
                                $balance = $person->balance();
                            @endphp
                            <div class="text-right flex-shrink-0">
                                <p class="text-lg font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-pink-600' }}">
                                    {{ $balance >= 0 ? '+' : '–' }} ₹{{ number_format(abs($balance), 0) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">
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

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 pt-3 border-t border-gray-200/50">
                            <a href="/people/{{ $person->id }}" wire:navigate class="flex-1 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-medium text-center shadow-md shadow-blue-500/30 btn-press">
                                View Details
                            </a>
                            <button wire:click="edit({{ $person->id }})" class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 rounded-xl text-sm font-medium border border-gray-200 btn-press">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button
                                wire:click="delete({{ $person->id }})"
                                onclick="return confirm('Are you sure? All transactions will be deleted.')"
                                class="px-4 py-2 bg-white hover:bg-red-50 text-red-600 rounded-xl text-sm font-medium border border-gray-200 btn-press"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($people->hasPages())
                <div class="mt-6 pt-4 border-t border-gray-200">
                    {{ $people->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-xl shadow-blue-500/20 p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl flex items-center justify-center">
                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <p class="text-gray-900 font-semibold text-lg mb-2">
                @if($search)
                    No people found matching "{{ $search }}"
                @else
                    No people added yet
                @endif
            </p>
            @if(!$search)
                <p class="text-gray-500 mb-6">Start by adding your first contact</p>
                <button wire:click="openForm" class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-2xl font-semibold shadow-lg shadow-blue-500/50 btn-press">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Your First Person
                </button>
            @endif
        </div>
    @endif

    <!-- Add/Edit Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center z-50 p-0 sm:p-4 modal-backdrop" wire:click.self="cancel">
            <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full sm:max-w-lg sm:mx-4 max-h-[90vh] overflow-y-auto shadow-2xl animate-slide-up">
                <div class="sticky top-0 bg-white px-6 py-5 border-b border-gray-100 flex items-center justify-between rounded-t-3xl">
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
                                class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent font-medium"
                                placeholder="Enter person's name"
                            >
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                            <input
                                type="text"
                                wire:model="phone"
                                class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent font-medium"
                                placeholder="Enter phone number"
                            >
                            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input
                                type="email"
                                wire:model="email"
                                class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent font-medium"
                                placeholder="Enter email address"
                            >
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="sticky bottom-0 bg-white px-6 py-5 border-t border-gray-100 flex gap-3 rounded-b-3xl">
                        <button
                            type="button"
                            wire:click="cancel"
                            class="flex-1 px-6 py-4 border-2 border-gray-200 rounded-2xl text-base font-semibold text-gray-700 hover:bg-gray-50 btn-press"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="flex-1 px-6 py-4 bg-blue-500 hover:bg-blue-600 text-white rounded-2xl text-base font-semibold shadow-lg shadow-blue-500/50 btn-press"
                        >
                            {{ $isEditing ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
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
