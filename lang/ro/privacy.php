<?php

return [
    'title' => 'Politica de confidențialitate',
    'last_updated' => 'Ultima actualizare: :date',

    'sections' => [
        'info_collect' => [
            'title' => '1. Informații colectate',
            'content' => [
                'intro' => 'Colectăm următoarele tipuri de date personale pentru a oferi și asigura accesul la platforma comunității:',
                'list' => [
                    'name' => '<strong>Nume</strong> — vizibil pentru toți utilizatorii.',
                    'contact' => '<strong>Informații de contact</strong> (precum adresa de e-mail, număr de telefon sau cont social) — vizibile doar pentru administratori și organizatori.',
                    'image' => '<strong>Imagine de profil</strong> — vizibilă pentru toți utilizatorii.',
                    'activity' => '<strong>Informații despre activitate</strong>, precum participări la sesiuni, comentarii, cereri și setări de notificări.',
                    'tech' => '<strong>Date tehnice</strong> precum adresa IP și user agent-ul browserului, colectate doar pentru securitate și prevenirea spamului în timpul cererilor de înscriere inițiate de utilizator.',
                ],
                'note' => 'Nu colectăm date personale inutile sau ascunse. Pentru utilizatorii invitați de organizatori, nu sunt înregistrate date tehnice (IP sau user agent).',
            ],
        ],

        'use' => [
            'title' => '2. Cum folosim informațiile tale',
            'text' => 'Folosim datele tale pentru a opera și îmbunătăți platforma, a gestiona sesiunile de jocuri de societate, a trimite notificări și memento-uri, a verifica identitatea și a asigura securitatea. Datele tehnice (IP și user agent) sunt folosite exclusiv pentru detectarea fraudelor, prevenirea abuzurilor și depanare. De asemenea, trimitem e-mailuri de tip „autentificare rapidă” („magic link”) pentru a permite accesul securizat fără parolă. Aceste linkuri conțin token-uri temporare, utilizate exclusiv pentru autentificare și care expiră automat după câteva zile.',
        ],

        'legal' => [
            'title' => '3. Baza legală (GDPR)',
            'intro' => 'Prelucrăm datele în baza următoarelor temeiuri legale:',
            'list' => [
                'consent' => '<strong>Consimțământ</strong> — atunci când soliciți să te alături sau te abonezi la notificări.',
                'legit' => '<strong>Interes legitim</strong> — pentru a menține securitatea platformei și a preveni abuzurile.',
                'contract' => '<strong>Necesitate contractuală</strong> — pentru a oferi acces membrilor înregistrați și a opera serviciul.',
            ],
        ],

        'visibility' => [
            'title' => '4. Cine poate vedea informațiile tale',
            'list' => [
                'users' => '<strong>Vizibile pentru toți utilizatorii:</strong> nume, fotografie de profil, statut de participare.',
                'admins' => '<strong>Vizibile pentru administratori și organizatori:</strong> adresa de e-mail, jurnalul de activitate și statutul de înscriere/invitație.',
                'external' => '<strong>Necomunicate extern:</strong> datele tale personale nu sunt vândute, partajate sau folosite în scopuri de marketing ori analiză terță parte.',
            ],
        ],

        'retention' => [
            'title' => '5. Păstrarea datelor',
            'text' => 'Informațiile tale sunt păstrate atât timp cât ești parte din comunitate. La ștergerea contului, toate datele personale asociate sunt eliminate definitiv din sistemele noastre, cu excepția cazurilor în care legea sau motivele administrative legitime impun păstrarea temporară (ex. jurnale pentru prevenirea abuzurilor, păstrate până la 90 de zile).',
        ],

        'security' => [
            'title' => '6. Măsuri de securitate',
            'text' => 'Folosim funcțiile de securitate oferite de framework-ul Laravel, protecțiile oferite de hostingul AWS EC2, stocare criptată, HTTPS și controale stricte de acces. Doar administratorii și organizatorii autorizați pot accesa informațiile sensibile. Deși niciun sistem nu poate garanta securitate absolută, urmăm cele mai bune practici actuale pentru protejarea datelor tale împotriva accesului neautorizat sau a abuzului.',
        ],

        'rights' => [
            'title' => '7. Drepturile tale',
            'text' => 'Ai dreptul de a accesa, corecta, șterge sau exporta datele tale personale în orice moment. Poți, de asemenea, să îți retragi consimțământul sau să modifici preferințele de notificare din setările contului tău. Pentru a-ți exercita aceste drepturi, contactează-ne la <strong>:email</strong>.',
        ],

        'cookies' => [
            'title' => '8. Cookie-uri',
            'text' => 'Folosim doar cookie-uri esențiale, necesare pentru autentificare, sesiuni, preferința de limbă și securitate. Nu folosim cookie-uri de publicitate, analiză sau urmărire terță parte.',
        ],

        'contact' => [
            'title' => '9. Contact',
            'text' => 'Dacă ai întrebări sau solicitări privind datele tale personale sau această politică, contactează-ne la <strong>:email</strong>.',
        ],
    ],
];
