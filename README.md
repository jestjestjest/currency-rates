## Курсы обмена валют
Простая реализация api для импорта данных из обменника.
Для запуска потребуется окружение:
- PHP 8.0.14
- Laravel 9
- MySQL 8.0

Для запуска потребуется выполнить:
- настройку подключения к БД в .env
- пролить миграции php artisan migrate
- запустить laravel serve

Чтобы осуществить добавление валют для которых нужны курсы - необходимо
добавить базовую валюту(добавить можно любую, но, к сожалению,
бесплатные api обменников предоставляют данные только по одной валюте и это 
EUR)

##Методы API

Методы защищены JWT авторизацией:
 
- POST api/auth/register
- POST api/auth/login
- POST api/auth/refresh
- GET api/auth/user-profile

Методы работы с базовой валютой

- POST api/base-currencies - создать базовую валюту
- GET api/base-currencies/{id?} - вывести все валюты или вывести одну
валюту с курсами на сегодня и историей курсов
- DELETE api/base-currencies/{id} - удалить базовую валюту

##Консольные команды
currency:get {dateFrom?} {dateTo?} - вытягивает курсы валют с открытого API
вшитого обменника, без параметров - происходит импорт за сегодняшний день
с параметрами - за промежуток дат


