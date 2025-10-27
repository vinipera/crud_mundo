# CRUD Mundo – Sistema de Gerenciamento de Países e Cidades
Feito por **Vinícius Pereira de Morais** - 3°DS

---

## Sobre o Projeto

O **CRUD Mundo** foi desenvolvido como parte da disciplina de **Programação Web** do curso de **Desenvolvimento de Sistemas**, com o objetivo de criar uma aplicação web funcional que permita gerenciar dados sobre **países e cidades do mundo**.

A aplicação conta com um **CRUD completo** (Create, Read, Update, Delete), interface responsiva e integração com APIs para enriquecer as informações exibidas. O foco está em boas práticas de desenvolvimento, separação de camadas, usabilidade e eficiência no acesso aos dados.

---

## Funcionalidades

**Gerenciamento de Países**  
- Cadastrar novos países com informações completas.  
- Listar todos os países cadastrados.  
- Editar dados existentes.  
- Excluir países (com validação de integridade referencial).  

**Gerenciamento de Cidades**  
- Associar cidades a um país existente.  
- Inserir, listar, editar e excluir cidades.  

**Validações e Feedbacks**  
- Validação de formulários no front-end com JavaScript.  
- Alertas e confirmações antes de exclusões.

**Exportação de Dados (Extra)**  
- Exportação de dados para **CSV compatível com Google Sheets e Excel**.

---

## Integrações com APIs

O sistema utiliza **APIs REST** para enriquecer os dados:

**REST Countries**  
- Informações adicionais sobre países (bandeira, moeda, capital etc.)

**OpenWeatherMap**  
- Exibição do clima em tempo real das cidades cadastradas.

---

## Tecnologias Utilizadas

- **HTML** – Estrutura das páginas  
- **CSS** – Estilização e responsividade  
- **JavaScript** – Validações e interações dinâmicas  
- **PHP** – Back-end e integração com o banco  
- **MySQL** – Armazenamento de dados  
- **Git & GitHub** – Controle de versão

---


### 🔧 Passos para rodar localmente

1. Clone este repositório:
   ```bash
   git clone https://github.com/seu-usuario/crud-mundo.git
