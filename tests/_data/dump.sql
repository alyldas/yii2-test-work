create table if not exists `yii2-test-work-tests`.user
(
    id int auto_increment primary key,
    username varchar(255) not null,
    password_hash varchar(60) not null,
    auth_key varchar(32) not null,
    created_at int not null,
    updated_at int not null,
    constraint auth_key
    unique (auth_key),
    constraint username
    unique (username)
);

create table if not exists `yii2-test-work-tests`.note
(
    id int auto_increment primary key,
    created_at int not null,
    updated_at int not null,
    title varchar(255) not null,
    text text not null,
    author_id int not null,
    published_at int null,
    deleted_at int null,
    constraint note_user_id_fk
    foreign key (author_id) references `yii2-test-work-tests`.user (id) on update cascade on delete cascade
);

insert into `yii2-test-work-tests`.user
values (1, 'user1', '$2y$13$HdWT6cnoHLNGXzNMaTH23ehMA34lsPAuqErB1XvvNW7GIgyaGe7Sm', 'K3nF70it7tzNsHddEiq0BZ0ibOU8S3xV', 0, 0),
       (2, 'user2', '$2y$13$HdWT6cnoHLNGXzNMaTH23ehMA34lsPAuqErB1XvvNW7GIgyaGe7Sm', 'dZlXsVnIDgIzFgX4EduAqkEPuOhhOh9q', 0, 0);

insert into `yii2-test-work-tests`.note (id, title, text, author_id, created_at, updated_at, published_at, deleted_at)
values (1, 'Past note', 'Заметка с датой создания и публикации в далеком прошлом', 1, 1642510010, 1642510010, 1639918010, NULL),
       (2, 'Sample note 1', 'Заметка с датой публикации в недалеком будущем', 1, 1642510010, 1642510010, 1642517210, NULL),
       (3, 'Sample note 2', 'Заметка с датой публикации в недалеком будущем', 2, 1642510010, 1642510010, 1642502810, NULL),
       (4, 'Sample note 3', 'Заметка с датой публикации в недалеком прошлом', 1, 1642510010, 1642510010, 1642502810, NULL),
       (5, 'Sample note 4', 'Заметка с датой публикации в далеком прошлом', 2, 1641661210, 1641661210, 1641661210, NULL),
       (6, 'Sample note 5', 'Заметка с датой публикации в недалеком прошлом', 1, 1642510010, 1642510010, 1642502810, NULL),
       (7, 'Future note', 'Заметка с датой создания и публикации в далеком будущем', 2, 1642510010, 1642510010, 1646398010, NULL);
