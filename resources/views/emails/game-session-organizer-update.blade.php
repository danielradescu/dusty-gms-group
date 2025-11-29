@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#2563eb; margin-bottom:20px;">📢 Update from the Organizer</h1>

                <p style="margin:0 0 16px 0;">Hey <strong>{{ $user->name }}</strong>,</p>

                <p style="margin:0 0 24px 0;">
                    The organizer of your upcoming board game session has posted an update for all confirmed participants.
                </p>

                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eff6ff; border-radius:8px; padding:16px 20px; margin-bottom:24px;">
                    <tr><td>🎲 <strong>Game:</strong> {{ $session->name }}</td></tr>
                    <tr><td>📅 <strong>Date:</strong> {{ $session->start_at?->format('l, F jS, Y') ?? 'TBD' }}</td></tr>
                    <tr><td>🕒 <strong>Time:</strong> {{ $session->start_at?->format('H:i') ?? 'TBD' }}</td></tr>
                    <tr><td>📍 <strong>Location:</strong> {{ $session->location ?? 'Not specified' }}</td></tr>
                </table>

                @if(!empty($message))
                    <div style="background:#e0f2fe; border-left:4px solid #3b82f6; padding:12px 16px; border-radius:6px; margin-bottom:24px;">
                        <p style="margin:0; color:#1e3a8a;"><strong>Organizer's message:</strong></p>
                        <p style="margin:8px 0 0 0;">{{ $message }}</p>
                    </div>
                @endif

                <p style="margin-top:20px;">
                    You can view the session details and continue the discussion with the community:
                </p>

                @component('mail::button', ['url' => route('game-session.interaction.show', $session->uuid) . '#post-comment'])
                    View Game Session
                @endcomponent

                <p style="margin-top:28px;">
                    Thanks for being part of the community,<br>
                    <strong>The Iasi Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
@endcomponent
