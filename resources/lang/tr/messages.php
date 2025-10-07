<?php

return [
    "title" => "Notlar",
    "single" => "Not",
    "group" => "İçerik",
    "pages" => [
        "groups" => "Not Gruplarını Yönet",
        "status" => "Not Durumlarını Yönet"
    ],
    "columns" => [
        "title" => "Başlık",
        "body" => "İçerik",
        "date" => "Tarih",
        "time" => "Saat",
        "is_pined" => "Sabitlendi mi",
        "is_public" => "Herkese Açık mı",
        "icon" => "Simge",
        "background" => "Arka Plan",
        "border" => "Kenarlık",
        "color" => "Renk",
        "font_size" => "Yazı Boyutu",
        "font" => "Yazı Tipi",
        "group" => "Grup",
        "status" => "Durum",
        "user_id" => "Kullanıcı ID",
        "user_type" => "Kullanıcı Türü",
        "model_id" => "Model ID",
        "model_type" => "Model Türü",
        "created_at" => "Oluşturulma Tarihi",
        "updated_at" => "Güncellenme Tarihi"
    ],
    "tabs" => [
        "general" => "Genel",
        "style" => "Stil"
    ],
    "actions" => [
        "view" => "Görüntüle",
        "edit" => "Düzenle",
        "delete" => "Sil",
        "notify" => [
            "label" => "Kullanıcıya Bildir",
            "notification" => [
                "title" => "Bildirim Başarıyla Gönderildi",
                "body" => "İlgili kullanıcıya bildirim iletildi."
            ]
        ],
        "share" => [
            "label" => "Notu Paylaş",
            "notification" => [
                "title" => "Not Paylaşım Bağlantısı Oluşturuldu",
                "body" => "Notun paylaşım bağlantısı oluşturuldu ve panoya kopyalandı."
            ]
        ],
        "user_access" => [
            "label" => "Kullanıcı Erişimi",
            "form" => [
                "model_id" => "Kullanıcılar",
                "model_type" => "Kullanıcı Türü",
            ],
            "notification" => [
                "title" => "Kullanıcı Yetkileri Güncellendi",
                "body" => "İlgili kullanıcının erişim hakları başarıyla güncellendi."
            ]
        ],
        "checklist" => [
            "label" => "Kontrol Listesi Ekle",
            "form" => [
                "checklist" => "Kontrol Listesi"
            ],
            "state" => [
                "done" => "Tamamlandı",
                "pending" => "Bekliyor"
            ],
            "notification" => [
                "title" => "Kontrol Listesi Güncellendi",
                "body" => "Kontrol listesi güncellendi.",
                "updated" => [
                    "title" => "Kontrol Listesi Öğesi Güncellendi",
                    "body" => "İlgili kontrol listesi öğesi güncellendi."
                ],
            ]
        ]
    ],
    "notifications" => [
        "edit" => [
            "title" => "Not Güncellendi",
            "body" => "Not güncellendi."
        ],
        "delete" => [
            "title" => "Not Silindi",
            "body" => "Not silindi."
        ]
    ]
];
