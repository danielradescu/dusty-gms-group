<?php

return [

    'rewards' => [

        // User actions
        'check_session' => 1, //day
        'interacted_with_game_session' => 2, //day
        'comment_session' => 1, //day
        'request_session_weekly' => 3, //week
        'participate_completed_successful_session' => 10, //every time

        // Organizer actions
        'organizer_create_session' => 5, //every time
        'organizer_completed_successful_session' => 15, //every time
    ],

    // Linear leveling: next_level_xp = base * level
    'leveling' => [
        'base_per_level' => 15,
    ],

];
