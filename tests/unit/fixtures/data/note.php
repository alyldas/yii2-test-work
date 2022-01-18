<?php

return [
    [
        'title' => 'Past note',
        'text' => 'Заметка с датой создания и публикации в далеком прошлом',
        'author_id' => 1,
        'created_at' => time(),
        'updated_at' => time(),
        'published_at' => time() - 60 * 60 * 24 * 30, // a month ago
    ],
    [
        'title' => 'Sample note 1',
        'text' => 'Заметка с датой публикации в недалеком будущем',
        'author_id' => 1,
        'created_at' => 1642510010,
        'updated_at' => 1642510010,
        'published_at' => 1642517210,
    ],
    [
        'title' => 'Sample note 2',
        'text' => 'Заметка с датой публикации в недалеком прошлом',
        'author_id' => 2,
        'created_at' => 1642510010,
        'updated_at' => 1642510010,
        'published_at' => 1642502810,
    ],
    [
        'title' => 'Sample note 3',
        'text' => 'Заметка с датой публикации в недалеком прошлом',
        'author_id' => 1,
        'created_at' => 1642510010,
        'updated_at' => 1642510010,
        'published_at' => 1642502810,
    ],
    [
        'title' => 'Sample note 4',
        'text' => 'Заметка с датой публикации в далеком прошлом',
        'author_id' => 2,
        'created_at' => 1641661210,
        'updated_at' => 1641661210,
        'published_at' => 1641661210,
    ],
    [
        'title' => 'Sample note 5',
        'text' => 'Заметка с датой публикации в недалеком прошлом',
        'author_id' => 1,
        'created_at' => 1642510010,
        'updated_at' => 1642510010,
        'published_at' => 1642502810,
    ],
    [
        'title' => 'Future note',
        'text' => 'Заметка с датой создания и публикации в далеком будущем',
        'author_id' => 2,
        'created_at' => time(),
        'updated_at' => time(),
        'published_at' => time() + 60 * 60 * 24 * 45, // after 45 days
    ],
];
