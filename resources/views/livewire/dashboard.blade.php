<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-2 text-sm text-gray-600">Overview of your cash and online transactions</p>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Cash In -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="text-green-500 text-2xl">💵</div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Cash In</dt>
                                <dd class="text-lg font-semibold text-gray-900">₹{{ number_format($totalCashIn, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cash Out -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="text-red-500 text-2xl">💸</div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Cash Out</dt>
                                <dd class="text-lg font-semibold text-gray-900">₹{{ number_format($totalCashOut, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Online In -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="text-blue-500 text-2xl">💳</div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Online In</dt>
                                <dd class="text-lg font-semibold text-gray-900">₹{{ number_format($totalOnlineIn, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Online Out -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="text-purple-500 text-2xl">🌐</div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Online Out</dt>
                                <dd class="text-lg font-semibold text-gray-900">₹{{ number_format($totalOnlineOut, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Overdue Payments -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Overdue Payments</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($overdueDues as $due)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $due->person->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $due->description ?? 'No description' }}</p>
                                </div>
                                <div class="ml-4 flex-shrink-0 text-right">
                                    <p class="text-sm font-semibold text-red-600">₹{{ number_format($due->amount, 2) }}</p>
                                    <p class="text-xs text-gray-500">Due: {{ $due->due_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">
                            No overdue payments
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Dues -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Upcoming Dues</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($upcomingDues as $due)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $due->person->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $due->description ?? 'No description' }}</p>
                                </div>
                                <div class="ml-4 flex-shrink-0 text-right">
                                    <p class="text-sm font-semibold text-green-600">₹{{ number_format($due->amount, 2) }}</p>
                                    <p class="text-xs text-gray-500">Due: {{ $due->due_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">
                            No upcoming dues
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent People -->
            <div class="bg-white shadow rounded-lg overflow-hidden lg:col-span-2">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Recent People</h3>
                    <a href="/people" class="text-sm font-medium text-blue-600 hover:text-blue-500">View all</a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentPeople as $person)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $person->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        @if($person->phone)
                                            {{ $person->phone }}
                                        @endif
                                        @if($person->email)
                                            {{ $person->phone ? ' • ' : '' }}{{ $person->email }}
                                        @endif
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <a href="/people/{{ $person->id }}" class="text-sm text-blue-600 hover:text-blue-500">View</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">
                            <p class="mb-2">No people added yet</p>
                            <a href="/people" class="text-sm font-medium text-blue-600 hover:text-blue-500">Add your first person</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
