create database crud_mundo;
use crud_mundo;

create table paises (
	id_pais int not null auto_increment,
    nome_pais varchar(120) not null,
    continente varchar(120) not null,
    populacao_pais bigint not null,
    idioma varchar(120) not null,
    primary key(id_pais)
);

create table cidades (
	id_cidade int not null auto_increment,
    nome_cidade varchar(120) not null,
    populacao_cidade bigint not null,
    id_pais int not null,
    primary key(id_cidade),
    foreign key (id_pais) references paises(id_pais)
);

