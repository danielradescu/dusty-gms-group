<?php

return [
    'title' => 'Dashboard',
    'upcoming_title' => '🎲 Upcoming Game Sessions',
    'organized_by' => 'Organized by',
    'unknown_organizer' => 'Unknown',
    'location_tbd' => 'To be decided',
    'no_response' => 'You haven’t responded to this session yet.',
    'hidden_until' => 'Hidden until :date',
    'view_details' => 'View Details',
    'this_week_title' => 'This Week Session Request',
    'requested_sessions_intro' => '✅ You’ve requested game sessions for:',
    'no_requests' => '🎯 You have no sessions requested yet — ready to roll? Pick your ideal play day!',

    'pick_days_title' => "Pick the days you're interested in.",
    'pick_days_subtitle' => "The organizer selects the initial start time. You can later request a change or communicate your planned arrival time.",

    'auto_join' => '⚡Auto-join',
    'notify_only' => '🔔Notify only',
    'not_available' => '🚫 Not Available',

    'join_and_notify' => '🟢 Join & Notify',
    'notify_only_label' => '🔔 Notify Only',
    'not_available_label' => '🚫 Not Available',

    'auto_join_label' => '( :count auto-join)',

    'save_preferences' => '💾 Save Preferences',
    'preferences_hint' => 'You can change these preferences anytime.',

    'any_day_title' => '🌍 Any Day Notifications',
    'any_day_description' => 'Get a delayed notification about <strong>any new session</strong> — even if you haven’t selected a specific day yet.',
    'any_day_enable' => '🔔 Enable All-Session Notifications',

    'understanding_title' => 'Understanding Your Notification Settings:',
    'day_preferences_title' => 'Day Preferences',
    'day_preferences_description' => [
        'auto' => '🟢 <b>Join & Notify</b>: You’ll be auto-joined for the first session created this day that has <b>Casual</b> or <b>Flexible</b> complexity. You’ll still receive notifications for <b>Competitive</b> sessions or any other session created this day.',
        'notify' => '🔔 <b>Notify Only</b>: You’ll receive an <b>instant notification</b> whenever a new session is created for this day, inviting you to join.',
        'none' => '🚫 <b>Not Available</b>: This will <b>reset</b> any of your existing preferences for this day — you won’t receive auto-joins or notifications until you change it again.',
        'after_join' => 'After joining a session, your setting will automatically switch to <b>Notify Only</b>. Users who have selected a day will always receive notifications first, giving them early access to join or auto-join new sessions.',
    ],

    'any_day_preferences_title' => 'Any Day Notifications',
    'any_day_preferences_description' => [
        'main' => '🔔 <b>Any Day Notifications</b>: You’ll receive alerts for <b>any new game session</b> — even if you didn’t select a specific day.',
        'delay' => '⏱️ These notifications are <b>delayed by about 2 up to 6 hours randomly chosen</b> after the day-specific notifications are sent. This encourages players to <b>vote for specific days</b>, helping organizers plan better.',
        'backup' => 'You can enable this as a general backup to make sure you never miss a new session announcement.',
        'tip' => '💡 This setting works the same way as the <b>“Always notify me about new game sessions”</b> option in your <a href=":link" class="text-indigo-600 dark:text-indigo-400 hover:underline">Email Settings</a>.',
    ],

];
