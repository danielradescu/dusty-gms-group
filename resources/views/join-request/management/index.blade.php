<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            👥 {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                            <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50">
                                <th class="px-4 py-3 font-semibold">Name</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 font-semibold">Initiated By</th>
                                <th class="px-4 py-3 font-semibold">Reviewed By</th>
                                <th class="px-4 py-3 font-semibold">Created</th>
                                <th class="px-4 py-3 font-semibold"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($joinRequests as $request)
                                <tr class="border-b border-gray-100 dark:border-gray-700 transition-colors hover:bg-indigo-50 dark:hover:bg-indigo-900/40">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $request->name }}</td>
                                    <td class="px-4 py-3">
                                        <x-join-request-status-badge :joinRequestStatus="$request->status"/>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $request->initiator?->name ?? '-Public Request-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $request->reviewer?->name ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $request->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('management-join-request-edit', $request->id) }}"
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 transition">
                                            ⚙ Manage →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                        No join requests or invites found.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Showing {{ $joinRequests->firstItem() ?? 0 }} to {{ $joinRequests->lastItem() ?? 0 }}
                            of {{ $joinRequests->total() }} results
                        </p>
                        <div>
                            {{ $joinRequests->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
