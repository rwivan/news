#Тестовое задание

В данном тестовом задании я реализовал новостной сайт.

Данный сайт написан на базе фреймворка Yii 2

## Зание № 1 - новостной сайт

### Установка

1. Создать базу данных `yii2basic_news` и пользователя в ней `news_maker`
    ```sql
    CREATE DATABASE `yii2basic_news` CHARACTER SET utf8 COLLATE utf8_general_ci;
    GRANT ALL ON `mydb`.* TO `news_maker`@% IDENTIFIED BY 'WxnYMHWymstn6V9Sc99gaDIS4ytwtY3b';
    ```
2. Выполнить миграции
    ```bash
    $ yii migrate
    ```
3. Выпонить инициализацию
    ```bash
    $ yii init
    ```

В результате данных действий будут созданные все необходимы структуры и в базе данных
будут заведены 3 пользователя (admin, editor, reader) с паролями "123456".


## Задание № 2 - Модуль уведомлений

### Модель данных

Таблица событий
```sql
CREATE TABLE event (
  id INT NOT NULL PRIMARY KEY,
  class_name VARCHAR(500) NOT NULL COMMENT 'Имя класса, у которого отслеживаем событие',
  event_name VARCHAR(500) NOT NULL COMMENT 'Имя отслеживаемого события'
)
  ENGINE = InnoDB
  COLLATE = utf8_unicode_ci;
```

Таблица каналов связи (email, alert, web-push, telegram, sms)
```sql
CREATE TABLE chanel (
  id INT NOT NULL PRIMARY KEY,
  name VARCHAR(50) NOT NULL COMMENT 'Наименование канала связи' 
)
  ENGINE = InnoDB
  COLLATE = utf8_unicode_ci;
```

Таблица шаблонов
```sql
CREATE TABLE template (
  id INT NOT NULL PRIMARY KEY,
  text TEXT NOT NULL COMMENT 'Текст шаблона'
)
  ENGINE = InnoDB
  COLLATE = utf8_unicode_ci;
```

Таблица сообщений (таблица связей, что, кому и как посылать)
```sql
CREATE TABLE notice (
  id INT NOT NULL PRIMARY KEY,
  role_name VARCHAR(50) NOT NULL COMMENT 'Имя роли пользоватлей',
  event_id INT COMMENT 'Идентификатор события',
  chanel_id INT COMMENT 'Идентификато канала связи',
  template_id INT COMMENT 'Идентификато шаблона',  
)
  ENGINE = InnoDB
  COLLATE = utf8_unicode_ci;
```

### Классы

#### Классы моделей
Все классы моделей потомки ActiveRecord
* Event
* Chanel
* Template
* Notice

#### Контроллеры
Все контроллеры выполнены, как стандартные CRUD, и потомки WebController 
* EventController
* TemplateController
* NoticeController

Таблица Chanel заполняет исключительно из миграций, она является не редактируемым справочником.
В ней должны быть только те каналы, для которых реализована доставка.

#### Нюансы реализации

Необходимо будет реализовать
```php
class Bootstrap implements BootstrapInterface
```
В нем нужно будет регистрировать события, за которыми необходимо следить.


Необходимо реализовать TemplateRender который будет отрисовывать шаблоны и подставлять переменные

Для каждого канала наобходимо реализовать свой класс EmailTransport, AlertTransport.
Все они должны быть патомками AbstractTransport
 