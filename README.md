# desafio_gerenciamento
Desafio de gerenciamento de pessoas utilizando Vue + Laravel + Docker

Este é um projeto de teste técnico para uma vaga de desenvolvedor, implementando um sistema simples de gerenciamento de pessoas (física e jurídica). O frontend é desenvolvido em Vue.js e o backend em Laravel, com containerização via Docker.

## Tecnologias Utilizadas
- **Frontend**: Vue.js
- **Backend**: Laravel (PHP)
- **Banco de Dados**: MySQL
- **Containerização**: Docker e Docker Compose

## Como Rodar o Projeto

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/GuilhermeSoares009/desafio_gerenciamento
   cd desafio_gerenciamento
   ```

2. **Suba os containers com Docker Compose**:
   ```bash
   docker-compose up --build
   ```

3. **Acesse a aplicação**:
   - Frontend: http://localhost:5173
   - Backend API: http://localhost:8000

4. **Credenciais de login**:
   - Email: admin@sistema.com
   - Senha: 123456

5. **Documentação da APi**:
   - Swagger: http://localhost:8000/docs

## Funcionalidades
- Login simples (mock)
- Listagem, criação, edição e exclusão de pessoas (física/jurídica)
- API REST com autenticação via Sanctum
