create database Airport;
use Airport;

create table Horario(
	data date not null,
	hora time not null,
	primary key (data, hora)
);

create table ModeloAeronave(
  modelo			varchar(50)  not null,
  qtd_tripulacao	int			 not null,
  qtd_passageiro	int			 not null,
  primary key (modelo)
);

create table Aeroporto(
	codigo	int unsigned not null auto_increment,
	nome	varchar(200) not null,
	cidade	varchar(100) not null,
	estado	varchar(50)  not null,
	pais	varchar(50)  not null,
	primary key (codigo)
);

create table Companhia(
	codigo	int unsigned not null auto_increment,
	sigla	varchar(3)	 not null check (length(sigla)=3),
	nome	varchar(200) not null,
	primary key (codigo)
);

create table Voo(
	codigo			int unsigned not null auto_increment,
	status			varchar(10)	 not null check (status in ('ativo','confirmado','cancelado','finalizado')),
	data_partida	date		 not null,
	hora_partida	time		 not null,
	data_chegada	date		 not null,
	hora_chegada	time		 not null,
	modelo_aeronave	varchar(50)  not null,
	cod_companhia	int unsigned not null,
	cod_origem		int unsigned not null,
	cod_destino		int unsigned not null,
	foreign key (data_partida, hora_partida) references Horario(data, hora),
	foreign key (data_chegada, hora_chegada) references Horario(data, hora),
	foreign key (modelo_aeronave)			 references ModeloAeronave(modelo) on update cascade on delete restrict,
	foreign key (cod_companhia)				 references Companhia(codigo)	   on update cascade on delete restrict,
	foreign key (cod_origem)				 references Aeroporto(codigo)	   on update cascade on delete restrict,
	foreign key (cod_destino)				 references Aeroporto(codigo)	   on update cascade on delete restrict,
	primary key (codigo)
);

create table Escala(
	cod_voo			int unsigned not null,
	cod_aeroporto	int unsigned not null,
	foreign key (cod_voo)		references Voo(codigo)		 on update cascade,
	foreign key (cod_aeroporto) references Aeroporto(codigo) on update cascade on delete restrict,
	primary key (cod_voo, cod_aeroporto)
);