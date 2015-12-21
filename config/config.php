<?php

    return [
        'validation' => [
            'name' => 'string|required',
            'email' => 'required|email',
            'message' => 'required',
            'channel_id' => 'integer|required',
            //'extra.product' => 'string|required',
        ],
        'fields' => [
            'id' => [
                'label' => '#',
                'disabled' => true
            ],
            'name' => [
                'label' => 'Имя',
            ],
            'email' => [
                'label' => 'Email',
            ],
            /*
             'extra.product' => [
                'label' => 'Продукт',
                'type' => 'partial',
                'path' => '$/idesigning/feedback/controllers/feedbacks/_extra_field.htm',
                'valueFrom' => 'product',
            ],
            */
            'message' => [
                'label' => 'Сообщение',
                'type' => 'textarea',
            ],
            'files' => [
                'label' => 'Файлы',
                'type' => 'partial',
            ]
        ],
        'columns' => [
            'id' => [
                'label' => '#',
                'searchable' => true
            ],
            'name' => [
                'label' => 'Имя',
                'searchable' => true,
            ],
            'email' => [
                'label' => 'Email',
                'searchable' => true,
            ],
            'phone' => [
                'label' => 'Телефон',
                'searchable' => true
            ],
            /*
            'extra.product' => [
                'label' => 'Продукт',
                'type' => 'partial',
                'path' => '$/idesigning/feedback/controllers/feedbacks/_extra_column.htm',
                'valueFrom' => 'product',
            ],
            */
            'channel' => [
                'label' => 'Канал',
                'relation' => 'channel',
                'select' => 'name',
            ],
            'created_at' => [
                'label' => 'Дата',
                'searchable' => true,
                'type' => 'datetime',
                'format' => 'd.m.Y H:i',
            ]
        ],
    ];