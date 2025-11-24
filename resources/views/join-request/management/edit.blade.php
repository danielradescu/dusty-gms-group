<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ⚙️ {{ __('Manage Join Request / Invitation') }}
        </h2>
    </x-slot>

    ```
    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    <div class="mb-4">
                        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('management-join-request-index') }}"
                           class="inline-flex items-center px-4 py-2 rounded-md bg-gray-200 text-gray-700
                              hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600
                              focus:outline-none focus:ring-2 focus:ring-gray-400 transition text-sm font-medium">
                            ← Back
                        </a>
                    </div>

                    <h3 class="text-lg font-semibold">Request Details</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-xs">Name</p>
                            <p>{{ $joinRequest->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-xs">Email</p>
                            <p>{{ $joinRequest->email }}</p>
                        </div>
                        @if($joinRequest->phone)
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-xs">Phone</p>
                                <p>{{ $joinRequest->phone }}</p>
                            </div>
                        @endif
                        @if($joinRequest->other_means_of_contact)
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-xs">Other Contact</p>
                                <p>{{ $joinRequest->other_means_of_contact }}</p>
                            </div>
                        @endif
                        <div class="sm:col-span-2">
                            <p class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-xs">Message</p>
                            <p class="whitespace-pre-line">{{ $joinRequest->message ?? '—' }}</p>
                        </div>

                        <div>
                            <p class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-xs">IP Address</p>
                            <p>{{ $joinRequest->ip_address ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-xs">User Agent</p>
                            <p class="break-all text-xs">{{ $joinRequest->user_agent ?? '—' }}</p>
                        </div>

                        <div>
                            <p class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-xs">Initiated By</p>
                            <p>{{ $joinRequest->initiator?->name ?? '-Public Request-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-xs">Created</p>
                            <p>{{ $joinRequest->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        @if($joinRequest->reviewed_by)
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-xs">Reviewed By</p>
                                <p>{{ $joinRequest->reviewer?->name ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-xs">Reviewed At</p>
                                <p>{{ optional($joinRequest->reviewed_at)->format('M d, Y H:i') ?? '—' }}</p>
                            </div>
                        @endif
                    </div>
                    @if ($joinRequest->status != \App\Enums\JoinRequestStatus::APPROVED)
                        <div class="mt-6 border-t border-gray-700 pt-6">
                            <h3 class="text-lg font-semibold mb-3">Update Status</h3>

                            <form action="{{ route('management-join-request-update', $joinRequest->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                    <select id="status" name="status"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach(App\Enums\JoinRequestStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ $joinRequest->status === $status ? 'selected' : '' }}>
                                                {{ ucfirst($status->value) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                </div>

                                <div class="flex items-center gap-3">
                                    <button type="submit"
                                            class="px-4 py-2 rounded-md bg-indigo-500 text-white hover:bg-indigo-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus:ring-indigo-800">
                                        💾 Save Changes
                                    </button>

                                    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('management-join-request-index') }}"
                                       class="px-4 py-2 rounded-md border border-gray-300 bg-gray-50 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    ```

</x-app-layout>
