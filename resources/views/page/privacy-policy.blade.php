<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Privacy Policy') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-10 text-gray-800 dark:text-gray-200">
            <section class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 leading-relaxed">
                <h1 class="text-3xl font-bold mb-6">Privacy Policy</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-10">
                    Last updated: 24 November 2025
                </p>

                <div class="space-y-8 leading-relaxed">

                    <section>
                        <h2 class="text-xl font-semibold mb-2">1. Information We Collect</h2>
                        <p>We collect the following types of personal data to provide and secure access to our community platform:</p>
                        <ul class="list-disc ml-6 mt-2 space-y-1">
                            <li><strong>Name</strong> — visible to all users.</li>
                            <li><strong>Any contact information</strong> (such as email address, phone number, or social handle) — visible only to admins and organizers.</li>
                            <li><strong>Profile image</strong> — visible to all users.</li>
                            <li><strong>Activity information</strong> such as session participation, comments, requests, and notification settings.</li>
                            <li><strong>Technical data</strong> such as IP address and browser user agent, collected only for security and spam prevention during self-initiated join requests.</li>
                        </ul>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            We do not collect unnecessary or hidden personal data. For organizer-invited users, no technical data (IP or user agent) is recorded.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">2. How We Use Your Information</h2>
                        <p>We use your data to operate and improve the platform, manage game sessions, send notifications and reminders, verify your identity, and ensure security. Technical data (IP and user agent) is used exclusively for fraud detection, abuse prevention, and debugging purposes.</p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">3. Legal Basis (GDPR)</h2>
                        <p>We process data under the following lawful bases:</p>
                        <ul class="list-disc ml-6 mt-2 space-y-1">
                            <li><strong>Consent</strong> — when you request to join or subscribe to notifications.</li>
                            <li><strong>Legitimate interest</strong> — to maintain platform security and prevent abuse.</li>
                            <li><strong>Contractual necessity</strong> — to provide access to registered members and operate the service.</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">4. Who Can See Your Information</h2>
                        <ul class="list-disc ml-6 space-y-1">
                            <li><strong>Visible to all users:</strong> name, profile photo, participation status.</li>
                            <li><strong>Visible to admins and organizers:</strong> email address, activity logs, and join/invite status.</li>
                            <li><strong>Not shared externally:</strong> your personal data is never sold, shared, or used for marketing or third-party analytics.</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">5. Data Retention</h2>
                        <p>Your information is kept as long as you are part of the community. When you delete your account, all related personal data is permanently removed from our systems, except where retention is required by law or for legitimate administrative reasons (e.g., abuse prevention logs, kept up to 90 days).</p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">6. Security Measures</h2>
                        <p>
                            We use Laravel framework security features, AWS EC2 hosting protections, encrypted storage, HTTPS, and strict access controls.
                            Only authorized admins and organizers can view sensitive information. While no system can guarantee absolute security,
                            we follow current best practices to safeguard your data against unauthorized access or misuse.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">7. Your Rights</h2>
                        <p>
                            You have the right to access, correct, delete, or export your personal data at any time.
                            You may also withdraw consent or edit your notification preferences in your account settings.
                            To exercise these rights, contact us at <strong>{{ config('app.contact_email') }}</strong>.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">8. Cookies</h2>
                        <p>We use only essential cookies required for login sessions, authentication, language preference and security.
                            No advertising, analytics, or third-party tracking cookies are used on this site.</p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">9. Contact Us</h2>
                        <p>If you have questions or requests regarding your personal data or this policy, contact us at <strong>{{ config('app.contact_email') }}</strong>.</p>
                    </section>

                </div>
            </section>
        </div>
    </div>
</x-app-layout>
