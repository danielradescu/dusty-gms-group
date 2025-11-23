<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🎲 {{ $gameSession->name }}
        </h2>
        <p class="text-sm text-gray-800 dark:text-gray-400">{!! \App\Helpers\TextHelper::linkify($gameSession->description ?? 'To be decided') !!}</p>
    </x-slot>

    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-8 space-y-6">
            @if ($gameSession->status === \App\Enums\GameSessionStatus::RECRUITING_PARTICIPANTS)
                @include('game-session.partials.manage._core_info',[$gameSession, $confirmedRegistrations])
            @endif
            @include('game-session.partials.manage._participants',[$gameSession, $confirmedRegistrations])
            @include('game-session.partials.manage._status',[$gameSession, $confirmedRegistrations])
            @include('game-session.partials.manage._organizer',[$gameSession, $confirmedRegistrations])
            @include('game-session.partials._comments', [$comments, $gameSession])
        </div>
    </div>

</x-app-layout>

<!-- Small inline JS to toggle buttons -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.change-section').forEach(section => {
            const checkbox = section.querySelector('.change-confirm');
            const buttonWrapper = section.querySelector('.save-button');

            checkbox.addEventListener('change', () => {
                buttonWrapper.classList.toggle('hidden', !checkbox.checked);
            });
        });
    });
</script>

<!-- Small inline JS to toggle reason field -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const statusSelect = document.getElementById('status');
        const reasonBox = document.getElementById('cancel-reason-container');
        const cancelValue = "{{ \App\Enums\GameSessionStatus::CANCELLED->value }}";

        const toggleReasonBox = () => {
            if (statusSelect.value === cancelValue) {
                reasonBox.classList.remove('hidden');
            } else {
                reasonBox.classList.add('hidden');
            }
        };

        statusSelect.addEventListener('change', toggleReasonBox);

        // ✅ Trigger on initial load (handles validation error case)
        toggleReasonBox();
    });
</script>
