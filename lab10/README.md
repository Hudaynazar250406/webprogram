# ЛР10 — PHP Калькулятор в Docker

## Структура проекта

```
lr10/
├── Dockerfile          # Docker образ на основе php:8.2-apache
├── docker-compose.yml  # Конфигурация Docker Compose
├── README.md           # Эта инструкция
└── php-app/
    ├── index.php       # Главная страница калькулятора
    └── clear.php       # Очистка истории сессии
```

## Запуск через Docker Compose (рекомендуется)

```bash
# 1. Перейди в папку проекта
cd lr10

# 2. Собери и запусти контейнер
docker-compose up --build

# 3. Открой браузер
# http://localhost:8080
```

## Запуск через Docker (без Compose)

```bash
# Сборка образа
docker build -t lr10-calculator .

# Запуск контейнера
docker run -d -p 8080:80 --name lr10_calc lr10-calculator

# Открой браузер: http://localhost:8080
```

## Остановка

```bash
# Docker Compose
docker-compose down

# Или Docker
docker stop lr10_calc && docker rm lr10_calc
```

## Функциональность

- Сложение чисел через запятую: `1,2,3` → 6
- Сложение через `+`: `1+2+3` → 6
- Вложенные скобки: `(1,2)+(3,4)` → 10
- История вычислений (хранится в сессии PHP)
- Очистка истории
- Валидация скобок и числовых выражений
