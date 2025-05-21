# Projeto TRAY

Este é um projeto full-stack que consiste em uma API Laravel e um frontend Vue.js.

## 🚀 Tecnologias Utilizadas

### Backend (API)
- Laravel 12
- PHP 8.2
- MySQL 8.0
- Redis (para filas)
- Nginx

### Frontend
- Vue 3
- PrimeVue
- TailwindCSS

## 📋 Pré-requisitos

- Docker
- Docker Compose
- Git

## 🔧 Instalação e Configuração

1. Clone o repositório:
```bash
git clone https://github.com/gustavonunes01/tray-teste
cd tray-teste
```

2. Configure as variáveis de ambiente:

Backend (api/.env):
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=tray
DB_USERNAME=tray
DB_PASSWORD=root

QUEUE_CONNECTION=redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Frontend (front/.env):
```env
VUE_APP_API_URL=http://localhost/api
```

3. Inicie os containers:
```bash
docker-compose up -d --build
```

4. Instale as dependências do Laravel:
```bash
docker-compose exec api composer install
```

5. Gere a chave da aplicação:
```bash
docker-compose exec api php artisan key:generate
```

6. Execute as migrações e seeders:
```bash
docker-compose exec api php artisan migrate --seed
```

> **Nota**: O container da API já está configurado para executar automaticamente:
> - Composer dump-autoload (para indexar helpers e classes)
> - Migrações do banco de dados
> - Seeders para popular dados iniciais
> - Supervisor para gerenciar workers de fila

## 🌐 Acessando a Aplicação

- Frontend: http://localhost:8080
- API: http://localhost/api
- MySQL: localhost:3306
- Redis: localhost:6379

## 📦 Estrutura do Projeto

```
tray/
├── api/                 # Backend Laravel
│   ├── app/
│   │   ├── Helpers/    # Helpers personalizados
│   │   └── ...
│   ├── config/
│   ├── database/
│   │   ├── migrations/  # Estrutura do banco
│   │   └── seeders/     # Dados iniciais
│   └── ...
├── front/              # Frontend Vue.js
│   ├── src/
│   ├── public/
│   └── ...
└── docker-compose.yml
```

## 🔄 Comandos Úteis

### Backend (Laravel)
```bash
# Executar migrações
docker-compose exec api php artisan migrate

# Executar seeders
docker-compose exec api php artisan db:seed

# Limpar cache
docker-compose exec api php artisan cache:clear

# Listar rotas
docker-compose exec api php artisan route:list

# Recriar banco de dados com seeders
docker-compose exec api php artisan migrate:fresh --seed

# Atualizar autoload do composer
docker-compose exec api composer dump-autoload
```

### Frontend (Vue.js)
```bash
# Instalar dependências
docker-compose exec front npm install

# Compilar para produção
docker-compose exec front npm run build
```

## 🐛 Debugging

- Logs do Laravel: `api/storage/logs/laravel.log`
- Logs do Nginx: `api/nginx/logs/`
- Logs do Supervisor: `/var/log/supervisor/supervisord.log`

## 📝 Notas Adicionais

- O projeto utiliza Redis para gerenciamento de filas
- Workers de fila são gerenciados automaticamente pelo Supervisor
- O frontend se comunica com a API através do Nginx
- As credenciais padrão do banco de dados são:
  - Database: tray
  - Username: tray
  - Password: root
- O banco de dados é automaticamente populado com dados iniciais durante a primeira execução
- Usuário admin:
  - email: super@admin.teste
  - senha: superadmin123
> **Nota**: E-mail pra teste eu usei o mailtrap para testar as notificações de e-mails.

## 🤝 Contribuição

1. Faça o fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request 