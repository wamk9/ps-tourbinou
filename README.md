# Tourbinou - Teste Técnico

## Sobre
O projeto foi desenvolvido baseado em informações prestadas pela empresa Tourbinou, com objetivo de fazer parte da equipe a partir do processo seletivo prestado pela mesma.

Este foi desenvolvido utilizando Laravel + VueJS, junto com alguns pacotes adicionais como Vuex, VueCookies, TailwindCSS, Inertia, ZiggyJS, entre outros...

## Para usuários Linux
Para usuários de Docker no Linux, recomenda-se executar o comando `chmod u+x caminha/do/projeto/docker/php/php_init_db.sh`, pois podem ocorrer problemas no momento de criar o ambiente. 

## Inicializando ambiente
O projeto foi criado utlizando o Docker, como pode ser visto na pasta raiz do mesmo, sendo assim podemos criar nosso ambiente após fazer o git clone utilizando o seguinte comando no terminal, dentro da respectiva pasta do projeto (Lembre-se de fazer a execução do comando com previlégios de administrador):

```
docker-compose up --build -d
```

Após todo o processo ser realizado e ficarmos apenas com os containers PHP e MySQL rodando, podemos realizar algumas configurações para acessar o projeto.

## Configurando .env
Utilize como base o .env.example, copiando seu conteúdo para um arquivo .env que você irá criar dentro da pasta `/tourbinou` e insira aqui as informações que serão utilizadas para rodar o projeto (API + Front-end), as principais nas quais devemos nos preocupar está em relação ao banco de dados e para o token do JWT, abaixo seguem os valores padrões para o banco de dados:

```
DB_CONNECTION=mysql
DB_HOST=mysql-container
DB_PORT=3306
DB_DATABASE=tourbinou
DB_USERNAME=root
DB_PASSWORD=teste123@
```

Lembre-se que ao alterar os dados padrões precisamos também alterar dentro do docker-compose.yml presente na raiz do projeto e DockerFile presente na pasta /docker/mysql

## Gerando token JWT e API Key
Para termos acesso ao projeto, precisaremos executar um gerador de token utilizando o container do PHP, para tal, execute o seguinte comando (com o container em execução):

```
docker exec -it php-container php /var/www/html/tourbinou/artisan key:generate
docker exec -it php-container php /var/www/html/tourbinou/artisan jwt:secret
```

## Acessando o projeto
*Importante:* Após feita todas as configurações acima, deveremos "refazer" o ambiente, para tal, use os comandos abaixo na pasta do projeto:

```
docker-compose down && docker-compose up --build -d
```

Caso esteja com problemas de permissão na pasta, execute o comando abaixo no container do PHP:

```
docker exec -it php-container chown -R www-data:www-data /var/www/
```

Talvez seja necessário também executarmos em alguns casos manualmente o migrate/seed do Laravel, para tal, executaremos o código abaixo:

```
docker exec -it php-container php /var/www/html/tourbinou/artisan migrate
docker exec -it php-container php /var/www/html/tourbinou/artisan db:seed
```

Após realizar as devidas configurações, podemos acessar o projeto com a url http://localhost:8000/, entretanto precisaremos criar os destinos e passeios para preencher a tela inicial com informações junto ao dashboard administrativo (http://localhost:8000/login)

No login preencha com o email `admin@turbinou.com` e senha `123456` para acessar o painel.
