@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">✅ You’ve Been Auto-Joined!</h1>

                <p style="margin:0 0 16px 0;">Hey <strong>{{ $user->name }}</strong>,</p>

                <p style="margin:0 0 24px 0;">
                    You’ve been automatically added to the first available board game session on <strong>{{ \Carbon\Carbon::parse($targetDate)->format('l, F jS, Y') }}</strong> — awesome!
                </p>

                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f9fafb; border-radius:8px; padding:16px 20px; margin-bottom:24px;">
                    <tr><td>🎲 <strong>Game:</strong> {{ $session->name }}</td></tr>
                    <tr><td>📅 <strong>Date:</strong> {{ $session->start_at?->format('l, F jS, Y') ?? 'TBD' }}</td></tr>
                    <tr><td>🕒 <strong>Time:</strong> {{ $session->start_at?->format('H:i') ?? 'TBD' }}</td></tr>
                    <tr><td>📍 <strong>Location:</strong> {{ $session->location ?? 'Not specified' }}</td></tr>
                </table>

                <p style="margin-top:20px;">
                    You don’t need to confirm your attendance — this has already been done automatically.<br>
                    If you don’t plan to join, update your status so others can take your spot.
                </p>

                @component('mail::button', ['url' => $mainButtonLink])
                    View or Update Attendance
                @endcomponent

                <p style="margin-top:28px;">
                    Thanks for being part of the community,<br>
                    <strong>The Iași Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
    @component('mail::footer', ['unsubscribeLink' => $unsubscribeLink])
    @endcomponent
@endcomponent
