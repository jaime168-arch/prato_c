create database restaurante_icaro;
use restaurante_icaro;

create table usuarios (
    id int primary key auto_increment,
    nome varchar(100) not null,
    email varchar(100) not null
);

create table pratos (
    id int primary key auto_increment,
    nome varchar(100) not null,
    descricao text not null,
    preco decimal(10,2) not null,
    categoria varchar(50) not null,

    id_usuario int not null,
    foreign key (id_usuario) references usuarios(id)
);