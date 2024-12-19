# Usar uma imagem base do Node.js
FROM node:20


# Definir o diretório de trabalho
WORKDIR /app

# Copiar o package.json e package-lock.json (se existir)
COPY package*.json ./

# Instalar dependências do npm
RUN npm install

# Copiar o restante do código da aplicação
COPY . .


RUN npm install axios

EXPOSE 5173

# Comando para rodar o servidor de desenvolvimento Vue.js
CMD ["npm", "run", "dev"]
