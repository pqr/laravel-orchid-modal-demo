# Демонстрационный проект Laravel Orchid

Проект для экспериментов с модальными окнами.

## Установка

```
composer install
php artisan migrate:fresh --seed
```

При запуске сидов будет создан пользователь `admin@admin.com` c паролем `password`.
Также будут заполнены таблицы `contacts` и `phones` несколькими тестовыми записями.

## Проблема

Зайти в модуль Contacts (первый пукнт в меню слева), провалиться в первую запись "Petr"

Внутри таблица из двух номеров телефонов привязанных к этому контакту.

Нажать кнопку Edit Phone напротив любого из телефонов и попробовать отредактировать в модальном окне.
После нажатия кнопки Apply будет ошибка
```
BadMethodCallException
Method App\Orchid\Screens\Contact\ContactEditScreen::1 does not exist.
```

Подробности ошибки описаны `app/Orchid/Screens/ContactEditScreen.php` в строках 86-104 (в комментариях).

Тажке вопрос по коду - на сколько он идиоматически верен?

Что можно улучшит в кодовой базе этого примера?

