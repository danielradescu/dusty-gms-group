@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">🎲 New Game Session Created!</h1>

                <p style="margin:0 0 16px 0;">Hey <strong>{{ $user->name }}</strong>,</p>

                <p style="margin:0 0 24px 0;">
                    A new board game session has just been scheduled!<br>
                    Here are the details:
                </p>

                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f9fafb; border-radius:8px; padding:16px 20px; margin-bottom:24px;">
                    <tr><td>🎲 <strong>Game:</strong> {{ $session->name }}</td></tr>
                    <tr><td>📅 <strong>Date:</strong> {{ $session->start_at?->format('l, F jS, Y') ?? 'TBD' }}</td></tr>
                    <tr><td>🕒 <strong>Time:</strong> {{ $session->start_at?->format('H:i') ?? 'TBD' }}</td></tr>
                    <tr><td>📍 <strong>Location:</strong> {{ $session->location ?? 'Not specified' }}</td></tr>
                    <tr><td>🧠 <strong>Complexity:</strong> {{ $session->complexity?->label() . ' - ' . $session->complexity?->description() ?? 'N/A' }}</td></tr>

                    @if(!empty($session->description))
                        <tr><td>ℹ️ <strong>Description:</strong> {{ $session->description }}</td></tr>
                    @endif
                </table>

                @component('mail::button', ['url' => route('game-session.interaction.show', $session->uuid)])
                    View Game Session
                @endcomponent

                <p style="margin-top:28px;">
                    Get ready to play, strategize, and have fun with the community!<br>
                    We look forward to seeing you there 🎲
                </p>

                <p style="margin-top:20px;">
                    Thanks for being part of the community,<br>
                    <strong>The Iasi Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
@endcomponent
