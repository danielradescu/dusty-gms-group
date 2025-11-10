@if ($errors->any())
    <div class="mb-4 rounded-md bg-red-50 p-4 border border-red-200">
        <div class="flex">
            <div class="shrink-0">
                <!-- optional icon -->
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3 text-sm text-red-700">
                <p class="font-semibold">Please fix the following issues:</p>
                <ul class="mt-2 list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
