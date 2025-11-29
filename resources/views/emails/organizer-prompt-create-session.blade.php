@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                    📅 Players Want to Play on {{ \Carbon\Carbon::parse($targetDate)->format('l, F jS, Y') }}
                </h1>

                <p style="margin:0 0 16px 0;">Hey <strong>{{ $organizer->name }}</strong>,</p>

                <p style="margin:0 0 24px 0;">
                    Several players have shown interest in joining a board game session on
                    <strong>{{ \Carbon\Carbon::parse($targetDate)->format('l, F jS, Y') }}</strong> —
                    at least <strong>{{ $interestedCount }}</strong> people are looking to play!
                </p>

                <div style="background-color:#f9fafb; border-radius:8px; padding:16px 20px; margin-bottom:24px;">
                    <p style="margin:0; color:#374151;">
                        💡 <strong>Here's your chance to organize something fun!</strong>
                    </p>
                    <p style="margin:8px 0 0 0;">
                        If you have a game in mind, consider creating a session for that date to match the players’ demand.
                        Before doing so, please check whether another organizer has already scheduled a session for that day
                        — coordinating helps us avoid overlaps and ensures a better experience for everyone.
                    </p>
                </div>

                @component('mail::button', ['url' => route('game-session.create')])
                    Create a Session
                @endcomponent

                <p style="margin-top:28px;">
                    Thanks for helping keep our community active and organized!
                    Your effort makes all the difference 🎲
                </p>

                <p style="margin-top:20px;">
                    — <strong>The Iasi Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
@endcomponent
