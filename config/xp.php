<?php

return [

    'rewards' => [

        // User actions
        'check_session' => 1,
        'request_session_weekly' => 3,
        'participate_completed_successful_session' => 10,

        // Organizer actions
        'organizer_create_session' => 5,
        'organizer_completed_successful_session' => 15,
    ],

    // Linear leveling: next_level_xp = base * level
    'leveling' => [
        'base_per_level' => 100,
    ],

];
