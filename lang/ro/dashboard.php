<?php

return [
    'title' => 'Panou principal',
    'upcoming_title' => '🎲 Sesiuni de joc viitoare',
    'organized_by' => 'Organizată de',
    'unknown_organizer' => 'Necunoscut',
    'location_tbd' => 'De stabilit',
    'no_response' => 'Nu ai răspuns încă la această sesiune.',
    'hidden_until' => 'Ascunsă până la :date',
    'view_details' => 'Vezi detalii',
    'this_week_title' => 'Cererile tale pentru sesiunile din această săptămână',
    'requested_sessions_intro' => '✅ Ai solicitat sesiuni de joc pentru:',
    'no_requests' => '🎯 Nu ai trimis încă nicio cerere — ești gata de joacă? Alege ziua ideală!',

    'pick_days_title' => 'Alege zilele care te interesează.',
    'pick_days_subtitle' => 'Organizatorul stabilește ora de început. Poți ulterior solicita o modificare sau comunica ora ta de sosire.',

    'auto_join' => '⚡Particip automat',
    'notify_only' => '🔔Doar notificare',
    'not_available' => '🚫 Indisponibil',

    'join_and_notify' => '🟢 Particip și notifică-mă',
    'notify_only_label' => '🔔 Doar notificare',
    'not_available_label' => '🚫 Indisponibil',

    'auto_join_label' => '( :count participări automate)',

    'save_preferences' => '💾 Salvează preferințele',
    'preferences_hint' => 'Poți modifica aceste preferințe oricând.',

    'any_day_title' => '🌍 Notificări pentru orice zi',
    'any_day_description' => 'Primești o notificare întârziată despre <strong>orice sesiune nouă</strong> — chiar dacă nu ai selectat o zi anume.',
    'any_day_enable' => '🔔 Activează notificările pentru toate sesiunile',

    'understanding_title' => 'Înțelegerea setărilor tale de notificare:',
    'day_preferences_title' => 'Preferințele pentru zile',
    'day_preferences_description' => [
        'auto' => '🟢 <b>Particip și notifică-mă</b>: Vei fi înscris automat la prima sesiune creată în acea zi care are complexitate <b>Casual</b> sau <b>Flexibilă</b>. Vei primi în continuare notificări și pentru sesiunile <b>Competitive</b> sau oricare altele din acea zi.',
        'notify' => '🔔 <b>Doar notificare</b>: Vei primi o <b>notificare instant</b> atunci când se creează o nouă sesiune în acea zi, invitându-te să participi.',
        'none' => '🚫 <b>Indisponibil</b>: Această opțiune va <b>reseta</b> orice preferință existentă pentru acea zi — nu vei primi notificări sau înscrieri automate până nu o modifici din nou.',
        'after_join' => 'După ce te înscrii la o sesiune, setarea se va schimba automat la <b>Doar notificare</b>. Utilizatorii care au selectat o zi vor primi notificările primii, având acces prioritar la sesiuni noi.',
    ],

    'any_day_preferences_title' => 'Notificări pentru orice zi',
    'any_day_preferences_description' => [
        'main' => '🔔 <b>Notificări pentru orice zi</b>: Vei primi alerte pentru <b>orice sesiune de joc nouă</b> — chiar dacă nu ai ales o zi anume.',
        'delay' => '⏱️ Aceste notificări sunt <b>întârziate aleatoriu între 2 și 6 ore</b> după trimiterea notificărilor specifice zilei. Scopul este de a încuraja jucătorii să <b>voteze zilele preferate</b>, pentru a ajuta organizatorii să planifice mai bine.',
        'backup' => 'Poți activa această opțiune ca rezervă, pentru a nu rata nicio sesiune nouă.',
        'tip' => '💡 Această setare funcționează identic cu opțiunea <b>„Anunță-mă mereu despre sesiunile noi”</b> din <a href=":link" class="text-indigo-600 dark:text-indigo-400 hover:underline">Setările de e-mail</a>.',
    ],

];
