insert into produtos
(nome, descricao, preco, quantidade, estoque_minimo)
values
('Teclado Gamer', 'Teclado mecanico RGB', 150.00, 20, 5),
('Mouse Gamer', 'Mouse com 6 botoes e RGB', 89.90, 15, 3),
('Monitor 24 Polegadas', 'Monitor Full HD 144Hz', 799.90, 10, 2),
('Headset Gamer', 'Headset com microfone', 199.90, 8, 2),
('Mousepad Gamer', 'Mousepad grande para jogos', 49.90, 30, 5),
('Webcam HD', 'Webcam com resolucao 720p', 120.00, 12, 3),
('SSD 480GB', 'SSD para armazenamento', 250.00, 6, 2),
('Memoria RAM 8GB', 'Memoria DDR4 3200MHz', 180.00, 4, 2),
('Cabo HDMI', 'Cabo HDMI de 2 metros', 25.00, 50, 10),
('Gabinete Gamer', 'Gabinete com lateral de vidro', 299.90, 7, 2);

insert into movimentacoes
(id_produto, tipo_movimentacao, quantidade, observacao)
values
(1, '1', 10, 'Compra de teclados para o estoque'),
(1, '0', 5, 'Venda de teclados'),
(2, '1', 8, 'Reposicao de mouses'),
(2, '0', 3, 'Venda de mouses'),
(3, '1', 5, 'Compra de monitores'),
(4, '0', 2, 'Venda de headsets'),
(5, '1', 15, 'Entrada de mousepads'),
(6, '0', 4, 'Venda de webcams'),
(7, '1', 3, 'Compra de SSDs'),
(8, '0', 2, 'Venda de memorias RAM');