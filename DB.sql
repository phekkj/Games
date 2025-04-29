create database games;
use games;

create table usuarios (
    id int not null auto_increment,
    nome varchar(100) not null,
    email varchar(100) not null unique,
    senha_hash varchar(255) not null,
    primary key (id)
) engine=InnoDB;

create table categorias (
    id int not null auto_increment,
    nome varchar(50) not null unique,
    primary key (id)
) engine=InnoDB;

create table jogos (
    id int not null auto_increment,
    nome varchar(100) not null,
    descricao text,
    ano_criacao year not null,
    preco decimal(10,2) not null default 0.00,
    total_vendas int not null default 0,
    primary key (id)
) engine=InnoDB;

create table jogos_categorias (
    id int not null auto_increment,
    jogo_id int not null,
    categoria_id int not null,
    primary key (id),
    foreign key (jogo_id) references jogos(id) on delete cascade on update cascade,
    foreign key (categoria_id) references categorias(id) on delete cascade on update cascade
) engine=InnoDB;

create table compras (
    id int not null auto_increment,
    usuario_id int not null,
    jogo_id int not null,
    data_compra datetime not null default current_timestamp,
    quantidade int not null default 1,
    preco_unitario decimal(10,2) not null,
    primary key (id),
    foreign key (usuario_id) references usuarios(id) on delete restrict on update cascade,
    foreign key (jogo_id) references jogos(id) on delete restrict on update cascade
) engine=InnoDB;

drop trigger if exists after_insert_compra;
delimiter $$
create trigger after_insert_compra
after insert on compras
for each row
begin
    update jogos
    set total_vendas = total_vendas + new.quantidade
    where id = new.jogo_id;
end$$
delimiter ;

drop trigger if exists after_delete_compra;
delimiter $$
create trigger after_delete_compra
after delete on compras
for each row
begin
    update jogos
    set total_vendas = total_vendas - old.quantidade
    where id = old.jogo_id;
end$$
delimiter ;

drop procedure if exists sp_registrar_compra;
delimiter $$
create procedure sp_registrar_compra(
    in p_usuario_id int,
    in p_jogo_id int,
    in p_quantidade int,
    in p_preco_unitario decimal(10,2)
)
begin
    insert into compras (usuario_id, jogo_id, quantidade, preco_unitario)
    values (p_usuario_id, p_jogo_id, p_quantidade, p_preco_unitario);
end$$
delimiter ;
