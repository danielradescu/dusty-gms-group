@if (isset($unsubscribeLink) && $unsubscribeLink)
    <!-- SECURITY NOTICE -->
    <tr>
        <td style="padding:0 32px 24px 32px; color:#9ca3af; font-size:13px; line-height:1.6; text-align:center;">
            <p style="margin:0;">
                <strong>Security note:</strong> This email contains a personalized link that allows
                access to your account. Please do not share or forward this email.
            </p>
        </td>
    </tr>

    <!-- FOOTER -->
    <tr>
        <td style="background:#f9fafb; padding:20px 32px; text-align:center;
                                       font-size:12px; color:#6b7280; line-height:1.6;">

            <p style="margin:0 0 8px;">
                © {{ date('Y') }} Iași Board Gaming Community. All rights reserved.
            </p>

            <p style="margin:0;">
                You received this email because you opted in to receive updates from us.
                If you no longer wish to receive notifications, you can
                <a href="{{ $unsubscribeLink ?? '#' }}" style="color:#4f46e5; text-decoration:underline;">
                    unsubscribe here
                </a>.
            </p>

        </td>
    </tr>
@else
    <!-- FOOTER -->
    <tr>
        <td style="background:#f9fafb; padding:20px 32px; text-align:center;
                                font-size:12px; color:#6b7280; line-height:1.6;">
            <p style="margin:0 0 8px;">
                © {{ date('Y') }} Iași Board Gaming Community. All rights reserved.
            </p>

            <p style="margin:0;">
                You’re receiving this email because you interacted with the Iași Board Gaming Community
                or one of its members.
            </p>
            <p style="margin:0;">
                If you didn’t expect this message, please ignore it — no further action is needed.
            </p>
        </td>
    </tr>
@endif
