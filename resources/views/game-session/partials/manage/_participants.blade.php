<div
     class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5 shadow-inner border border-gray-200 dark:border-gray-700">
    <h3 class="text-sm uppercase tracking-wide text-gray-500 dark:text-gray-400 font-semibold mb-3">
        Participants Overview
    </h3>

    <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
        Here’s who’s joining this session!
        Use this list to plan games that fit the group’s experience level and interests.
    </p>


    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50">
                        <th class="px-4 py-3 font-semibold">Photo</th>
                        <th class="px-4 py-3 font-semibold">Name</th>
                        <th class="px-4 py-3 font-semibold">Role</th>
                        <th class="px-4 py-3 font-semibold">Level</th>
                        <th class="px-4 py-3 font-semibold">Joined</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($confirmedRegistrations as $confirmedRegistration)
                        <tr
                            class="border-b border-gray-100 dark:border-gray-700 cursor-pointer transition-colors
                                           hover:bg-indigo-50 dark:hover:bg-indigo-900/40">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                <img src="{{ asset($confirmedRegistration->user->getPhotoURL()) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                {{ $confirmedRegistration->user->name }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                {{ $confirmedRegistration->user->role->label() }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                <div class="flex">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm shadow-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                        {{ $confirmedRegistration->user->level }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ $confirmedRegistration->user->created_at->format('d M Y') }}
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
        </div>
    </div>
</div>

