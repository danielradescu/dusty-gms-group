@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation"
           style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:24px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                    🎉 Welcome to the Iași Board Gaming Community!
                </h1>

                <p style="margin:0 0 16px 0;">Hey <strong>{{ $joinRequest->name }}</strong>,</p>

                <p>
                    We’re excited to have you join our group of players, strategists, and board game enthusiasts.
                </p>


                <p style="margin:0 0 24px 0;">
                    To complete your registration, please click the button below.
                    You can use your current email address ({{ $joinRequest->email }}) or a different one if you prefer by clicking the "Complete Your Registration" button bellow.
                </p>

                @component('mail::button', ['url' => route('simple_register', 'invitation_token=' . $joinRequest->invitation_token)])
                    Complete Your Registration
                @endcomponent

                <div style="background-color:#f9fafb; border-left:4px solid #4f46e5; padding:16px 20px;
                        border-radius:8px; margin:28px 0;">
                    <p style="margin:0; color:#1e3a8a; font-weight:600;">What happens next:</p>
                    <ul style="margin:8px 0 0 20px; color:#111827; line-height:1.6;">
                        <li>✅ Create your account and set up your profile.</li>
                        <li>🎲 Browse upcoming board game sessions or join one that interests you.</li>
                        <li>💬 Interact with other members, comment on sessions, and confirm attendance.</li>
                        <li>📅 Receive reminders and community updates automatically.</li>
                    </ul>
                </div>

                <p style="margin:0 0 16px 0;">
                    We can’t wait to see you at the table and share some great games together!
                </p>

                <p style="margin-top:20px;">
                    — <strong>The Iași Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
    @component('mail::footer')
    @endcomponent
@endcomponent
