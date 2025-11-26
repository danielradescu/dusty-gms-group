@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation"
           style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                    🔐 Reset Your Password
                </h1>

                <p style="margin:0 0 16px 0;">
                    Hey <strong>{{ $user->name }}</strong>,
                </p>

                <p style="margin:0 0 24px 0;">
                    It looks like you requested to reset your password. No worries — you can set a new one by clicking the button below:
                </p>

                @component('mail::button', ['url' => $url])
                    Reset Password
                @endcomponent

                <p style="margin:28px 0 16px 0;">
                    For security reasons, this link will expire in <strong>{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }}</strong> minutes.
                </p>

                <p style="margin:0 0 24px 0;">
                    If you didn’t request a password reset, you can safely ignore this message.
                    Your current password will remain unchanged.
                </p>

                <p style="margin-top:24px;">
                    Stay secure,<br>
                    <strong>The Iasi Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
@endcomponent
