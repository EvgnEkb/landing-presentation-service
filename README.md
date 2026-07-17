# Landing Presentation Service

Backend-сервис для формы обратной связи на лендинге. Принимает заявки, анализирует комментарий через OpenAI, отправляет письма владельцу и пользователю, логирует запросы и ограничивает частоту обращений.

## 1. Как запустить проект

### Требования

- Docker и Docker Compose
- Make (опционально, для удобных команд)

### Установка и запуск

```bash
# 1. Клонировать репозиторий
git clone https://github.com/EvgnEkb/landing-presentation-service.git
cd landing-presentation-service

# 2. Создать .env для Docker Compose (корень проекта)
cp .env.example .env

# 3. Создать .env для Laravel (директория src)
cp src/.env.example src/.env

# 4. Собрать контейнеры, поднять сервисы, установить зависимости и выполнить миграции
make setup

#5. Запустить очереди
make queue-start
```

### Доступные сервисы после запуска

| Сервис        | URL |
|---------------|---|
| WEB форма     | `http://localhost:8080/` |
| API           | `http://localhost:8080/api/contact` |
| Swagger UI    | `http://localhost:8080/api/documentation` |
| Health check  | `http://localhost:8080/up` |
| Mailpit (web) | `http://localhost:8025` |

---

## 2. Стек технологий

### Backend

| Категория | Технология |
|---|---|
| Язык | PHP 8.4 |
| Фреймворк | Laravel 12 |
| База данных | PostgreSQL 16 |
| HTTP-клиент | Guzzle (через `Illuminate\Support\Facades\Http`) |
| Документация API | L5-Swagger + swagger-php (PHP Attributes) |
| Почта | Laravel Mail (SMTP / log) |
| Контейнеризация | Docker, Nginx, PHP-FPM |

### DevOps и качество кода

- PHP CS Fixer
- PHPStan (Larastan)
- PHPUnit
- GitHub Actions CI

### AI

| Инструмент | Назначение |
|---|---|
| OpenAI Chat Completions API | Анализ тональности и категории обращения |
| Модель `gpt-3.5-turbo` | Классификация комментария пользователя |

Пакет `openai-php/laravel` подключён в проекте, но текущая реализация использует прямой HTTP-запрос через Laravel Http Client - это упрощает контроль над промптом и обработкой ответа.

---

## 3. Реализация API

### Эндпоинты

| Метод | URL | Описание |
|---|---|---|
| `POST` | `/api/contact` | Отправка формы обратной связи |
| `GET` | `/up` | Health check |
| `GET` | `/api/documentation` | Swagger UI |

### `POST /api/contact`

**Тело запроса (JSON):**

```json
{
  "name": "Иван Иванов",
  "phone": "+7 999 123-45-67",
  "email": "user@example.com",
  "comment": "Здравствуйте! У меня вопрос по заказу."
}
```

**Успешный ответ `201 Created`:**

```json
{
  "message": "Ваше сообщение отправлено",
  "data": {
    "name": "Иван Иванов",
    "email": "user@example.com"
  },
  "ai_analysis": {
    "sentiment": "neutral",
    "category": "question"
  }
}
```

Поле `ai_analysis` может быть `null`, если OpenAI недоступен - заявка при этом всё равно обрабатывается.

**Ошибка валидации `422 Unprocessable Entity`:**

```json
{
  "message": "Имя обязательно (and 1 more error)",
  "errors": {
    "name": ["Имя обязательно"],
    "email": ["Укажите корректный email"]
  }
}
```

**Ошибка отправки почты `500 Internal Server Error`:**

```json
{
  "message": "Не удалось отправить письмо, попробуйте позже",
  "errors": {
    "email": ["Ошибка отправки"]
  }
}
```

**Превышение лимита `429 Too Many Requests`:**

Стандартный ответ Laravel Throttle middleware.

### Валидация

Правила в `ContactRequest`:

| Поле | Правила |
|---|---|
| `name` | required, string, max:255 |
| `phone` | required, string, max:20 |
| `email` | required, email, max:255 |
| `comment` | required, string, max:5000 |

Сообщения об ошибках - на русском языке.

### Обработка ошибок

- **422** - автоматически Laravel при ошибке валидации Form Request.
- **500** - при сбое отправки email в `ContactController`.
- **429** - при превышении rate limit.
- Глобальный обработчик в `bootstrap/app.php` возвращает JSON для API-запросов (`expectsJson()`).

### Пример запроса через curl

```bash
curl -X POST http://localhost:8080/api/contact \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Иван Иванов",
    "phone": "+7 999 123-45-67",
    "email": "user@example.com",
    "comment": "Хочу уточнить сроки доставки"
  }'
```

---

## 5. AI-интеграция

### Инструменты и назначение

| Компонент | Назначение |
|---|---|
| `AIAnalysisInterface` | Контракт для AI-анализа (легко заменить реализацию) |
| `OpenAIAnalysisService` | Вызов OpenAI Chat Completions API |
| Модель `gpt-3.5-turbo` | Определение тональности и типа обращения |

AI анализирует поле `comment` и возвращает:

```json
{
  "sentiment": "positive | neutral | negative",
  "category": "complaint | suggestion | question | other"
}
```

### Промпт (system message)

```
Ты - аналитик. Определи тональность (positive, neutral, negative) и классифицируй тип запроса (complaint, suggestion, question, other). Ответь JSON: {"sentiment": "...", "category": "..."}.
```

Параметры запроса: `temperature: 0.3`, `max_tokens: 60`.

### Реализация fallback

AI-анализ **не блокирует** основной сценарий. Fallback реализован на трёх уровнях:

1. **Уровень контроллера** - исключение при вызове AI перехватывается, `ai_analysis` остаётся `null`, письма отправляются.
2. **Уровень сервиса (ошибка API)** - при неуспешном HTTP-ответе логируется предупреждение, возвращается `null`.
3. **Уровень парсинга ответа** - если OpenAI вернул не JSON, вызывается `fallbackParse()`: из текста ответа извлекаются ключевые слова (`positive`, `complaint` и т.д.).

```php
// ContactController - AI не ломает отправку формы
try {
    $aiResult = $this->aiService->analyze($validated['comment']);
} catch (\Throwable $e) {
    Log::warning('AI analysis failed: ' . $e->getMessage());
}
```

---

## 6. Хранение данных

### Логи

| Тип | Где хранится | Как пишется |
|---|---|---|
| API-запросы/ответы | `storage/logs/laravel-YYYY-MM-DD.log` | `LogRequestMiddleware` через канал `daily` |
| Успешные заявки | `storage/logs/laravel.log` | `Log::info()` в `ContactController` |
| Ошибки AI | `storage/logs/laravel.log` | `Log::warning()` / `Log::error()` в `OpenAIAnalysisService` |
| Ошибки почты | `storage/logs/laravel.log` | `Log::error()` в `ContactController` |

Канал `daily` ротирует файлы, хранит записи 14 дней (настраивается через `LOG_DAILY_DAYS`).

Middleware логирует метод, URL, IP, payload (без паролей) и статус ответа.

### Rate limiting

Реализован через встроенный Laravel middleware `throttle` на маршруте `/api/contact`:

```php
->middleware('throttle:' . env('RATE_LIMIT_ATTEMPTS', 5) . ',' . env('RATE_LIMIT_DECAY', 1));
```

По умолчанию: **5 запросов в минуту** с одного IP.

Счётчики хранятся в **кэше Laravel** (`CACHE_STORE` / `CACHE_DRIVER` в `.env`):

- `database` - таблица `cache` в PostgreSQL
- `file` - файлы в `storage/framework/cache/`

Отдельной таблицы для rate limit нет - используется стандартный механизм Laravel.

### Статистика

Отдельного хранилища статистики (аналитической БД, таблицы заявок) **нет** - это осознанное решение для MVP:

- Заявки **не сохраняются** в базу данных, а уходят на email.
- Единственный «аналитический» след - записи в логах (`email`, результат AI-анализа).
- PostgreSQL используется для инфраструктурных нужд Laravel: сессии, кэш, очереди, миграции по умолчанию.

Для production-версии можно добавить таблицу `contact_submissions` и вынести отправку писем в очередь (`QUEUE_CONNECTION=database`).

---