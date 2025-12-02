@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#16a34a; margin-bottom:20px;">🎯 A Spot Just Opened Up!</h1>

                <p style="margin:0 0 16px 0;">Hey <strong>{{ $user->name }}</strong>,</p>

                <p style="margin:0 0 24px 0;">
                    Good news! A spot just became available for a game session you’re interested in.
                    <br>
                    Whether you were waiting for an open seat or had set a reminder — now’s your chance to join!
                </p>

                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#ecfdf5; border-radius:8px; padding:16px 20px; margin-bottom:24px;">
                    <tr><td>🎲 <strong>Game:</strong> {{ $session->name }}</td></tr>
                    <tr><td>📅 <strong>Date:</strong> {{ $session->start_at?->format('l, F jS, Y') ?? 'TBD' }}</td></tr>
                    <tr><td>🕒 <strong>Time:</strong> {{ $session->start_at?->format('H:i') ?? 'TBD' }}</td></tr>
                    <tr><td>📍 <strong>Location:</strong> {{ $session->location ?? 'Not specified' }}</td></tr>
                </table>

                <p style="margin:0 0 16px 0;">
                    If you still want to play, grab your spot soon — seats tend to fill up quickly.
                </p>

                @component('mail::button', ['url' => $mainButtonLink])
                    Join the Session
                @endcomponent

                <p style="margin-top:28px;">
                    If you’ve changed your mind, no worries — simply ignore this message.
                    Otherwise, we hope to see you at the table 🎲
                </p>

                <p style="margin-top:20px;">
                    We can’t wait to see you at the table!<br>
                    <strong>The Iași Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
    @component('mail::footer', ['unsubscribeLink' => $unsubscribeLink])
    @endcomponent
@endcomponent
