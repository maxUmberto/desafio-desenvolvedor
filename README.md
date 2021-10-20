# Desafio desenvolvedor pleno Oliveira Trust

Este projeto foi desenvolvido como parte do processo seletivo para desenvolvedor pleno na Oliveira Trust (10/2021)

## Antes de começar
Este projeto foi desenvolvido utilizando **Docker**, então é recomendável que se possua alguma familiaridade com a tecnologia e já o tenha instalado em seu sistema. Caso não conheça ou não tenha instalado, basta seguir esse [tutorial](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-compose-on-ubuntu-20-04-pt).

## Preparando o ambiente

Primeiro vamos clonar o projeto. Abra seu terminal e digite
```bash
git clone https://github.com/maxUmberto/desafio-desenvolvedor.git
```
Quando terminamos de clonar o projeto, entre no diretório `/desafio-desevolvedor/backend`. Dentro deste diretório precisamo criar um arquivo chamado `.env`. Depois de criado, copie o conteúdo do arquivo `.env.example` para dentro de `.env`.  

Se estiver utilizando linux, basta copiar e colar o comando abaixo:
```bash
cd desafio-desenvolvedor/backend && cp .env.example .env
```

## Inicializando o projeto
Como estamos utilizando Docker, a primeira execução é um pouco lenta, uma vez que iremos baixar as imagens que o projeto precisa. As próximas execuções serão mais rápidas.

Na raiz do diretório `/desafio-desenvolvedor`, digite o comando abaixo:
```bash
docker-compose up
```
Com o comando acima você poderá ver o log de execução dos containers do Docker. Recomendo utilizar o comando acima na primeira execução, pois caso aconteça algum erro é mais fácil de identificar o problema. O ponto negativo desse comando é que ele "prende" o seu terminal com os logs dos containers. Caso você queira continuar com seu terminal livre, basta rodar o comando abaixo
```bash
docker-compose up -d
```
O argumento `-d` vai fazer com que os containers rodem em background e não "prendam" seu terminal. Recomendo rodar este comando somente em execuções posteriores.

Aguarde até que todos os containers estejam rodando. Você pode verificar isso digitando
```bash
docker ps
```
Se tudo tiver certo, você irá ver os seguintes containers rodando:
- desafio_ot-front
- desafio_ot-nginx
- desafio_ot-api
- desafio_ot_db
- desafio_ot-redis

Se tudo correu bem até aqui, já está pronto para testar o projeto. Abra seu navegador e navegue até o endereço `http://localhost:8081`. Agora basta criar um usuário e testar a aplicação. ~~E com fé nada vai quebrar~~

## Testando email
Para que o projeto consiga mandar emails, é necessário fazer alguns procedimentos antes. Vamos utilizar seu email pessoal do GMAIL para isso. Como você irá executar este projeto no seu local, não precisa se preocupar de inserir suas informações, elas não ira se tornar públicas ~~mas cuidado com o colega de trabalho passando na tela atrás~~.

Antes, precisamos liberar o uso de App menos seguros na sua conta Google. Basta seguir esse [tutorial](https://support.google.com/accounts/answer/6010255#zippy=%2Cse-a-op%C3%A7%C3%A3o-acesso-a-app-menos-seguro-estiver-desativada-para-sua-conta)

---

Abra o arquivo `.env` e edite as variáveis abaixo com as suas informações

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.googlemail.com
MAIL_PORT=465
MAIL_USERNAME=seu email aqui
MAIL_PASSWORD=sua senha aqui
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=seu email aqui
MAIL_FROM_NAME="${APP_NAME}"
```

Depois de inserir suas informações, rode o comando abaixo

```bash
docker exec -ti desafio_ot-api php artisan queue:work
```

Pronto, agora você deve visualizar os logs dos eventos de envio de email. Basta checar sua caixa de entrada que os emails estarão lá.