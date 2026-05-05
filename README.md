# AutoParts Shop

Современный интернет-магазин автозапчастей, построенный на **Laravel 13 + Vue 3 (Inertia.js)** с упором на чистую архитектуру и production-ready подход.

## Функционал

### Витрина
- Каталог товаров с фильтрацией (поиск, бренд, категория)
- Пагинация
- Страница товара с описанием и похожими товарами
- Адаптивный дизайн (mobile-first)

### Корзина
- Session-based корзина (без авторизации)
- Изменение количества, удаление товаров
- Подсчёт итоговой суммы

### Заказы
- Оформление заказа (имя, телефон, адрес)
- Автоматическое уменьшение остатков
- История заказов пользователя
- Email-уведомления через очередь

### Админ-панель (`/admin/orders`)
- Просмотр всех заказов
- Фильтрация по статусу и поиск
- Изменение статуса заказа (ожидает / в обработке / выполнен / отменён)

### Авторизация
- Регистрация / Вход (Laravel Breeze)
- Роли: `user`, `admin`
- Middleware защита админки

## Технологии

### Backend
- **Laravel 13** (PHP 8.3)
- MySQL 8.4
- Queue (database driver)
- SMTP email

### Frontend
- **Vue 3** (Composition API)
- **Inertia.js**
- **Tailwind CSS**

### Архитектура
- SOLID принципы
- Action-based (Actions, DTOs, Resources)
- Thin Controllers
- Service Layer (CartService)

```
app/
├── Actions/         # Бизнес-логика
├── DTO/             # Data Transfer Objects
├── Services/        # Сервисы (CartService)
├── Models/          # Eloquent модели
├── Http/
│   ├── Controllers/ # Тонкие контроллеры
│   ├── Middleware/   # AdminMiddleware, Inertia
│   ├── Requests/    # Form Requests (валидация)
│   └── Resources/   # API Resources
├── Jobs/            # Очереди (SendOrderNotification)
└── Notifications/   # Email уведомления
```

## Быстрый старт

### Docker (рекомендуется)

```bash
git clone https://github.com/Mirlanoff/Autoshop.git
cd Autoshop

# Development (Laravel Sail)
cp .env.example .env
docker compose up -d --build
docker exec -it laravel_app bash
composer install
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
```

### Production

```bash
cp .env.example .env
# Настройте .env (DB, MAIL, APP_URL)
docker compose -f docker-compose.prod.yml up -d --build
docker exec -it autoshop-app-1 php artisan migrate --seed
```

### Локально (без Docker)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

npm install
npm run build
php artisan serve
```

Откройте: `http://localhost:8000`

## Тестовые данные

После `php artisan db:seed`:

| Роль  | Email           | Пароль   |
|-------|-----------------|----------|
| Admin | admin@test.com  | password |
| User  | (5 случайных)   | password |

- 40 товаров (реальные названия автозапчастей)
- 10 брендов, 8 категорий

## Структура страниц

| URL                    | Описание              | Доступ      |
|------------------------|-----------------------|-------------|
| `/`                    | Каталог товаров       | Все         |
| `/products/{slug}`     | Страница товара       | Все         |
| `/cart`                | Корзина               | Все         |
| `/checkout`            | Оформление заказа     | Auth        |
| `/orders`              | История заказов       | Auth        |
| `/orders/{id}`         | Детали заказа         | Auth (свой) |
| `/admin/orders`        | Управление заказами   | Admin       |
| `/admin/orders/{id}`   | Детали заказа (админ) | Admin       |
| `/login`               | Вход                  | Guest       |
| `/register`            | Регистрация           | Guest       |
| `/profile`             | Профиль               | Auth        |

## Очередь

```bash
php artisan queue:work
```

В Docker worker запускается автоматически.

## Возможные улучшения

- Онлайн-оплата (Stripe / Элсом)
- Redis + Laravel Horizon
- API для мобильного приложения
- Поиск по OEM-номерам
- Загрузка изображений товаров
- Расширенная аналитика в админке

## Автор

Проект создан как практическая демонстрация архитектурного подхода к Laravel-разработке.
