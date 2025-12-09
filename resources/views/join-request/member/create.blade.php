<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Invite to Join the Community') }}
        </h2>
    </x-slot>
    <div class="py-12 pb-0">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-xl p-6 text-gray-900 dark:text-gray-100 space-y-6">
                @if(session('success'))
                    <div
                        class="bg-emerald-100 dark:bg-emerald-800/30 border borderemerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300 rounded-md p-3">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('member-invite-store') }}" method="POST"
                      class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium textgray-700 dark:text-gray-300">Name</label>
                        <input type="text" id="name" name="name"
                               value="{{ old('name') }}"
                               class="mt-1 block w-full rounded-md border-gray-300
                                dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500
                                focus:ring-indigo-500" required>
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ old('name') }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email"
                               class="block text-sm font-medium textgray-700 dark:text-gray-300">Email</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               class="mt-1 block w-full rounded-md border-gray-300
                                    dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500
                                    focus:ring-indigo-500" required>
                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ old('email') }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone"
                               class="block text-sm font-medium textgray-700 dark:text-gray-300">Phone number (optional)</label>
                        <input type="text" id="phone" name="phone"
                               value="{{ old('phone') }}"
                               class="mt-1 block w-full rounded-md border-gray-300
                                    dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500
                                    focus:ring-indigo-500">
                        @error('phone')
                            <p class="text-sm text-red-500 mt-1">{{ old('phone') }}</p>
                        @enderror
                    </div>
                    <div class="pt-3">
                        <x-button variant="primary">Send Invite</x-button>
                    </div>
                </form>
                <div class="mt-8 border-t border-gray-700 pt-6">
                    <h3 class="text-lg font-semibold mb-4">Your Invites</h3>
                    <table class="min-w-full text-sm">
                        <thead>
                        <tr class="border-b border-gray-700 text-left textgray-400 uppercase text-xs">
                            <th class="py-2">Name</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($invites as $invite)
                            <tr class="border-b border-gray-700">
                                <td class="py-2">{{ $invite->name }}</td>
                                <td class="py-2">{{ $invite->email }}</td>
                                <td class="py-2"><x-join-request-status-badge :joinRequestStatus="$invite->status"/></td>
                                <td class="py-2">{{ $invite->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-gray-500 textcenter">
                                    You don't have any invites.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $invites->links() }}
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Note: Each invite will be removed after 7 days.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
