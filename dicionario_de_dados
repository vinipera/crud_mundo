# Dicionário de Dados — Banco de Dados `crud_mundo`

O banco de dados **`crud_mundo`** tem como objetivo armazenar informações sobre **países** e **cidades**, incluindo detalhes sobre os países e climáticos sobre as cidades.  
É composto por duas tabelas principais: `paises` e `cidades`, ligadas por uma relação de **chave estrangeira (1:N)**, em que um país pode ter várias cidades.

---

## 🌍 **Tabela: `paises`**

| **Campo** | **Tipo de Dado** | **Tamanho / Precisão** | **Nulo?** | **Chave** | **Descrição** |
|------------|------------------|------------------------|------------|------------|----------------|
| `id_pais` | INT | — | não | **PK** | Identificador único do país (gerado automaticamente). |
| `nome_pais` | VARCHAR | 120 | não | — | Nome oficial do país. |
| `continente` | VARCHAR | 120 | não | — | Nome do continente ao qual o país pertence. |
| `populacao_pais` | BIGINT | — | não | — | Número total de habitantes do país. |
| `idioma` | VARCHAR | 120 | não | — | Idioma principal falado no país. |
| `capital` | VARCHAR | 100 | pode ser | — | Nome da capital do país. |
| `moeda` | VARCHAR | 50 | pode ser | — | Nome da moeda oficial do país. |
| `bandeira` | VARCHAR | 255 | pode ser | — | Caminho ou URL da imagem da bandeira do país. |
| `sigla` | VARCHAR | 5 | pode ser | — | Código ou abreviação do país (ex: BRA, USA, FRA). |

---

## 🏙️ **Tabela: `cidades`**

| **Campo** | **Tipo de Dado** | **Tamanho / Precisão** | **Nulo?** | **Chave** | **Descrição** |
|------------|------------------|------------------------|------------|------------|----------------|
| `id_cidade` | INT | — | não | **PK** | Identificador único da cidade (gerado automaticamente). |
| `nome_cidade` | VARCHAR | 120 | não | — | Nome da cidade. |
| `populacao_cidade` | BIGINT | — | não | — | Número de habitantes da cidade. |
| `id_pais` | INT | — | não | **FK → paises(id_pais)** | Chave estrangeira que indica a qual país a cidade pertence. |
| `latitude` | DECIMAL | (10,8) | pode ser | — | Coordenada geográfica de latitude da cidade. |
| `longitude` | DECIMAL | (11,8) | pode ser | — | Coordenada geográfica de longitude da cidade. |

---

## 🔗 **Relacionamento entre as Tabelas**

| **Tabela 1** | **Campo** | **Tabela 2** | **Campo** | **Tipo de Relação** |
|---------------|------------|---------------|------------|----------------------|
| `paises` | `id_pais` | `cidades` | `id_pais` | 1:N (Um país possui várias cidades) |

---

