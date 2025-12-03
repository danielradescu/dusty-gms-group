<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📬 Contact Administration
        </h2>
        <p class="text-sm text-gray-800 dark:text-gray-400">
            Have an issue or suggestion? Use this form to reach the organizing or admin team directly.
        </p>
    </x-slot>

    <!-- Body -->
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Main Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    <!-- Back to Dashboard -->
                    <div class="mb-4">
                        <x-link-button class="!w-auto" href="{{ route('dashboard') }}" variant="secondary">← Back to Dashboard</x-link-button>
                    </div>

                    <!-- Intro -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            Get in Touch
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            Help us make things better!
                            Tell us what’s working well and what could be improved — your feedback helps shape a better experience for everyone.
                        </p>
                    </div>

                    <!-- Success message -->
                    @if (session('status'))
                        <div class="rounded-md bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-800 p-3">
                            <p class="text-emerald-800 dark:text-emerald-300 text-sm font-medium">
                                {{ session('status') }}
                            </p>
                        </div>
                    @endif

                    <!-- Contact Form -->
                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                        @csrf

                        <!-- Purpose -->
                        <div>
                            <label for="purpose"
                                   class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-2">
                                Purpose of your message
                            </label>
                            <select name="purpose" id="purpose" required
                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700
                                           text-gray-800 dark:text-gray-200 rounded-md p-2 focus:ring-2 focus:ring-indigo-500
                                           focus:border-transparent">
                                <option value="" disabled selected>Select one...</option>
                                <option value="session_feedback" {{old('purpose') == 'session_feedback' ? "SELECTED" : ""}}>About sessions organization</option>
                                <option value="website_feedback" {{old('purpose') == 'website_feedback' ? "SELECTED" : ""}}>About the website</option>
                            </select>
                            @error('purpose')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message"
                                   class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-2">
                                Your Message
                            </label>
                            <textarea name="message" id="message" rows="5" required
                                      placeholder="Describe your issue or suggestion in detail..."
                                      class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700
                                             text-gray-800 dark:text-gray-200 rounded-md p-3 focus:ring-2 focus:ring-indigo-500
                                             focus:border-transparent resize-none">{{old('message')}}</textarea>
                            @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end">
                            <x-button variant="primary">✉️ Send Message</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
