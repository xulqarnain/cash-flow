<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">People</h1>
                <p class="mt-2 text-sm text-gray-600">Manage people and their transactions</p>
            </div>
            <button wire:click="openForm" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Add Person
            </button>
        </div>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Add/Edit Form Modal -->
        @if($showForm)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $isEditing ? 'Edit Person' : 'Add New Person' }}
                        </h3>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="px-6 py-4 space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    wire:model="name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter person's name"
                                >
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Phone
                                </label>
                                <input
                                    type="text"
                                    id="phone"
                                    wire:model="phone"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter phone number"
                                >
                                @error('phone')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    wire:model="email"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter email address"
                                >
                                @error('email')
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

        <!-- Search Bar -->
        <div class="mb-6">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search by name, phone, or email..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
            >
        </div>

        <!-- People List -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            @if($people->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($people as $person)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $person->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        @if($person->phone)
                                            <div>{{ $person->phone }}</div>
                                        @endif
                                        @if($person->email)
                                            <div>{{ $person->email }}</div>
                                        @endif
                                        @if(!$person->phone && !$person->email)
                                            <span class="text-gray-400">No contact info</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $balance = $person->balance();
                                    @endphp
                                    <span class="text-sm font-medium {{ $balance > 0 ? 'text-green-600' : ($balance < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                        ₹{{ number_format(abs($balance), 2) }}
                                        @if($balance > 0)
                                            (to receive)
                                        @elseif($balance < 0)
                                            (to pay)
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="/people/{{ $person->id }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    <button wire:click="edit({{ $person->id }})" class="text-yellow-600 hover:text-yellow-900">Edit</button>
                                    <button
                                        wire:click="delete({{ $person->id }})"
                                        onclick="return confirm('Are you sure you want to delete this person? All their transactions will also be deleted.')"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $people->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <p class="text-gray-500 mb-4">
                        @if($search)
                            No people found matching "{{ $search }}"
                        @else
                            No people added yet
                        @endif
                    </p>
                    @if(!$search)
                        <button wire:click="openForm" class="text-blue-600 hover:text-blue-700 font-medium">
                            Add your first person
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
