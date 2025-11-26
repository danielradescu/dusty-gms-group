@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                    💬 New Comment on Your Game Session
                </h1>

                <p style="margin:0 0 16px 0;">Hey <strong>{{ $organizer->name }}</strong>,</p>

                <p style="margin:0 0 24px 0;">
                    A new comment was just added to your board game session:
                </p>

                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f9fafb; border-radius:8px; padding:16px 20px; margin-bottom:24px;">
                    <tr><td>🎲 <strong>Game:</strong> {{ $session->name }}</td></tr>
                    <tr><td>📅 <strong>Date:</strong> {{ $session->start_at?->format('l, F jS, Y') ?? 'TBD' }}</td></tr>
                    <tr><td>🕒 <strong>Time:</strong> {{ $session->start_at?->format('H:i') ?? 'TBD' }}</td></tr>
                    <tr><td>📍 <strong>Location:</strong> {{ $session->location ?? 'Not specified' }}</td></tr>
                </table>

                <div style="background:#eef2ff; border-left:4px solid #4f46e5; padding:12px 16px; border-radius:6px; margin-bottom:24px;">
                    <p style="margin:0; color:#1e3a8a;">
                        <strong>{{ $comment->user->name }}</strong>
                        <span style="font-weight:400; color:#6b7280;">
                        · {{ $comment->created_at->format('l, M j, Y · H:i') }}
                    </span>
                    </p>
                    <p style="margin:8px 0 0 0; color:#111827;">“{{ $comment->body }}”</p>
                </div>

                <p style="margin-top:20px;">
                    You can reply directly on the session page to keep the discussion going:
                </p>

                @component('mail::button', ['url' => route('game-session.interaction.show', $session->uuid) . '#post-comment'])
                    View Comment & Reply
                @endcomponent

                <p style="margin-top:28px;">
                    Keeping the conversation active helps everyone stay informed and excited!
                </p>

                <p style="margin-top:20px;">
                    🎲 Thanks for keeping the community engaged.<br>
                    — <strong>The Iasi Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
@endcomponent
