@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">📋 Don’t Forget to Finalize Your Game Session</h1>

                <p style="margin:0 0 16px 0;">Hey <strong>{{ $user->name }}</strong>,</p>

                <p style="margin:0 0 24px 0;">
                    It looks like your board game session — <strong>{{ $session->name }}</strong> — has already taken place,
                    but it hasn’t been finalized yet.
                </p>

                <p style="margin:0 0 24px 0;">
                    Please take a moment to mark how the session went — <strong>Success or Fail</strong> —
                    record who participated or was absent, and leave a short note about how things went.
                    <br><br>
                    This helps the community stay organized and celebrate great game nights 🎲
                </p>

                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f9fafb; border-radius:8px; padding:16px 20px; margin-bottom:24px;">
                    <tr><td>🎲 <strong>Game:</strong> {{ $session->name }}</td></tr>
                    <tr><td>📅 <strong>Date:</strong> {{ $session->start_at?->format('l, F jS, Y') ?? 'TBD' }}</td></tr>
                    <tr><td>🕒 <strong>Time:</strong> {{ $session->start_at?->format('H:i') ?? 'TBD' }}</td></tr>
                    <tr><td>📍 <strong>Location:</strong> {{ $session->location ?? 'Not specified' }}</td></tr>
                </table>

                @component('mail::button', ['url' => $mainButtonLink])
                    Finalize Session
                @endcomponent

                <p style="margin-top:28px;">
                    Thank you for your time and for helping keep the community organized!<br>
                    Every session record helps everyone stay informed and improves future events.
                </p>

                <p style="margin-top:20px;">
                    With appreciation,<br>
                    <strong>The Iasi Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>

    @component('mail::footer')
    @endcomponent
@endcomponent
