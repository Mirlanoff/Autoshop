# 🚗 AutoParts Shop

Современный интернет-магазин автозапчастей, построенный на Laravel + Vue (Inertia) с упором на **чистую архитектуру и production-ready подход**.

> ⚡ Проект демонстрирует полный e-commerce flow: каталог, корзина, заказы, админка, очереди и деплой через Docker.

---

## 🧠 О проекте

Это не просто CRUD-приложение, а приближенная к реальности система:

* Каталог товаров с фильтрацией
* Корзина (session-based)
* Оформление заказов
* Админ-панель
* Уведомления через очередь
* Оптимизация и кеширование

---

## ⚙️ Технологии

### Backend

* Laravel 13
* PHP 8.3
* MySQL

### Frontend

* Vue 3 (Composition API)
* Inertia.js
* Tailwind CSS

### Инфраструктура

* Docker (Nginx + PHP-FPM + MySQL)
* Queue (database driver)
* SMTP email

---

## 🧱 Архитектура

Проект построен с использованием:

* SOLID принципов
* Action-based архитектуры
* DTO (Data Transfer Objects)
* API Resources (трансформация данных)
* Thin Controllers

```bash
app/
 ├── Actions/
 ├── DTO/
 ├── Models/
 ├── Services/
 ├── Http/
 │    ├── Controllers/
 │    ├── Requests/
 │    └── Resources/
```

---

## 🚀 Запуск через Docker (рекомендуется)

### 1. Клонировать проект

```bash
git clone <your-repo>
cd <project-folder>
```

---

### 2. Запустить контейнеры

```bash
docker compose up -d --build
```

---

### 3. Зайти в контейнер

```bash
docker exec -it laravel_app bash
```

---

### 4. Установить зависимости

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

npm install
npm run build
```

---

### 5. Открыть проект

```
http://localhost:8000
```

---

## 💻 Локальный запуск (без Docker)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

npm install
npm run build
php artisan serve
```

---

## 🔁 Очередь (Queue)

Проект использует очередь для отправки уведомлений.

### Запуск worker:

```bash
php artisan queue:work
```

> ⚠️ В Docker worker запускается автоматически

---

## 📬 Email (SMTP)

Настрой в `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@email.com
```

---

## 🔐 Авторизация и роли

* Laravel Breeze
* Роли: `user`, `admin`
* Middleware защита админки

---

## 🧑‍💻 Админка

Доступ:

```
/admin/orders
```

Функционал:

* просмотр заказов
* фильтрация
* изменение статуса

---

## 🛒 Основной функционал

### Каталог

* фильтрация (поиск, бренд, категория)
* пагинация

### Корзина

* session-based
* добавление / удаление товаров

### Заказы

* оформление
* транзакции
* Order + OrderItems

---

## ⚡ Оптимизация

* индексы БД
* eager loading
* кеширование (каталог, бренды, категории)
* минимизация payload

---

## 🧪 Тестовый доступ

Админ можно создать через:

```bash
php artisan tinker
```

```php
$user = \App\Models\User::first();
$user->role = 'admin';
$user->save();
```

---

## 📌 Возможные улучшения

* 💳 Онлайн-оплата (Stripe)
* ⚡ Redis + Horizon
* 📦 API для мобильного приложения
* 🧑‍💼 Расширенная админка

---

## 👨‍💻 Автор

Проект создан как практическая демонстрация архитектурного подхода к Laravel разработке.

---

## ⭐ Поддержка

Если проект был полезен — поставь звезду ⭐
