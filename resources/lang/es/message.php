<?php

return [
    "title" => "Notas",
    "single" => "Nota",
    "group" => "Contenido",
    "pages" => [
        "groups" => "Gestionar Grupos de Notas",
        "status" => "Gestionar Estados de Notas"
    ],
    "columns" => [
        "title" => "Titulo",
        "body" => "Cuerpo",
        "date" => "Fecha",
        "time" => "Hora",
        "is_pined" => "Fijado",
        "is_public" => "Publico",
        "icon" => "Icono",
        "background" => "Fondo",
        "border" => "Borde",
        "color" => "Color",
        "font_size" => "Tamaño de Fuente",
        "font" => "Fuente",
        "group" => "Grupo",
        "status" => "Estado",
        "user_id" => "Id de Usuario",
        "user_type" => "Tipo de Usuario",
        "model_id" => "Id del Modelo",
        "model_type" => "Tipo de Modelo",
        "created_at" => "Creado en",
        "updated_at" => "Actualizado en"
    ],
    "tabs" => [
        "general" => "General",
        "style" => "Estilo"
    ],
    "actions" => [
        "view" => "Ver",
        "edit" => "Editar",
        "delete" => "Eliminar",
        "notify" => [
            "label" => "Notificar Usuario",
            "notification" => [
                "title" => "Notificación Enviada",
                "body" => "La notificación ha sido enviada."
            ]
        ],
        "share" => [
            "label" => "Compartir Nota",
            "notification" => [
                "title" => "Enlace de Compartir Creado",
                "body" => "El enlace de compartir ha sido creado y copiado al portapapeles"
            ]
        ],
        "user_access" => [
            "label" => "Acceso de Usuario",
            "form" => [
                "model_id" => "Usuarios",
                "model_type" => "Tipo de Usuario",
            ],
            "notification" => [
                "title" => "Acceso de Usuario Actualizado",
                "body" => "El acceso de usuario ha sido actualizado."
            ]
        ],
        "checklist"=> [
            "label" => "Adicionar Lista de Tareas",
            "form" => [
                "checklist"=> "Lista de Tareas"
            ],
            "state" => [
                "done" => "Hecho",
                "pending" => "Pendiente"
            ],
            "notification" => [
                "title" => "Lista de Tareas Actualizada",
                "body" => "La lista de tareas ha sido actualizada.",
                "updated" => [
                    "title" => "Elemento de Lista de Tareas Actualizado",
                    "body" => "El elemento de la lista de tareas ha sido actualizado."
                ],
            ]
        ]
    ],
    "notifications" => [
        "edit" => [
            "title" => "Nota Actualizada",
            "body" => "La nota ha sido actualizada."
        ],
        "delete" => [
            "title" => "Nota Eliminada",
            "body" => "La nota ha sido eliminada."
        ]
    ]
];
