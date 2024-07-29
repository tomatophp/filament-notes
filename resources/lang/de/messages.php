<?php

return [
    "title" => "Notizen",
    "single" => "Notiz",
    "group" => "Inhalt",
    "pages" => [
        "groups" => "Notizgruppen verwalten",
        "status" => "Notizstatus verwalten"
    ],
    "columns" => [
        "title" => "Titel",
        "body" => "Inhalt",
        "date" => "Datum",
        "time" => "Zeit",
        "is_pined" => "Fixiert",
        "is_public" => "Öffentlich",
        "icon" => "Icon",
        "background" => "Hintergrund",
        "border" => "Rahmen",
        "color" => "Farbe",
        "font_size" => "Schriftgröße",
        "font" => "Schriftart",
        "group" => "Gruppe",
        "status" => "Status",
        "user_id" => "Benutzer-ID",
        "user_type" => "Benutzertyp",
        "model_id" => "Modell-ID",
        "model_type" => "Modelltyp",
        "created_at" => "Erstellt am",
        "updated_at" => "Aktualisiert am"
    ],
    "tabs" => [
        "general" => "Allgemein",
        "style" => "Stil"
    ],
    "actions" => [
        "view" => "Anzeigen",
        "edit" => "Bearbeiten",
        "delete" => "Löschen",
        "notify" => [
            "label" => "Benutzer benachrichtigen",
            "notification" => [
                "title" => "Benachrichtigung gesendet",
                "body" => "Die Benachrichtigung wurde gesendet."
            ]
        ],
        "share" => [
            "label" => "Notiz teilen",
            "notification" => [
                "title" => "Notizfreigabelink erstellt",
                "body" => "Der Notizfreigabelink wurde erstellt und in die Zwischenablage kopiert."
            ]
        ],
        "user_access" => [
            "label" => "Benutzerzugriff",
            "form" => [
                "model_id" => "Benutzer",
                "model_type" => "Benutzertyp",
            ],
            "notification" => [
                "title" => "Benutzerzugriff aktualisiert",
                "body" => "Der Benutzerzugriff wurde aktualisiert."
            ]
        ],
        "checklist"=> [
            "label" => "Checkliste hinzufügen",
            "form" => [
                "checklist"=> "Checkliste"
            ],
            "state" => [
                "done" => "Erledigt",
                "pending" => "Ausstehend"
            ],
            "notification" => [
                "title" => "Checkliste aktualisiert",
                "body" => "Die Checkliste wurde aktualisiert.",
                "updated" => [
                    "title" => "Checkliste aktualisiert",
                    "body" => "Das Checkliste-Element wurde aktualisiert."
                ],
            ]
        ]
    ],
    "notifications" => [
        "edit" => [
            "title" => "Notiz aktualisiert",
            "body" => "Die Notiz wurde aktualisiert."
        ],
        "delete" => [
            "title" => "Notiz gelöscht",
            "body" => "Die Notiz wurde gelöscht."
        ]
    ]
];
