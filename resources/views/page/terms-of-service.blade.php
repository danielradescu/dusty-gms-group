<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('terms.title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-10 text-gray-800 dark:text-gray-200">
            <section class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 leading-relaxed">
                <div class="mb-4">
                    <x-link-button class="!w-auto" href="{{ url()->previous() }}" variant="secondary">{{ __('join.form.back') }}</x-link-button>
                </div>
                <h1 class="mt-10 text-3xl font-bold mb-6">{{ __('terms.title') }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-10">
                    {{ __('terms.last_updated', ['date' => '24 November 2025']) }}
                </p>

                <div class="space-y-8 leading-relaxed">
                    @foreach (__('terms.sections') as $section)
                        <section>
                            <h2 class="text-xl font-semibold mb-2">{{ $section['title'] }}</h2>
                            <p>{!! str_replace(':email', '<strong>' . e(config('app.contact_email')) . '</strong>', $section['text']) !!}</p>
                        </section>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
