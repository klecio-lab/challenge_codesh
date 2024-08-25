# Challenge Codesh Laravel

Este projeto é um desafio proposto pela Coodesh, desenvolvido em Laravel para demonstrar habilidades em gerenciamento de dados e criação de APIs.

## Tecnologias Utilizadas

- **Linguagem**: PHP
- **Framework**: Laravel 10
- **Banco de Dados**: PostgreSQL
- **Outras Tecnologias**: Docker, Redis, Laravel Sail, Composer

## Como Instalar e Usar

### Pré-requisitos

Certifique-se de ter as seguintes ferramentas instaladas:

- **Composer**: Para gerenciar dependências em projetos PHP.
- **PHP**: Versão 8.2.
- **Docker**: Para criar e gerenciar containers.
- **Git**: Para versionamento e controle de código.



### Instalação e Configuração

#### 1. Clonar o Repositório Clone o repositório do projeto e acesse o diretório correspondente: 

```bash 
# Clonar o Repositório do Projeto 
git clone https://github.com/klecio-lab/challenge-codesh-laravel.git 
# Navegar para o Diretório do Projeto 
cd challenge-codesh-laravel
```


#### 2. Instalação do Composer

O Composer é uma ferramenta essencial para gerenciar dependências em projetos PHP. Siga as instruções abaixo para instalá-lo:

```bash
# Baixar e Instalar o Composer
curl -sS https://getcomposer.org/installer | php

# Mover o Composer para o Diretório Executável
sudo mv composer.phar /usr/local/bin/composer

# Verificar a Versão do Composer
composer --version

# Atualizar o Composer
composer self-update
```


#### 3. Instalação do PHP

Atualize o sistema, adicione o repositório de PHP e instale o PHP 8.2 com suas dependências:

```bash
# Atualizar o Sistema
sudo apt update
sudo apt upgrade

# Adicionar o Repositório de PHP
sudo add-apt-repository ppa:ondrej/php
sudo apt update

# Instalar PHP 8.2 e Suas Dependências
sudo apt install php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-pgsql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip

# Verificar a Versão do PHP
php -v
```

#### 4. rodar comando manualmente

Execute os seguintes comandos para configurar e iniciar o projeto:

```bash
# Iniciar os Containers com Laravel Sail
./vendor/bin/sail up

# Parar os Containers
./vendor/bin/sail down

# Rodar as Migrations e Seeders
./vendor/bin/sail artisan migrate:fresh --seed

# Sincronizar Food Facts
./vendor/bin/sail artisan app:sync-food-facts

# Iniciar o Agendador de Tarefas
./vendor/bin/sail artisan schedule:work
```

video explicando projeto
https://drive.google.com/file/d/1dtJ6fEx3oqPjjWZzuHpW7kltg9zlM22d/view?usp=sharing

>  This is a challenge by [Coodesh](https://coodesh.com/)
