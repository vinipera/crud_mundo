# 🌍 CRUD Mundo – Sistema de Gerenciamento de Países e Cidades

Bem-vindo ao **CRUD Mundo**, um sistema web completo desenvolvido em **PHP**, **MySQL**, **HTML**, **CSS** e **JavaScript**, projetado para gerenciar dados geográficos do mundo — permitindo cadastrar, listar, editar e excluir **países** e **cidades** com interface amigável e integração com APIs externas.

---

## 📌 Índice

- [📖 Sobre o Projeto](#-sobre-o-projeto)
- [⚙️ Funcionalidades](#️-funcionalidades)
- [🗄️ Estrutura do Banco de Dados](#️-estrutura-do-banco-de-dados)
- [🔌 Integrações com APIs](#-integrações-com-apis)
- [🛠️ Tecnologias Utilizadas](#️-tecnologias-utilizadas)
- [🚀 Como Executar o Projeto](#-como-executar-o-projeto)
- [📂 Estrutura de Pastas](#-estrutura-de-pastas)
- [📊 Funcionalidades Extras (Desafio)](#-funcionalidades-extras-desafio)
- [📸 Capturas de Tela](#-capturas-de-tela)
- [📜 Licença](#-licença)
- [👤 Autor](#-autor)

---

## 📖 Sobre o Projeto

O **CRUD Mundo** foi desenvolvido como parte da disciplina de **Programação Web** do curso de **Desenvolvimento de Sistemas**, com o objetivo de criar uma aplicação web funcional que permita gerenciar dados sobre **países e cidades do mundo**.

A aplicação conta com um **CRUD completo** (Create, Read, Update, Delete), interface responsiva e integração com APIs para enriquecer as informações exibidas. O foco está em boas práticas de desenvolvimento, separação de camadas, usabilidade e eficiência no acesso aos dados.

---

## ⚙️ Funcionalidades

✅ **Gerenciamento de Países**  
- Cadastrar novos países com informações completas.  
- Listar todos os países cadastrados.  
- Editar dados existentes.  
- Excluir países (com validação de integridade referencial).  

✅ **Gerenciamento de Cidades**  
- Associar cidades a um país existente.  
- Inserir, listar, editar e excluir cidades.  

✅ **Validações e Feedbacks**  
- Validação de formulários no front-end com JavaScript.  
- Alertas e confirmações antes de exclusões.

✅ **Exportação de Dados (Extra)**  
- Exportação de dados para **CSV compatível com Google Sheets e Excel**.

---

## 🗄️ Estrutura do Banco de Dados

Banco de dados: `bd_mundo`  

**Tabelas principais:**

### 🗺️ paises
| Campo        | Tipo         | Descrição                      |
|--------------|--------------|------------------------------|
| id_pais      | INT (PK)     | Identificador do país        |
| nome         | VARCHAR(100) | Nome oficial                 |
| continente   | VARCHAR(50)  | Continente                   |
| populacao    | INT          | População total             |
| idioma       | VARCHAR(50)  | Idioma principal            |

### 🏙️ cidades
| Campo        | Tipo         | Descrição                      |
|--------------|--------------|------------------------------|
| id_cidade    | INT (PK)     | Identificador da cidade      |
| nome         | VARCHAR(100) | Nome da cidade              |
| populacao    | INT          | População da cidade        |
| id_pais      | INT (FK)     | Relacionamento com `paises` |

🔗 Relação: **1:N** (um país possui várias cidades)

---

## 🔌 Integrações com APIs

O sistema utiliza **APIs REST** para enriquecer os dados:

🌐 **REST Countries**  
- Informações adicionais sobre países (bandeira, moeda, capital etc.)

☁️ **OpenWeatherMap**  
- Exibição do clima em tempo real das cidades cadastradas.

---

## 🛠️ Tecnologias Utilizadas

- **HTML5** – Estrutura das páginas  
- **CSS3** – Estilização e responsividade  
- **JavaScript (ES6+)** – Validações e interações dinâmicas  
- **PHP 8+** – Back-end e integração com o banco  
- **MySQL** – Armazenamento de dados  
- **cURL / REST** – Consumo de APIs externas  
- **Git & GitHub** – Controle de versão

---

## 🚀 Como Executar o Projeto

### 🧱 Pré-requisitos

- [XAMPP](https://www.apachefriends.org/) ou outro servidor PHP/MySQL  
- PHP 8.0+  
- MySQL 5.7+  
- Navegador atualizado

### 🔧 Passos para rodar localmente

1. Clone este repositório:
   ```bash
   git clone https://github.com/seu-usuario/crud-mundo.git
