ТРЕБОВАНИЯ
----------
PHP версии 7.3.0 или новее

УСТАНОВКА
---------
Перед установкой в файлах `params/db.php` и `params/test_db.php`
необходимо указать данные для подключения к заранее созданным БД

~~~
composer install
yii migrate 
~~~

`yii fixture/load User` - загрузить в основную БД тестовые модели пользователей
(user1 и user2, смотри `tests/unit/fixtures/data/user.php`)

`yii fixture/load Note` - загрузить в основную БД тестовые модели заметок
(смотри `tests/unit/fixtures/data/user.php`)

ENDPOINTS
---------
~~~
GET|HEAD /users         - список заметок
POST /users             - создание заметки   *
GET|HEAD /users/{id}    - просмотр заметки
PATCH|PUT /users/{id}   - обновление заметки *
DELETE /users/{id}      - удаление заметки   *
~~~

\* - для доступа к этим endpoint требуется авторизация посредством использования Bearer Token Auth.
Для этого необходимо добавить заголовок `Authorization` со значением `Bearer dZlXsVnIDgIzFgX4EduAqkEPuOhhOh9q`
(пример для пользователя 'user2')

ТЕСТИРОВАНИЕ
------------
В файле `tests/api.suite.yml` указать актуальные данные для подключения к БД для тестов,
аналогичные данным в `params/test_db.php` - необходимо для загрузки дампа.

```
vendor/bin/codecept build
vendor/bin/codecept run api
```
