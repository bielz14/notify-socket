## 🚀 Быстрый запуск проекта

Выполните следующие команды в терминале **из корня проекта**:

```bash
# 1. Создание файла окружения (данные для подключения к БД брать из docker-compose.yml
copy .env.docker.example .env.docker

# 2. Сборка Docker-образов
docker-compose build

# 3. Запуск контейнеров в фоне
docker-compose up -d
```
