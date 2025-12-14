# Sistema de Gerenciamento de Pessoas

Sistema web para cadastro de pessoas físicas e jurídicas desenvolvido com Laravel + Vue.js + Docker.

## 🚀 Como Rodar

### 1. Clone o repositório
```bash
git clone https://github.com/GuilhermeSoares009/desafio_gerenciamento
cd desafio_gerenciamento
```

### 2. Configure o backend
```bash
cd backend
cp .env.example .env
cd ..
```

**Windows (PowerShell como Administrador):**
```powershell
icacls backend\storage /grant Everyone:F /T
icacls backend\bootstrap\cache /grant Everyone:F /T
```

**Linux/Mac:**
```bash
chmod -R 777 backend/storage backend/bootstrap/cache
```

### 3. Suba os containers
```bash
docker compose up -d --build
```

### 4. Configure o banco 
```bash
docker exec -it app_backend bash -c "composer install && php artisan key:generate && php artisan migrate:fresh --seed && php artisan l5-swagger:generate"
```

### 5. Instale dependências do frontend
```bash
docker exec -it app_frontend sh -c "npm install"
```

## 🌐 Acessos

- **Frontend**: http://localhost:5173
- **API**: http://localhost:8000/api
- **Swagger**: http://localhost:8000/api/documentation

**Credenciais:**
- Email: `admin@sistema.com`
- Senha: `123456`

## 🧪 Rodar Testes
```bash
docker exec -it app_backend bash -c "php artisan test"
```

## 📦 Tecnologias

**Backend:** Laravel 11, MySQL 8, Sanctum, PEST, Swagger  
**Frontend:** Vue 3, Vite, Tailwind, Flowbite  
**Infra:** Docker, Nginx, PHP 8.2

## 🛠️ Comandos Úteis
```bash
# Ver logs
docker compose logs -f

# Parar containers
docker compose down

# Resetar banco de dados
docker exec -it app_backend bash -c "php artisan migrate:fresh --seed"

# Reconstruir tudo
docker compose down && docker compose up -d --build
```