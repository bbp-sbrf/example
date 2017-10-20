Тестовое задание
================

В данном примере решил реализовать возможность загрузки 
[реестра дисквалифицированных лиц](https://www.nalog.ru/opendata/7707329152-registerdisqualified/) 
(т.е. лиц, которым по решению суда запрещено на определенный срок занимать руководящие должности) с сайта налоговой инспекции.

Данные справочника могут быть использованы при регистрации нового пользователя, что бы не допустить к работе на площадке ненадежных лиц.
 

__Что сделано:__
* app\components\registers\RegisterComponent - реализация клиента к сайту налоговой. 
В качестве адаптера может быть использован любой клиент, реализующий интерфейс AdapterInterface. 
В моём случае это Adapter\GuzzleHttpAdapter из библиотеки Guzzle 

* app\commands\DisqualifiedController - консольная команда загрузки справочника.



Требования
----------

* guzzlehttp/guzzle
* yiisoft/yii2-mongodb


Настройка
---------

```php
return [
    //....
    'components' => [
        'register' => [
            'class' => \app\components\registers\RegisterComponent::class,
        ],
    ],
];
```

Загрузка справочника
--------------------
```sh
/usr/bin/php /home/sberbank.dev.kingbird.ru/yii disqualified
```
Можно настроить периодическую загрузку справочника, например:

```sh
30 3 * * * /usr/bin/php /home/sberbank.dev.kingbird.ru/yii disqualified
```
В таком варианте задача будет выполняться ежедневно в 3:30 утра.


Поиск пользователя
------------------
```php
$rows = \Yii::$app->register->findByFio($fio);
print_r($rows);
```
 
---