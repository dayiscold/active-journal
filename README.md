# 🎓 Student Activity (Active Journal)

Сервис для учета посещаемости студентов, статистики и управления учебным процессом.

## 🚀 Стек технологий

* **Laravel (PHP 8.4)**
* **MySQL**
* **Redis**
* **Nginx**
* **Docker / Docker Compose**

---

## 📦 Возможности

* 📋 Учет посещаемости
* 📊 Статистика по студентам
* 📥 Импорт данных
* 👥 Управление студентами, группами и дисциплинами

---

## ⚠️ В разработке

* 🔐 Аутентификация (Auth) — требуется доработка
* 🗄️ Структура базы данных — возможны изменения
* 📊 Расширенная аналитика

---

## 🐳 Быстрый запуск (Docker)

### 1. Клонировать репозиторий

```bash
git clone https://github.com/YOUR_USERNAME/active-student.git
cd active-student
```

---

### 2. Запустить контейнеры

```bash
docker-compose up -d --build
```

---

### 3. Установить зависимости Laravel

```bash
docker exec -it active-student-app composer install
```

---

### 4. Настроить `.env`

```bash
cp .env.example .env
```

Отредактировать `.env`:

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=attendance_db
DB_USERNAME=attendance_user
DB_PASSWORD=attendance_password

REDIS_HOST=redis
REDIS_PORT=6379
```

---

### 5. Сгенерировать ключ приложения

```bash
docker exec -it active-student-app php artisan key:generate
```

---

### 6. Применить миграции

```bash
docker exec -it active-student-app php artisan migrate
```

---

## 🌐 Доступ к сервисам

| Сервис      | URL                   |
| ----------- | --------------------- |
| Laravel App | http://localhost      |
| phpMyAdmin  | http://localhost:8080 |
| MySQL       | localhost:3308        |
| Redis       | localhost:6380        |

---

## 🐳 Docker Hub

Образ приложения:

```
coldayyy/student-activity:latest
```

---

## 📁 Структура проекта

```
app/
 ├── Http/Controllers
 ├── Models
 ├── Middleware

database/
 ├── factories
 ├── migrations
 ├── seeders

resources/
 ├── views
 ├── js
 ├── css

routes/
 ├── web.php
 ├── api.php
 
 
```


## 🤝 Сontributors

1. Сделать fork репозитория
2. Создать ветку:

   ```bash
   git checkout -b feature/your-feature
   ```
3. Сделать изменения и commit:

   ```bash
   git commit -m "feat: add new feature"
   ```
4. Запушить ветку:

   ```bash
   git push origin feature/your-feature
   ```
5. Создать Pull Request 🚀

---

## 🧠 Рекомендации разработчикам

* Использовать отдельные контроллеры (например `AttendanceController`)
* Следовать REST API подходу
* Проверять данные через Request validation

---

## 📌 TODO

* [ ] Реализовать полноценный Auth (JWT / Sanctum)
* [ ] Добавить роли пользователей (Admin / Teacher / Student)
* [ ] Оптимизировать запросы к БД

