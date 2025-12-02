@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                    💬 New Comment on Your Game Session
                </h1>

                <p style="margin:0 0 16px 0;">Hey <strong>{{ $organizer->name }}</strong>,</p>

                <p style="margin:0 0 24px 0;">
                    Someone just added a new comment in the game session you’re organizing —
                    <strong>{{ $session->name }}</strong>.
                </p>

                <p style="margin:0 0 24px 0;">
                    Your participants are starting to engage and may have questions, suggestions, or updates.
                    A quick reply from you helps keep everyone informed and shows that the session is active and well-organized.
                </p>

                {{-- If the session is happening today --}}
                @if($session->start_at && $session->start_at->isToday())
                    <p style="margin:0 0 24px 0; color:#dc2626; font-weight:500;">
                        🚨 <strong>Today is the day!</strong><br>
                        This is the most important moment to stay connected — make sure everyone knows the final details like the meeting point or any last-minute changes.
                        Your communication today helps ensure the session runs smoothly and participants show up prepared.
                    </p>
                @endif

                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f9fafb; border-radius:8px; padding:16px 20px; margin-bottom:24px;">
                    <tr><td>🎲 <strong>Game:</strong> {{ $session->name }}</td></tr>
                    <tr><td>📅 <strong>Date:</strong> {{ $session->start_at?->format('l, F jS, Y') ?? 'TBD' }}</td></tr>
                    <tr><td>🕒 <strong>Time:</strong> {{ $session->start_at?->format('H:i') ?? 'TBD' }}</td></tr>
                    <tr><td>📍 <strong>Location:</strong> {{ $session->location ?? 'Not specified' }}</td></tr>
                </table>

                <p style="margin:28px 0 16px 0;">
                    💡 <em>Active communication keeps your session healthy and builds trust among players.</em><br>
                    A small follow-up message or clarification can go a long way.
                </p>

                @component('mail::button', ['url' => $mainButtonLink . '#post-comment'])
                    View Comment & Reply
                @endcomponent

                <p style="margin-top:20px;">
                    🎲 Thanks for keeping the community engaged.<br>
                    — <strong>The Iași Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
    @component('mail::footer', ['unsubscribeLink' => $unsubscribeLink])
    @endcomponent
@endcomponent
