<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('About Us') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-10 text-gray-800 dark:text-gray-200">

            <!-- Section 1: The Hobby -->
            <section class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 leading-relaxed">
                <h3 class="text-2xl font-semibold mb-3">🎲 A Shared Passion for Board Gaming</h3>
                <p class="text-gray-700 dark:text-gray-300">
                    In a world where face-to-face interaction has slowly become optional, we believe in keeping
                    real connections alive through the timeless joy of tabletop games.
                    Board gaming isn’t just about winning or losing — it’s about stories, laughter, strategy,
                    and the moments shared around a table.
                </p>
                <p class="mt-3 text-gray-700 dark:text-gray-300">
                    Our hobby brings people together — old friends, new players, and everyone who wants to take a break
                    from screens and rediscover what it means to play, think, and socialize together.
                </p>
            </section>

            <!-- Section 2: The Purpose of the App -->
            <section class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 leading-relaxed">
                <h3 class="text-2xl font-semibold mb-3">💡 The Purpose Behind This App</h3>
                <p class="text-gray-700 dark:text-gray-300">
                    This app helps local board-gaming enthusiasts connect and organize weekly sessions more easily and efficiently.
                    Whether you’re looking to join an open table, show interest in an upcoming event, or simply find new people
                    who share your passion, we’ve got you covered.
                </p>

                <ul class="list-disc pl-5 mt-4 space-y-2 text-gray-700 dark:text-gray-300">
                    <li>✅ <strong>Join a gaming session</strong> that fits your schedule and group size.</li>
                    <li>👀 <strong>Show interest</strong> for an upcoming session and get notified when it’s confirmed.</li>
                    <li>📅 <strong>Request a session</strong> when no events are planned for the weekend —
                        help organizers understand when players are available. <strong>Organizers</strong> can plan smarter by seeing when players are most likely to join.</li>
                </ul>

                <p class="mt-4 text-gray-700 dark:text-gray-300">
                    Our goal is simple: make board-gaming events easier to plan, more inclusive, and full of people
                    who share the same excitement to play together in person.
                </p>
            </section>
            @if ($featuredMembers)
                <!-- Section 3: Community Members -->
                <section class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 leading-relaxed">
                    <h3 class="text-2xl font-semibold mb-3">👥 Meet Our Members</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        Our community is built by amazing people who share their passion for board games.
                        Here are some of our featured members — the heart of most game sessions.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @forelse($featuredMembers as $member)
                            <div class="p-5 bg-gray-50 dark:bg-gray-900/40 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition">
                                <div class="flex items-center gap-3 mb-3">
                                    <img src="{{ asset($member->user->getPhotoURL()) }}"
                                         alt="{{ $member->user->name }}"
                                         class="w-12 h-12 rounded-full ring-2 ring-indigo-500 object-cover">
                                    <div>
                                        <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">
                                            {{ $member->user->name }}
                                        </h4>
                                    </div>
                                </div>

                                @if($member->description)
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-3 leading-relaxed">
                                        “{{ $member->description }}”
                                    </p>
                                @endif

                                @if($member->bgg_profile_url)
                                    <a href="{{ $member->bgg_profile_url }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-indigo-600 dark:text-indigo-400 text-sm font-medium hover:underline">
                                        View BoardGameGeek Profile
                                    </a>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                                No featured members yet — you could be the first!
                            </p>
                        @endforelse
                    </div>

                    <p class="mt-6 text-sm text-gray-500 dark:text-gray-400 italic text-center">
                        Want to be featured here? Stay active, join sessions, and help grow the community — your story might be next!
                    </p>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
