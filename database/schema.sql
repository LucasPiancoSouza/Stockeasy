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

create table movimentacoes (
id_movimentacao int not null primary key auto_increment,
id_produto int not null,
tipo_movimentacao enum("0","1") not null, -- 0 saida -- 1 entrada 
quantidade int not null, 
data_movimentacao datetime default current_timestamp,
observacao text,

foreign key (id_produto) references produtos(id_produto),
constraint quantidade_movimentacao_positiva check (quantidade > 0)

);

