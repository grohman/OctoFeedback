<?php


return [
    'component' => [
        'feedback' => [
            'name' => 'Обратная связь',
            'description' => 'Обработка форм обратной свяи',

            'channelCode' => [
                'title' => 'Канал',
                'description' => 'Выбрите канал'
            ],
            'successMessage' => [
                'title' => 'Кастомизированное сообщение об отправке',
                'description' => 'Не обязательно.'
            ],
            'redirectTo' => [
                'title' => 'Перенаправлять:',
                'description' => 'Укажите куда перенаправлять человека после успешной отправки формы.'
            ]
        ],

        'onSend' => [
            'success' => 'Спасибо за сообщение!',
            'error' => [
                'email' => [
                    'email' => 'Invalid email address, please provide a valid email'
                ],
                'message' => [
                    'required' => 'Отсутствует текст сообщения.'
                ]
            ]
        ]
    ],

    'backend' => [
        'feedback' => [
            'archive' => [
                'bulkSuccess' => 'Сообщения были заархивированы',
                'success' => 'Сообщение заархивировано'
            ]
        ],
        'settings' => [
            'channel' => [
                'emailDestinationComment' => 'Куда отправлять сообщения. Укажите один или несколько емейлов через запятую, или оставьте поле пустым для отправки админу сайта.',
                'preventSaveDatabase' => 'НЕ СОХРАНЯТЬ сообщения в базу',
                'warning' => 'Warning! This configuration will have no action!'
            ]
        ]
    ],

    'channel' => [
        'name' => 'Название',
        'code' => 'Код',
        'method' => 'Метод',
        'emailDestination' => 'Получатель(-ли)'
    ],
    'feedback' => [
        'name' => 'Имя',
        'email' => 'Email',
        'message' => 'Сообщение',
        'phone' => 'Телефон',
        'file' => 'Файл',
        'files' => 'Файлы',
    ],

    'mail_template' => [
        'description' => 'Шаблон для отправки писем из модуля обратной связи'
    ],

    'backmail_template' => [
        'description' => 'Шаблон для автооответчика из модуля обратной связи.'
    ],


    'permissions' => [
        'feedback' => [
            'manage' => 'Управление отзывами'
        ],
        'settings' => [
            'channel' => 'Управление каналами отзывов'
        ]
    ]
];