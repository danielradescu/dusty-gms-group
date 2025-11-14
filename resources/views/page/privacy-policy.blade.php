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
                    Last updated: {{ date('F d, Y') }}
                </p>

                <div class="space-y-8 leading-relaxed">

                    <section>
                        <h2 class="text-xl font-semibold mb-2">1. Information We Collect</h2>
                        <p>We collect the following types of personal data:</p>
                        <ul class="list-disc ml-6 mt-2 space-y-1">
                            <li><strong>Name</strong> — visible to all users.</li>
                            <li><strong>Email address</strong> — visible only to admins and organizers.</li>
                            <li><strong>Profile image</strong> — visible to all users.</li>
                            <li>Activity information such as session participation, comments, requests, and notification
                                settings.
                            </li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">2. How We Use Your Information</h2>
                        <p>We use your data to operate the platform, manage game sessions, send notifications, and
                            ensure
                            security.</p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">3. Legal Basis (GDPR)</h2>
                        <p>We process data based on your consent, legitimate interest, and contractual necessity.</p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">4. Who Can See Your Information</h2>
                        <ul class="list-disc ml-6 space-y-1">
                            <li><strong>Visible to all users:</strong> name, profile photo, participation status.</li>
                            <li><strong>Visible to admins/organizers:</strong> email address.</li>
                            <li>Your data is never sold or shared commercially.</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">5. Data Retention</h2>
                        <p>Your information is kept until you delete your account. Deleted accounts are permanently
                            removed.</p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">6. Security Measures</h2>
                        <p>
                            We use Laravel framework security features, AWS EC2 hosting security,
                            encrypted storage, and strict access controls. While no system is fully infallible,
                            we follow industry standards to protect your data.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">7. Your Rights</h2>
                        <p>You may access, correct, delete, or export your data and edit your notification preferences
                            at
                            any time.</p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">8. Cookies</h2>
                        <p>We use minimal cookies required for login and security. No advertising or tracking
                            cookies.</p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-2">9. Contact Us</h2>
                        <p>If you have questions, contact us at <strong>iasi.boardgames@gmail.com</strong>.</p>
                    </section>

                </div>
            </section>
        </div>
    </div>
</x-app-layout>
