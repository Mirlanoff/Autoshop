# 🚗 AutoParts Shop

Простой, но архитектурно правильный интернет-магазин автозапчастей, построенный с использованием современного стека Laravel + Vue (Inertia).

> ⚡ Проект демонстрирует production-ready подход: чистая архитектура, очередь, кеширование, админка и деплой через Docker.

---

## 🧠 О проекте

Это не просто CRUD-приложение.

В проекте реализован полный e-commerce flow:

* Каталог товаров
* Фильтрация и поиск
* Корзина (session-based)
* Оформление заказа
* Админка (управление заказами)
* Уведомления через очередь
* Оптимизация и кеширование

---

## ⚙️ Стек

**Backend:**

* Laravel 13
* PHP 8.3
* MySQL

**Frontend:**

* Vue 3 (Composition API)
* Inertia.js
* Tailwind CSS

**Инфраструктура:**

* Docker (Nginx + PHP-FPM + MySQL)
* Queue (database driver)
* Email (SMTP)

---

## 🧱 Архитектура

Проект построен с использованием принципов:

* SOLID
* Thin Controllers
* Action-based architecture
* DTO (Data Transfer Objects)
* API Resources (transform layer)

```id="arch1"
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

## 🔥 Основной функционал

### 🛍 Каталог

* Фильтрация (поиск, бренд, категория)
* Пагинация
* Оптимизированные запросы

### 🛒 Корзина

* Session-based
* Добавление / удаление / обновление
* Подсчёт суммы

### 📦 Заказы

* Оформление заказа
* Транзакции
* Order + OrderItems

### 🧑‍💻 Админка

* Просмотр заказов
* Фильтрация (status, поиск)
* Обновление статуса

### 🔔 Уведомления

* Очереди (Jobs)
* Email через SMTP
* Асинхронная обработка

---

## ⚡ Оптимизация

* Индексы БД
* Eager Loading
* Select only нужных полей
* Cache (каталог, бренды, категории)
* Lazy props (Inertia)

---

## 🔐 Безопасность

* Аутентификация (Laravel Breeze)
* Роли (admin / user)
* Middleware защита админки

---

## 🐳 Docker запуск

```bash
docker compose up -d --build
```

### Инициализация:

```bash
docker exec -it laravel_app bash

composer install
php artisan key:generate
php artisan migrate
npm install
npm run build
```

Открыть:

```
http://localhost:8000
```

---

## 🔁 Очередь

Queue worker запускается в отдельном контейнере:

```bash
php artisan queue:work
```

---

## 📬 Email

Настрой в `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=app_password
```

---

## 🚀 Production-ready

Проект уже готов к продакшену:

* Docker
* Queue worker
* SMTP
* Оптимизация запросов
* Разделение слоёв

---

## 🧠 Чему учит проект

* Архитектура Laravel приложений
* Работа с Inertia (SPA без SPA-сложности)
* Построение e-commerce логики
* Очереди и асинхронка
* Оптимизация и кеширование
* Деплой и инфраструктура

---

## 📌 Дальнейшие улучшения

* 💳 Онлайн-оплата (Stripe / локальные системы)
* ⚡ Redis + Horizon
* 📦 API для мобильного приложения
* 🧑‍💼 Полноценная админка (CRUD товаров)

---

## 👨‍💻 Автор

Разработано как учебно-практический проект с упором на реальные production-паттерны.

---

## ⭐ Если проект был полезен — поставь звезду
