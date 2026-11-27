use stockeasy;

create table produtos(
id_produto int not null primary key auto_increment,
nome varchar(255) not null,
descricao text,
preco decimal(10,2) not null,
quantidade int not null,
estoque_minimo int not null,
data_criacao datetime default current_timestamp,

constraint quantidade_positiva check (quantidade >= 0),
constraint estoque_positivo check (estoque_minimo >= 0),
constraint preco_positivo check (preco > 0)

);
