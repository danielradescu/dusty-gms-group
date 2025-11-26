<!DOCTYPE html>
<html lang="en" style="margin:0; padding:0;">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name') }} Email</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, sans-serif;">

<!-- Outer Container -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
       style="background-color:#f3f4f6; padding:30px 0;">
    <tr>
        <td align="center">

            <!-- Email Card -->
            <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0"
                   style="background:#ffffff; border-radius:12px; overflow:hidden;
                              box-shadow:0 2px 10px rgba(0,0,0,0.06);">

                <!-- HEADER -->
                <tr>
                    <td style="background:#4f46e5; padding:24px; text-align:center;">
                        <h1 style="margin:0; color:white; font-size:22px; font-weight:600;">
                            Iasi Board Gaming Community
                        </h1>
                    </td>
                </tr>

                <!-- BODY -->
                <tr>
                    <td style="padding:32px; color:#111827; font-size:15px; line-height:1.6;">

                        {!! $slot !!}
                    </td>
                </tr>

                <!-- SUBCOPY -->
                @isset($subcopy)
                    <tr>
                        <td style="padding:0 32px 24px 32px; color:#6b7280; font-size:13px; line-height:1.6;">
                            {{ $subcopy }}
                        </td>
                    </tr>
                @endisset

                <!-- FOOTER -->
                <tr>
                    <td style="background:#f9fafb; padding:20px 32px; text-align:center;
                                   font-size:12px; color:#6b7280; line-height:1.6;">

                        <p style="margin:0 0 8px;">
                            © {{ date('Y') }} Iasi Board Gaming Community. All rights reserved.
                        </p>

                        <p style="margin:0;">
                            You received this email because you opted in to receive updates from us.
                            If you no longer wish to receive notifications, you can
                            <a href="{{ $unsubscribeUrl ?? '#' }}" style="color:#4f46e5; text-decoration:underline;">
                                unsubscribe here
                            </a>.
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
