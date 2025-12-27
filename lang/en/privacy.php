<?php

return [
    'title' => 'Privacy Policy',
    'last_updated' => 'Last updated: :date',

    'sections' => [
        'info_collect' => [
            'title' => '1. Information We Collect',
            'content' => [
                'intro' => 'We collect the following types of personal data to provide and secure access to our community platform:',
                'list' => [
                    'name' => '<strong>Name</strong> — visible to all users.',
                    'contact' => '<strong>Any contact information</strong> (such as email address, phone number, or social handle) — visible only to admins and organizers.',
                    'image' => '<strong>Profile image</strong> — visible to all users.',
                    'activity' => '<strong>Activity information</strong> such as session participation, comments, requests, and notification settings.',
                    'tech' => '<strong>Technical data</strong> such as IP address and browser user agent, collected only for security and spam prevention during self-initiated join requests.',
                    'auth_tokens' => '<strong>Authentication tokens</strong> — temporary secure tokens included in “magic login” emails that allow password-less access. These are time-limited, encrypted, and expire automatically.',
                ],
                'note' => 'We do not collect unnecessary or hidden personal data. For organizer-invited users, no technical data (IP or user agent) is recorded.',
            ],
        ],

        'use' => [
            'title' => '2. How We Use Your Information',
            'text' => 'We use your data to operate and improve the platform, manage game sessions, send notifications and reminders, verify your identity, and ensure security. Technical data (IP and user agent) is used exclusively for fraud detection, abuse prevention, and debugging purposes. We also send secure “magic login” emails to facilitate password-less authentication — these contain temporary signed tokens that expire automatically after a few days and are used solely for login security.',
        ],

        'legal' => [
            'title' => '3. Legal Basis (GDPR)',
            'intro' => 'We process data under the following lawful bases:',
            'list' => [
                'consent' => '<strong>Consent</strong> — when you request to join or subscribe to notifications.',
                'legit' => '<strong>Legitimate interest</strong> — to maintain platform security and prevent abuse.',
                'contract' => '<strong>Contractual necessity</strong> — to provide access to registered members and operate the service.',
            ],
        ],

        'visibility' => [
            'title' => '4. Who Can See Your Information',
            'list' => [
                'users' => '<strong>Visible to all users:</strong> name, profile photo, participation status.',
                'admins' => '<strong>Visible to admins and organizers:</strong> email address, activity logs, and join/invite status.',
                'external' => '<strong>Not shared externally:</strong> your personal data is never sold, shared, or used for marketing or third-party analytics.',
            ],
        ],

        'retention' => [
            'title' => '5. Data Retention',
            'text' => 'Your information is kept as long as you are part of the community. When you delete your account, all related personal data is permanently removed from our systems, except where retention is required by law or for legitimate administrative reasons (e.g., abuse prevention logs, kept up to 90 days).',
        ],

        'security' => [
            'title' => '6. Security Measures',
            'text' => 'We use Laravel framework security features, AWS EC2 hosting protections, encrypted storage, HTTPS, and strict access controls. Only authorized admins and organizers can view sensitive information. While no system can guarantee absolute security, we follow current best practices to safeguard your data against unauthorized access or misuse.',
        ],

        'rights' => [
            'title' => '7. Your Rights',
            'text' => 'You have the right to access, correct, delete, or export your personal data at any time. You may also withdraw consent or edit your notification preferences in your account settings. To exercise these rights, contact us at <strong>:email</strong>.',
        ],

        'cookies' => [
            'title' => '8. Cookies',
            'text' => 'We use only essential cookies required for login sessions, authentication, language preference, and security. No advertising, analytics, or third-party tracking cookies are used on this site.',
        ],

        'contact' => [
            'title' => '9. Contact Us',
            'text' => 'If you have questions or requests regarding your personal data or this policy, contact us at <strong>:email</strong>.',
        ],
    ],
];
