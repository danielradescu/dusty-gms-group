<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            👥 {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                            <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50">
                                <th class="px-4 py-3 font-semibold">Name</th>
                                <th class="px-4 py-3 font-semibold">Email</th>
                                <th class="px-4 py-3 font-semibold">Role</th>
                                <th class="px-4 py-3 font-semibold">Joined</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                                <tr
                                    class="border-b border-gray-100 dark:border-gray-700 cursor-pointer transition-colors
                                               hover:bg-indigo-50 dark:hover:bg-indigo-900/40"
                                    onclick="window.location='{{ route('admin.user.edit', $user->id) }}'">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-4 py-3">{{ $user->email }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                        {{ $user->role->label() }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
