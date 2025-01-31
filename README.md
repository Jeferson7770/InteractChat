# InteractChat

## Sobre o Projeto

O **InteractChat** é um sistema de bate-papo desenvolvido com **PHP**, **MySQL**, **HTML**, **CSS** e **JavaScript**, permitindo que usuários se cadastrem, façam login e enviem mensagens em tempo real.

## 🚀 Funcionalidades

- ✅ Cadastro e login de usuários
- ✅ Status online dos usuários
- ✅ Envio e recebimento de mensagens
- ✅ Interface simples e intuitiva

## 📌 Pré-requisitos

Antes de instalar e rodar o **InteractChat**, você precisará ter instalado:

- **PHP (>=7.4)**
- **MySQL** ou **MariaDB**
- **Apache** (caso utilize XAMPP ou WAMP)
- **Git** (opcional, para clonar o repositório)
- **phpMyAdmin** (opcional, para gerenciar o banco de dados)

## 🛠️ Instalação e Configuração

### 1️⃣ Clonar o Repositório

git clone https://github.com/Jeferson7770/InteractChat.git

### 2️⃣ Configurar o Banco de Dados

Acesse o phpMyAdmin no navegador:

http://localhost/phpmyadmin/

Crie o banco de dados e execute o seguinte SQL na aba SQL:

CREATE DATABASE chat;

USE chat;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    online DATETIME NOT NULL
);

CREATE TABLE mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    destino VARCHAR(255) NOT NULL,
    texto TEXT NOT NULL,
    datahora DATETIME NOT NULL
);

### 3️⃣ Iniciar o Servidor

Se estiver usando XAMPP, inicie Apache e MySQL.

Caso esteja rodando PHP manualmente, execute:

php -S localhost:8000

Acesse o InteractChat no navegador:

http://localhost/InteractChat/

ou, se estiver usando PHP embutido:

http://localhost:8000/

🔧 Melhorias Futuras

- 🔒 Melhorar segurança (substituir md5() por password_hash() para armazenar senhas)

- 🎨 Melhorar o design com Bootstrap ou Tailwind CSS

- ⚡ Implementar WebSockets para mensagens em tempo real sem polling

## 📌 Autor  

👤 **Jeferson Moreira**  
📧 [jefersonmoreira770@gmail.com](mailto:jefersonmoreira770@gmail.com)  
🔗 [LinkedIn](https://www.linkedin.com/in/jefersonmoreiradev/)  




