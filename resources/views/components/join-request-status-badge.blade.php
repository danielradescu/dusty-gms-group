@php

    $status = strtolower($status);
    $styles = match($status) {
        'approved' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200 border-green-300 dark:border-green-600',
        'declined' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200 border-red-300 dark:border-red-600',
        'contacted' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200 border-blue-300 dark:border-blue-600',
        default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200 border-yellow-300 dark:border-yellow-600',
    };

    $labels = [
        'approved' => 'Approved',
        'declined' => 'Rejected',
        'contacted' => 'Contacted',
        'pending' => 'Pending',
    ];
@endphp

<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border {{ $styles }}">
    {{ $labels[$status] ?? ucfirst($status) }}
</span>
