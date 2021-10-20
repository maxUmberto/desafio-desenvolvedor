FROM node:14

# faz da pasta 'app' o diretório atual de trabalho
WORKDIR /app

# add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

# copia os arquivos 'package.json' e 'package-lock.json' (se disponível)
COPY package*.json ./

# instala dependências do projeto
RUN npm install @vue/cli@4.5.13 -g
RUN npm install

# copia arquivos e pastas para o diretório atual de trabalho (pasta 'app')
COPY . .

# compila a aplicação de produção com minificação
# RUN npm run build

EXPOSE 8080
CMD ["npm", "run", "serve"]