@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#dc2626; margin-bottom:20px;">❌ Game Session Canceled</h1>

                <p style="margin:0 0 16px 0;">Hey <strong>{{ $user->name }}</strong>,</p>

                <p style="margin:0 0 24px 0;">
                    Unfortunately, the board game session you were interested in (or had confirmed for) has been canceled.<br>
                    Here are the details for your reference:
                </p>

                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#fef2f2; border-radius:8px; padding:16px 20px; margin-bottom:24px;">
                    <tr><td>🎲 <strong>Game:</strong> {{ $session->name }}</td></tr>
                    <tr><td>📅 <strong>Date:</strong> {{ $session->start_at?->format('l, F jS, Y') ?? 'TBD' }}</td></tr>
                    <tr><td>🕒 <strong>Time:</strong> {{ $session->start_at?->format('H:i') ?? 'TBD' }}</td></tr>
                    @if(!empty($session->note))
                        <tr><td>ℹ️ <strong>Organizer note:</strong> {{ $session->note }}</td></tr>
                    @endif
                </table>

                <p style="margin-top:28px;">
                    We’re sorry for any inconvenience caused. Please check our platform for other available sessions — we’d love to see you join another game soon!
                </p>

                @component('mail::button', ['url' => route('game-session.interaction.show', $session->uuid)])
                    View Game Session
                @endcomponent

                <p style="margin-top:28px;">
                    Thank you for your understanding,<br>
                    <strong>The Iasi Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
@endcomponent
