@props([
    'url',
    'color' => 'primary'
])

@php
    // Define brand color
    $backgroundColor = '#4f46e5'; // Your purple
    $textColor = '#ffffff';

    // For success / error notifications Laravel uses
    $colors = [
        'success' => ['#16a34a', '#ffffff'],
        'error'   => ['#dc2626', '#ffffff'],
    ];

    if (isset($colors[$color])) {
        [$backgroundColor, $textColor] = $colors[$color];
    }
@endphp

<table role="presentation" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td style="border-radius:8px; overflow:hidden;">
            <a href="{{ $url }}"
               style="
                    display:inline-block;
                    background-color: {{ $backgroundColor }};
                    color: {{ $textColor }};
                    padding: 12px 24px;
                    font-size: 15px;
                    font-weight: 600;
                    text-decoration: none;
                    border-radius:8px;
               ">
                {{ $slot }}
            </a>
        </td>
    </tr>
</table>
