<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Terms of Service') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-10 text-gray-800 dark:text-gray-200">

            <section class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 leading-relaxed">
                <h1 class="text-3xl font-bold mb-6">Terms of Service</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-10">
                    Last updated: {{ date('F d, Y') }}
                </p>

                <div class="space-y-8 leading-relaxed">

                    <section>
                        <h2 class="text-xl font-semibold mb-2">1. Purpose of the Platform</h2>
                        <p>
                            This App helps local board-gaming enthusiasts join sessions, show interest, request
                            sessions,
                            and interact with organizers in a friendly community environment.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">2. Eligibility</h2>
                        <p>
                            You must be at least 16 years old or have guardian consent to use the App.
                            You agree to provide accurate information and follow community guidelines.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">3. User Accounts</h2>
                        <p>
                            You must provide a real name and a valid email address. Your profile image is optional.
                            You are responsible for keeping your account secure.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">4. Acceptable Use</h2>
                        <p>You agree not to misuse the platform. This includes harassment, impersonation, or disruptive
                            activity.</p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">5. Content You Provide</h2>
                        <p>
                            You must have the right to post the content you upload. We may remove content that violates
                            these Terms.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">6. Session Participation</h2>
                        <p>
                            Game sessions are hosted by community volunteers. Attendance is at your own risk.
                            We are not liable for issues at in-person events.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">7. Notifications</h2>
                        <p>
                            The App may send session-related notifications. You may choose to disable them at any time,
                            but then must check the App manually for updates.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">8. Privacy and Data</h2>
                        <p>
                            We collect minimal personal data. For details, please review our Privacy Policy.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">9. Availability and Changes</h2>
                        <p>
                            The App may experience downtime, and features may change. Continued use constitutes
                            acceptance
                            of updated Terms.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">10. Liability</h2>
                        <p>
                            The App is provided “as is.” We are not liable for misuse, outages, user content, or
                            real-world
                            event issues.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">11. Account Deletion</h2>
                        <p>
                            You can delete your account at any time. Your personal data will be removed.
                            Comments may remain anonymously.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">12. Contact</h2>
                        <p>
                            If you have any questions regarding these Terms, email us at:
                            <strong>iasi.boardgames@gmail.com</strong>.
                        </p>
                    </section>

                </div>
            </section>
        </div>
    </div>
</x-app-layout>
