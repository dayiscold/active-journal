<div align="center">

# Активный студент

**Веб-система учёта посещаемости для университетов**

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-v4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=flat-square&logo=alpine.js&logoColor=white)](https://alpinejs.dev)
[![Docker](https://img.shields.io/badge/Docker-ready-2496ED?style=flat-square&logo=docker&logoColor=white)](https://docker.com)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)](LICENSE)

Система автоматизирует журнал посещаемости: преподаватель отмечает студентов вручную или загружает CSV-выгрузку из Microsoft Teams — всё остальное система делает сама.

</div>

---

## Возможности

- **4 роли** — Администратор, Преподаватель, Студент, Учебная часть с разграниченным доступом
- **Ручная отметка** — удобный сегментированный контрол прямо в таблице группы
- **Импорт из Teams** — загрузите CSV, система сопоставит email и проставит статусы автоматически
- **Умное определение опоздания** — по времени входа (+15 мин) и длительности присутствия (< 75 мин)
- **Отчёты** — по студенту, по группе, по дисциплине; прогресс-бары и процент посещаемости
- **Тёмная / светлая тема** — переключается в один клик, сохраняется в профиле
- **Полностью Docker** — поднимается одной командой, без ручной настройки окружения

## Стек технологий

| Слой | Технология |
|------|-----------|
| Backend | Laravel 13, PHP 8.4-fpm |
| Frontend | Tailwind CSS v4, Alpine.js 3, Vite 7 |
| База данных | MySQL 8, Redis (сессии) |
| Инфраструктура | Docker, nginx |
| Инструменты | Laravel Pint, Chart.js |

## Быстрый старт

**Требования:** Docker Desktop, Git.

```bash
# 1. Клонировать репозиторий
git clone https://github.com/dayiscold/active-journal.git
cd active-journal/active-student

# 2. Скопировать конфиг окружения
cp .env.example .env

# 3. Поднять контейнеры
docker compose up -d

# 4. Установить зависимости и подготовить БД
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed

# 5. Собрать фронтенд
docker compose exec app npm install
docker compose exec app npm run build
```

Приложение доступно на **http://localhost**

| Сервис | Адрес |
|--------|-------|
| Приложение | http://localhost |
| phpMyAdmin | http://localhost:8080 |

### Тестовые учётные записи

| Роль | Email | Пароль |
|------|-------|--------|
| Администратор | admin@example.com | password |
| Преподаватель | teacher@example.com | password |
| Студент | student@example.com | password |
| Учебная часть | dean@example.com | password |

## Структура проекта

```
active-student/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # AuthController, AttendanceController, ReportController...
│   │   └── Middleware/         # CheckRole — ролевой доступ
│   └── Models/                 # User, Group, Discipline, Lesson, Attendance
├── database/
│   ├── migrations/             # Схема БД
│   └── seeders/                # Тестовые данные
├── resources/
│   ├── css/app.css             # Tailwind + CSS-переменные тем
│   ├── js/app.js               # Alpine.js, themeToggle
│   └── views/
│       ├── admin/              # CRUD: группы, дисциплины, пользователи
│       ├── teacher/            # Занятия, отметка посещаемости, импорт CSV
│       ├── student/            # Личный дашборд с Chart.js
│       ├── reports/            # Отчёты (группа, студент)
│       ├── layouts/app.blade.php
│       └── components/         # stat-card, badge, glass-card
└── routes/web.php
```

## Схема данных

```
users ──────────┐
 role: admin    │ group_id
       teacher  ├──► groups ◄──────── group_discipline ──► disciplines
       student  │                           │ teacher_id
       dean     │                           ▼
                │                        lessons
                │                           │ lesson_id
                └───────────────────► attendance
                                       status: present / late / absent / sick
```

## Роли и доступ

| Раздел | admin | teacher | student | dean |
|--------|:-----:|:-------:|:-------:|:----:|
| Управление группами | ✓ | — | — | — |
| Управление дисциплинами | ✓ | — | — | — |
| Управление пользователями | ✓ | — | — | — |
| Создание занятий | — | ✓ | — | — |
| Отметка посещаемости | — | ✓ | — | — |
| Импорт из Teams | — | ✓ | — | — |
| Отчёты | ✓ | ✓ | — | ✓ |
| Личная статистика | — | — | ✓ | — |

## Импорт из Microsoft Teams

1. В Teams → **Участники** → **Скачать список участников** (`.csv`)
2. На странице занятия нажмите **Импорт из Teams CSV** и загрузите файл
3. Система автоматически:
   - Сопоставит email из CSV с базой данных (поле `teams_email` или `email`)
   - Отметит студентов **присутствующими**, **опоздавшими** или **отсутствующими**

**Критерии опоздания:** вход позже 15 минут от начала пары ИЛИ суммарное время в звонке менее 75 минут.

## Разработка

```bash
# Горячая перезагрузка фронтенда
docker compose exec app npm run dev

# Запуск тестов
docker compose exec app php artisan test

# Форматирование кода (Laravel Pint)
docker compose exec app ./vendor/bin/pint
```

---

## Авторы

Учебный проект — Асатулин В.А., Геребен Я.В., Птухин Т.И.
