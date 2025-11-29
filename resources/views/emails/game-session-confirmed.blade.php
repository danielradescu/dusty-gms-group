@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation"
           style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#16a34a; margin-bottom:20px;">
                    ✅ Game Session Confirmed!
                </h1>

                <p style="margin:0 0 16px 0;">
                    Hey <strong>{{ $user->name }}</strong>,
                </p>

                <p style="margin:0 0 24px 0;">
                    Great news! The game session you signed up for has been <strong>officially confirmed</strong> by the organizer.
                    Everything is set — get ready for an awesome board gaming experience!
                </p>

                <table cellpadding="0" cellspacing="0" border="0" width="100%"
                       style="background-color:#f0fdf4; border-radius:8px; padding:16px 20px; margin-bottom:24px;">
                    <tr><td>🎲 <strong>Name:</strong> {{ $session->name }}</td></tr>
                    <tr><td>📅 <strong>Date:</strong> {{ $session->start_at?->format('l, F jS, Y') ?? 'TBD' }}</td></tr>
                    <tr><td>🕒 <strong>Time:</strong> {{ $session->start_at?->format('H:i') ?? 'TBD' }}</td></tr>
                    <tr><td>📍 <strong>Location:</strong> {{ $session->location ?? 'To be announced' }}</td></tr>
                    <tr><td>🧠 <strong>Complexity:</strong> {{ $session->complexity?->label() ?? 'N/A' }}</td></tr>
                    @if(!empty($session->description))
                        <tr><td>ℹ️ <strong>Description:</strong> {{ $session->description }}</td></tr>
                    @endif
                </table>

                @component('mail::button', ['url' => route('game-session.interaction.show', $session->uuid)])
                    View Session Details
                @endcomponent

                <p style="margin:28px 0 16px 0;">
                    Please make sure you can still attend.
                    If something comes up, update your participation status so another player can take your spot.
                </p>

                <p style="margin:0 0 24px 0;">
                    Bring your best strategy, a good mood, and get ready for a fun gaming session! 🎲
                </p>

                <p style="margin-top:24px;">
                    See you at the table,<br>
                    <strong>The Iasi Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
@endcomponent
