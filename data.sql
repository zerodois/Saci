-- Inserção de Aeroportos
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto de Congonhas','São Paulo','São Paulo','Brasil');
insert into Aeroporto(nome,cidade,estado,pais) values ('Viracopos','Campinas','São Paulo','Brasil');
insert into Aeroporto(nome,cidade,estado,pais) values ('Palácio de Castro','Rio Branco','Acre','Brasil');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto de Santa Maria','Santa Maria','Rio Grande do Sul','Brasil');
insert into Aeroporto(nome,cidade,estado,pais) values ('Galeão','Rio de Janeiro','Rio de Janeiro','Brasil');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto Internacional de Ketchikan','Ketchikan','Alasca','Estados Unidos');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto Parque Nacional do Grand Canyon','Tusayan','Arizona','Estados Unidos');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto de Long Beach','Long Beach','Califórnia','Estados Unidos');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto Internacional de Los Angeles','Los Angeles','Califórnia','Estados Unidos');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto Internacional de Vancouver','Vancouver','Colúmbia Britânica','Canadá');
insert into Aeroporto(nome,cidade,estado,pais) values ('Downsview','Toronto','Ontário','Canadá');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto de Thompson','Thompson','Manitoba','Canadá');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto Internacional Ministro Pistarini','Buenos Aires','Buenos Aires','Argentina');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto de Monte Caseros','Monte Caseros','Corrientes','Argentina');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto Porto Santa Cruz','Porto Santa Cruz','Santa Cruz','Argentina');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto de Barcelona e El Prat','Barcelona','Catalônia','Espanha');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto Quatro Ventos','Madri','Madri','Espanha');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto de Seville','Seville','Andalúsia','Espanha');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto de Hamburgo','Hamburgo','Hamburgo','Alemanha');
insert into Aeroporto(nome,cidade,estado,pais) values ('Aeroporto Sandro Pertini','Turin','Piemonte','Itália');

-- Inserção de Companhias
insert into Companhia(sigla,nome) values('AZL','Azul Brazilian Airlines');
insert into Companhia(sigla,nome) values('TAM','LATAM Airlines');
insert into Companhia(sigla,nome) values('GLO','GOL Linhas Aéreas Inteligentes');
insert into Companhia(sigla,nome) values('ONE','Avianca Brasil');
insert into Companhia(sigla,nome) values('TTL','Total Linhas Aéreas');
insert into Companhia(sigla,nome) values('RIO','Rio Linhas Aéreas');
insert into Companhia(sigla,nome) values('AAY','American Airlines');
insert into Companhia(sigla,nome) values('ASA','Alaska Airlines');
insert into Companhia(sigla,nome) values('DAL','Delta Air Lines');
insert into Companhia(sigla,nome) values('FFT','Frontier Airlines');
insert into Companhia(sigla,nome) values('QXE','Horizon Air');
insert into Companhia(sigla,nome) values('ROU','Air Canada Rouge');
insert into Companhia(sigla,nome) values('GGN','Air Georgian');
insert into Companhia(sigla,nome) values('CRQ','Air Creebec');
insert into Companhia(sigla,nome) values('ASP','AirSprint');
insert into Companhia(sigla,nome) values('BFL','Buffalo Airways');
insert into Companhia(sigla,nome) values('LDE','Líneas Aéreas del Estado');
insert into Companhia(sigla,nome) values('AEA','Air Europa');
insert into Companhia(sigla,nome) values('BER','Air Berlin');
insert into Companhia(sigla,nome) values('AEY','Air Italy');

-- Inserção de Modelos de Aeronave
insert into ModeloAeronave values ('ATR 72',4,78);
insert into ModeloAeronave values ('Airbus A300',4,266);
insert into ModeloAeronave values ('Airbus A380',6,525);
insert into ModeloAeronave values ('Airbus A350 XWB',4,366);
insert into ModeloAeronave values ('Airbus A340',4,375);
insert into ModeloAeronave values ('Embraer E195',4,124);
insert into ModeloAeronave values ('Embraer ERJ 145',3,50);
insert into ModeloAeronave values ('Boeing 787 Dreamliner',6,335);
insert into ModeloAeronave values ('Boeing 777',6,396);
insert into ModeloAeronave values ('Boeing 767',5,375);
insert into ModeloAeronave values ('Boeing 757',4,295);
insert into ModeloAeronave values ('Boeing 737',4,215);

-- Inserção de Horários
insert into Horario values ('2016-11-22','05:45:00');
insert into Horario values ('2016-11-22','07:50:00');
insert into Horario values ('2016-12-13','12:30:00');
insert into Horario values ('2016-12-13','21:15:00');
insert into Horario values ('2016-11-13','11:00:00');
insert into Horario values ('2016-11-14','20:30:00');
insert into Horario values ('2016-11-24','20:10:00');
insert into Horario values ('2016-11-25','10:50:00');
insert into Horario values ('2016-11-01','23:55:00');
insert into Horario values ('2016-11-02','00:40:00');
insert into Horario values ('2016-11-04','18:25:00');
insert into Horario values ('2016-11-04','20:53:00');
insert into Horario values ('2016-11-17','15:45:00');
insert into Horario values ('2016-11-18','02:35:00');
insert into Horario values ('2017-01-05','12:00:00');
insert into Horario values ('2017-01-06','00:10:00');
insert into Horario values ('2016-10-31','20:35:00');
insert into Horario values ('2016-11-01','05:44:00');
insert into Horario values ('2016-11-15','03:45:00');
insert into Horario values ('2016-11-15','09:50:00');

-- Inserção de Voos
insert into Voo(status,data_partida,hora_partida,data_chegada,hora_chegada,modelo_aeronave,cod_companhia,cod_origem,cod_destino) values ('finalizado','2016-11-22','05:45:00','2016-11-22','07:50:00','Airbus A380',2,1,3);
insert into Voo(status,data_partida,hora_partida,data_chegada,hora_chegada,modelo_aeronave,cod_companhia,cod_origem,cod_destino) values ('ativo','2016-12-13','12:30:00','2016-12-13','21:15:00','Boeing 777',7,11,1);
insert into Voo(status,data_partida,hora_partida,data_chegada,hora_chegada,modelo_aeronave,cod_companhia,cod_origem,cod_destino) values ('cancelado','2016-11-13','11:00:00','2016-11-14','20:30:00','Boeing 787 Dreamliner',18,16,13);
insert into Voo(status,data_partida,hora_partida,data_chegada,hora_chegada,modelo_aeronave,cod_companhia,cod_origem,cod_destino) values ('finalizado','2016-11-24','20:10:00','2016-11-25','10:50:00','Embraer E195',1,1,20);
insert into Voo(status,data_partida,hora_partida,data_chegada,hora_chegada,modelo_aeronave,cod_companhia,cod_origem,cod_destino) values ('finalizado','2016-11-01','23:55:00','2016-11-02','00:40:00','Airbus A300',4,2,1);
insert into Voo(status,data_partida,hora_partida,data_chegada,hora_chegada,modelo_aeronave,cod_companhia,cod_origem,cod_destino) values ('finalizado','2016-11-04','18:25:00','2016-11-04','20:53:00','ATR 72',6,5,2);
insert into Voo(status,data_partida,hora_partida,data_chegada,hora_chegada,modelo_aeronave,cod_companhia,cod_origem,cod_destino) values ('cancelado','2016-11-17','15:45:00','2016-11-18','02:35:00','Airbus A350 XWB',17,10,2);
insert into Voo(status,data_partida,hora_partida,data_chegada,hora_chegada,modelo_aeronave,cod_companhia,cod_origem,cod_destino) values ('ativo','2017-01-05','12:00:00','2017-01-06','00:10:00','Boeing 767',2,1,14);
insert into Voo(status,data_partida,hora_partida,data_chegada,hora_chegada,modelo_aeronave,cod_companhia,cod_origem,cod_destino) values ('finalizado','2016-10-31','20:35:00','2016-11-01','05:44:00','Embraer ERJ 145',12,10,13);
insert into Voo(status,data_partida,hora_partida,data_chegada,hora_chegada,modelo_aeronave,cod_companhia,cod_origem,cod_destino) values ('finalizado','2016-11-15','03:45:00','2016-11-15','09:50:00','Embraer E195',5,1,3);

-- Inserção de Escalas
insert into Escala values (2,3);
insert into Escala values (2,8);
insert into Escala values (3,1);
insert into Escala values (4,5);
insert into Escala values (4,18);
insert into Escala values (6,1);
insert into Escala values (7,1);
insert into Escala values (7,6);
insert into Escala values (9,1);
insert into Escala values (10,2);