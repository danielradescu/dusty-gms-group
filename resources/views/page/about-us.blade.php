<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('about.title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-10 text-gray-800 dark:text-gray-200">


            <section class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 leading-relaxed">
                <div class="mb-4">
                    <x-link-button class="!w-auto" href="{{ url()->previous() }}" variant="secondary">{{ __('join.form.back') }}</x-link-button>
                </div>
                <h3 class="mt-10 text-2xl font-semibold mb-3">{{ __('about.sections.hobby.title') }}</h3>
                <p class="text-gray-700 dark:text-gray-300">
                    {{ __('about.sections.hobby.paragraphs.p1') }}
                </p>
                <p class="mt-3 text-gray-700 dark:text-gray-300">
                    {{ __('about.sections.hobby.paragraphs.p2') }}
                </p>

                <h3 class="mt-10 text-2xl font-semibold mb-3">{{ __('about.sections.app_purpose.title') }}</h3>

                <p class="text-gray-700 dark:text-gray-300">
                    {{ __('about.sections.app_purpose.paragraphs.p1') }}
                </p>

                <ul class="list-disc pl-5 mt-4 space-y-2 text-gray-700 dark:text-gray-300">
                    <li>{!! __('about.sections.app_purpose.list.join') !!}</li>
                    <li>{!! __('about.sections.app_purpose.list.interest') !!}</li>
                    <li>{!! __('about.sections.app_purpose.list.request') !!}</li>
                </ul>

                <p class="mt-4 text-gray-700 dark:text-gray-300">
                    {{ __('about.sections.app_purpose.paragraphs.p2') }}
                </p>
            </section>
        </div>
    </div>
</x-app-layout>
