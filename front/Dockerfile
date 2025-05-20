FROM node:18-alpine

# Criar usuário não-root
RUN addgroup -S appgroup && adduser -S appuser -G appgroup

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar arquivos de dependências
COPY package*.json ./

# Instalar dependências
RUN npm install

# Copiar o resto do código
COPY . .

# Expor porta
EXPOSE 8080

# Mudar para usuário não-root
USER appuser

# Comando para iniciar a aplicação
CMD ["npm", "run", "serve"] 