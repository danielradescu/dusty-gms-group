@component('mail::message')
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation"
           style="font-family: Arial, sans-serif; color:#111827;">
        <tr>
            <td>
                <h1 style="font-size:22px; font-weight:700; color:#4f46e5; margin-bottom:20px;">
                    🧭 You’re Now the Organizer of a Game Session!
                </h1>

                <p style="margin:0 0 16px 0;">Hey <strong>{{ $organizer->name }}</strong>,</p>

                <p style="margin:0 0 24px 0;">
                    You’ve been assigned as the organizer for the following board game session:
                </p>

                <table cellpadding="0" cellspacing="0" border="0" width="100%"
                       style="background-color:#f9fafb; border-radius:8px; padding:16px 20px; margin-bottom:24px;">
                    <tr><td>🎲 <strong>Game:</strong> {{ $session->name }}</td></tr>
                    <tr><td>📅 <strong>Date:</strong> {{ $session->start_at?->format('l, F jS, Y') ?? 'TBD' }}</td></tr>
                    <tr><td>🕒 <strong>Time:</strong> {{ $session->start_at?->format('H:i') ?? 'TBD' }}</td></tr>
                    <tr><td>📍 <strong>Location:</strong> {{ $session->location ?? 'Not specified' }}</td></tr>
                </table>

                <p style="margin:0 0 16px 0;">
                    Depending on how this happened, it might be because:
                </p>
                <ul style="margin:0 0 24px 20px; color:#374151; padding-left:10px;">
                    <li>✅ You created this session yourself.</li>
                    <li>🔄 Another organizer assigned you to lead this session.</li>
                </ul>

                <div style="background-color:#eef2ff; border-left:4px solid #4f46e5; padding:16px 20px;
                        border-radius:8px; margin-bottom:24px;">
                    <p style="margin:0; color:#1e3a8a;"><strong>Next Steps to Follow</strong></p>
                    <ol style="margin:8px 0 0 20px; color:#111827; line-height:1.6;">
                        <li>
                            Review the session details and update any missing information
                            (location, description, etc.) — if the session is not yet confirmed.
                        </li>
                        <li>
                            Confirm the start time, location, and communicate any other updates if needed.
                        </li>
                        <li>
                            Monitor new registrations and manage participants requests.
                        </li>
                        <li>
                            Always engage with players via comments or any other means to answer questions
                            and share updates.
                        </li>
                    </ol>
                </div>

                <div style="background-color:#f9fafb; border-left:4px solid #10b981; padding:16px 20px;
                        border-radius:8px; margin-bottom:24px;">
                    <p style="margin:0; color:#065f46; font-weight:600;">📘 Understanding the Session Flow</p>
                    <p style="margin:8px 0 0 0;">
                        Here’s how every session progresses through its lifecycle:
                    </p>

                    <ul style="margin:8px 0 0 20px; color:#111827; line-height:1.6;">
                        <li>
                            <strong>Recruiting Participants:</strong>
                            The session is open for players to join. You can freely update its details,
                            but the play date is fixed.
                        </li>
                        <li>
                            <strong>Confirmed:</strong>
                            Once enough players are confirmed and the location is secured, you can confirm
                            the session. At this point, it becomes locked for edits, but players can still
                            join as long as there are open spots.
                        </li>
                        <li>
                            <strong>Updates:</strong>
                            After confirmation, you can send important announcements or reminders to
                            participants (e.g., changes in meeting point or time adjustments).
                        </li>
                        <li>
                            <strong>Canceled:/Reassign:</strong>
                            If plans change, you can cancel the session or assign a new organizer from
                            one of the participants. Everyone who confirmed will be notified automatically.
                        </li>
                    </ul>

                    <p style="margin-top:12px; color:#065f46;">
                        💡 Once a session is confirmed, no further edits can be made — but you can always
                        communicate with your players through updates or comments.
                    </p>
                </div>

                <p style="margin:0 0 16px 0;">
                    Ready to take charge? You can manage everything directly from your organizer dashboard:
                </p>

                @component('mail::button', ['url' => route('game-session.manage.edit', $session->uuid)])
                    Manage Session
                @endcomponent

                <p style="margin-top:28px;">
                    Thanks for organizing and keeping our community active!<br>
                    Your leadership makes every game session possible 🎲
                </p>

                <p style="margin-top:20px;">
                    — <strong>The Iasi Board Gaming Community Team</strong>
                </p>
            </td>
        </tr>
    </table>
@endcomponent
