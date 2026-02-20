<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Assistant</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 800px;
            padding: 30px;
        }

        h1 {
            color: #333;
            margin-bottom: 25px;
            font-size: 28px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }

        select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        select {
            background-color: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
        }

        select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .response-container {
            margin-top: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .response-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            color: #555;
            font-weight: 500;
        }

        .response-content {
            color: #333;
            line-height: 1.6;
            font-size: 15px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .response-content.empty {
            color: #999;
            font-style: italic;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error-message {
            background-color: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 14px;
        }

        .response-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .copy-button {
            background: #e0e0e0;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            color: #333;
            transition: all 0.2s;
            width: auto;
        }

        .copy-button:hover {
            background: #d0d0d0;
        }

        .info-text {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🤖 AI Assistant</h1>

    <form id="aiForm">
        @csrf
        <div class="form-group">
            <label for="modelName">Выберите модель:</label>
            <select name="modelName" id="modelName" required>
                <option value="">-- Выберите модель --</option>
                @foreach($models as $model)
                    <option value="{{ $model }}" {{ $loop->first ? 'selected' : '' }}>
                        {{ $model }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="content">Ваш вопрос:</label>
            <textarea
                name="content"
                id="content"
                required
                placeholder="Например: Привет! Объясни, что такое Docker..."
            ></textarea>
        </div>

        <button type="submit" id="submitBtn">
            <span class="btn-text">🚀 Спросить у ИИ</span>
            <span class="loading" style="display: none;"></span>
        </button>
    </form>

    <div class="response-container" id="responseContainer" style="display: none;">
        <div class="response-header">
            <div class="response-title">
                <span>📝 Ответ AI:</span>
            </div>
            <button class="copy-button" id="copyButton" onclick="copyResponse()">
                📋 Копировать
            </button>
        </div>
        <div class="response-content" id="responseContent"></div>
        <div class="error-message" id="errorMessage" style="display: none;"></div>
    </div>

    <div class="info-text">
        Доступно моделей: {{ count($models) }} |
        <span id="modelCount">{{ count($models) }}</span>
    </div>
</div>

<script>
    const form = document.getElementById('aiForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.querySelector('.btn-text');
    const loading = document.querySelector('.loading');
    const responseContainer = document.getElementById('responseContainer');
    const responseContent = document.getElementById('responseContent');
    const errorMessage = document.getElementById('errorMessage');
    const modelSelect = document.getElementById('modelName');
    const contentInput = document.getElementById('content');

    // Устанавливаем CSRF токен для всех запросов
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Показываем загрузку
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        loading.style.display = 'inline-block';

        // Скрываем предыдущий контейнер с ответом
        responseContainer.style.display = 'none';
        errorMessage.style.display = 'none';

        // Валидация на клиенте
        if (!modelSelect.value) {
            showError('Пожалуйста, выберите модель');
            resetButton();
            return;
        }

        if (!contentInput.value.trim()) {
            showError('Пожалуйста, введите вопрос');
            resetButton();
            return;
        }

        try {
            const response = await fetch('{{ route("ai.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    modelName: modelSelect.value,
                    content: contentInput.value.trim()
                })
            });

            const data = await response.json();

            if (response.ok) {
                // Показываем ответ
                showResponse(data);
            } else {
                // Показываем ошибку
                showError(data.message || 'Произошла ошибка при запросе');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Ошибка соединения с сервером');
        } finally {
            resetButton();
        }
    });

    function showResponse(data) {
        responseContent.textContent = data.response || data.message || 'Ответ получен';
        responseContent.classList.remove('empty');
        responseContainer.style.display = 'block';
    }

    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        responseContainer.style.display = 'block';
        responseContent.textContent = '';
    }

    function resetButton() {
        submitBtn.disabled = false;
        btnText.style.display = 'inline';
        loading.style.display = 'none';
    }

    // Функция копирования ответа
    window.copyResponse = function() {
        const text = responseContent.textContent;
        if (text) {
            navigator.clipboard.writeText(text).then(() => {
                const copyBtn = document.getElementById('copyButton');
                const originalText = copyBtn.textContent;
                copyBtn.textContent = '✅ Скопировано!';
                setTimeout(() => {
                    copyBtn.textContent = originalText;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }
    };

    // Автоматическое изменение высоты textarea
    contentInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Сохраняем выбранную модель в localStorage
    modelSelect.addEventListener('change', function() {
        localStorage.setItem('selectedModel', this.value);
    });

    // Восстанавливаем выбранную модель из localStorage
    const savedModel = localStorage.getItem('selectedModel');
    if (savedModel) {
        const option = Array.from(modelSelect.options).find(opt => opt.value === savedModel);
        if (option) {
            modelSelect.value = savedModel;
        }
    }
</script>
</body>
</html>
