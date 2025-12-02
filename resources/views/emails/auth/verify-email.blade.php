@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation"
           style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                    👋 Welcome, {{ $user->name }}!
                </h1>

                <p style="margin:0 0 16px 0;">
                    We're excited to have you as part of the <strong>Iași Board Gaming Community</strong>!
                </p>

                <p style="margin:0 0 24px 0;">
                    Before we can fully welcome you in, please take a quick moment to verify your email address.
                    This step helps us keep the community safe and ensure that all communications reach the right place.
                </p>

                @component('mail::button', ['url' => $url])
                    Verify My Email
                @endcomponent

                <p style="margin:28px 0 16px 0;">
                    Once verified, you’ll be able to:
                </p>

                <ul style="margin:0 0 24px 20px; color:#111827; line-height:1.6;">
                    <li>🎲 Join and register for game sessions.</li>
                    <li>💬 Interact with other players and organizers.</li>
                    <li>📅 Receive updates and reminders for upcoming games.</li>
                </ul>

                <p style="margin:0 0 24px 0;">
                    If you didn’t request this email, you can safely ignore it — no changes will be made to your account.
                </p>

                <p style="margin-top:24px;">
                    Thanks for joining us,<br>
                    <strong>The Iași Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
    @component('mail::footer')
    @endcomponent
@endcomponent
