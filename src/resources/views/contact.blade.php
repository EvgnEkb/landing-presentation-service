<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Обратная связь — {{ config('app.name', 'Landing') }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #fafafa 50%, #f5f0ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #1a1a2e;
        }

        .card {
            width: 100%;
            max-width: 480px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 2rem;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .subtitle {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.35rem;
            color: #374151;
        }

        input, textarea {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.15s;
            margin-bottom: 1rem;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        input.error, textarea.error {
            border-color: #ef4444;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .field-error {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: -0.75rem;
            margin-bottom: 0.75rem;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background: #6366f1;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
        }

        button:hover:not(:disabled) {
            background: #4f46e5;
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .alert {
            padding: 0.85rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: none;
        }

        .alert.success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert.error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert.visible { display: block; }

        .ai-badge {
            margin-top: 0.75rem;
            padding: 0.6rem 0.85rem;
            background: #f5f3ff;
            border-radius: 8px;
            font-size: 0.8rem;
            color: #5b21b6;
            display: none;
        }

        .ai-badge.visible { display: block; }

        .services {
            margin-top: 1.75rem;
            padding-top: 1.25rem;
            border-top: 1px solid #e5e7eb;
        }

        .services h2 {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            margin-bottom: 0.75rem;
        }

        .services-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .services-list a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.55rem 0.75rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            color: #374151;
            text-decoration: none;
            font-size: 0.85rem;
            transition: border-color 0.15s, background 0.15s;
        }

        .services-list a:hover {
            background: #f3f4f6;
            border-color: #6366f1;
            color: #4f46e5;
        }

        .services-list .label {
            font-weight: 500;
        }

        .services-list .url {
            font-size: 0.75rem;
            color: #9ca3af;
            font-family: ui-monospace, monospace;
        }

        .services-list a:hover .url {
            color: #818cf8;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Обратная связь</h1>
        <p class="subtitle">Оставьте сообщение — мы ответим на email</p>

        <div id="alert" class="alert"></div>

        <form id="contact-form" novalidate>
            <div>
                <label for="name">Имя</label>
                <input type="text" id="name" name="name" placeholder="Иван Иванов" required>
                <div class="field-error" data-field="name"></div>
            </div>

            <div>
                <label for="phone">Телефон</label>
                <input type="tel" id="phone" name="phone" placeholder="+7 999 123-45-67" required>
                <div class="field-error" data-field="phone"></div>
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="user@example.com" required>
                <div class="field-error" data-field="email"></div>
            </div>

            <div>
                <label for="comment">Сообщение</label>
                <textarea id="comment" name="comment" placeholder="Ваш вопрос или комментарий..." required></textarea>
                <div class="field-error" data-field="comment"></div>
            </div>

            <button type="submit" id="submit-btn">Отправить</button>
        </form>

        <div id="ai-badge" class="ai-badge"></div>

        @php
            $mailpitUrl = request()->getScheme() . '://' . request()->getHost() . ':8025';
        @endphp

        <nav class="services" aria-label="Доступные сервисы">
            <h2>Сервисы</h2>
            <ul class="services-list">
                <li>
                    <a href="{{ url('/api/documentation') }}" target="_blank" rel="noopener">
                        <span class="label">Swagger UI</span>
                        <span class="url">/api/documentation</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/up') }}" target="_blank" rel="noopener">
                        <span class="label">Health check</span>
                        <span class="url">/up</span>
                    </a>
                </li>
                <li>
                    <a href="{{ $mailpitUrl }}" target="_blank" rel="noopener">
                        <span class="label">Mailpit</span>
                        <span class="url">:8025</span>
                    </a>
                </li>
                <li>
                    <span class="info">
                        <span class="label">API</span>
                        <span class="url">POST /api/contact</span>
                    </span>
                </li>
            </ul>
        </nav>
    </div>

    <script>
        const form = document.getElementById('contact-form');
        const alertEl = document.getElementById('alert');
        const aiBadge = document.getElementById('ai-badge');
        const submitBtn = document.getElementById('submit-btn');

        function showAlert(message, type) {
            alertEl.textContent = message;
            alertEl.className = 'alert visible ' + type;
        }

        function clearErrors() {
            document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
            document.querySelectorAll('input, textarea').forEach(el => el.classList.remove('error'));
        }

        function showErrors(errors) {
            for (const [field, messages] of Object.entries(errors)) {
                const errorEl = document.querySelector(`[data-field="${field}"]`);
                const inputEl = document.getElementById(field);
                if (errorEl) errorEl.textContent = messages[0];
                if (inputEl) inputEl.classList.add('error');
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearErrors();
            alertEl.className = 'alert';
            aiBadge.className = 'ai-badge';

            const data = {
                name: form.name.value.trim(),
                phone: form.phone.value.trim(),
                email: form.email.value.trim(),
                comment: form.comment.value.trim(),
            };

            submitBtn.disabled = true;
            submitBtn.textContent = 'Отправка...';

            try {
                const response = await fetch('/api/contact', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data),
                });

                const result = await response.json();

                if (response.ok) {
                    showAlert(result.message || 'Сообщение отправлено!', 'success');
                    form.reset();

                    if (result.ai_analysis) {
                        const { sentiment, category } = result.ai_analysis;
                        const labels = {
                            positive: 'позитивный', neutral: 'нейтральный', negative: 'негативный',
                            complaint: 'жалоба', suggestion: 'предложение', question: 'вопрос', other: 'другое',
                        };
                        aiBadge.textContent = `AI-анализ: тон ${labels[sentiment] || sentiment}, тип — ${labels[category] || category}`;
                        aiBadge.className = 'ai-badge visible';
                    }
                } else if (response.status === 422 && result.errors) {
                    showAlert(result.message || 'Проверьте поля формы', 'error');
                    showErrors(result.errors);
                } else if (response.status === 429) {
                    showAlert('Слишком много запросов. Попробуйте через минуту.', 'error');
                } else {
                    showAlert(result.message || 'Ошибка при отправке', 'error');
                }
            } catch {
                showAlert('Не удалось связаться с сервером', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Отправить';
            }
        });
    </script>
</body>
</html>
