# Usar uma imagem base do Node.js
FROM node:20

# Definir o diretório de trabalho
WORKDIR /app

# Copiar os arquivos de dependência antes do código para usar o cache de build
COPY package*.json ./

# Instalar as dependências
RUN npm install

# Copiar o restante do código da aplicação
COPY . .
RUN npm install axios
RUN npm install jszip
# Expor a porta usada pelo servidor Vue.js
EXPOSE 5173

# Comando para rodar o servidor de desenvolvimento Vue.js
CMD ["npm", "run", "dev"]
