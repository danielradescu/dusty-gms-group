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
                        <x-link-button class="!w-auto" href="{{ url()->previous() !== url()->current() ? url()->previous() : route('management-join-request-index') }}" variant="secondary">← Back</x-link-button>
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
                                        @foreach(collect(App\Enums\JoinRequestStatus::cases())->reject(fn($case) => $case === App\Enums\JoinRequestStatus::REGISTERED) as $status)
                                            <option value="{{ $status->value }}"
                                                {{ old('status', $joinRequest->status->value) == $status->value ? 'selected' : '' }}>
                                                {{ ucfirst($status->label()) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                </div>

                                <div>
                                    <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Organizer Notes
                                    </label>
                                    <textarea id="note" name="note" rows="3"
                                              placeholder="Example: Contacted on Nov 25 via WhatsApp, confirmed they play Eurogames..."
                                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">{{ old('note', $joinRequest->note) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('note')" />
                                </div>

                                <div class="mt-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                                        💬 Before approving or rejecting this request, please personally contact the applicant using the
                                        <strong>phone number</strong> or <strong>other contact method</strong> they provided.
                                        Confirm their interest and clarify any questions. Only after this conversation,
                                        update the status below.
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        📝 Use the note field to record any relevant details for other organizers
                                        (for example, when you contacted the person or what was discussed).
                                        This note is visible only to organizers and admins.
                                    </p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <x-button variant="primary">💾 Save Changes</x-button>
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
