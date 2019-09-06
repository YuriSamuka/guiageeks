CREATE DATABASE guiageeks24012018
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

use guiageeks24012018;

CREATE TABLE usuario(
	id_usuario INT(5) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(45) NOT NULL,
    email VARCHAR(75) NOT NULL UNIQUE,
    senha VARCHAR(32) NOT NULL,
	sobre_mim VARCHAR(200),
    sexo ENUM('M','F') NOT NULL,
    ultimo_acesso TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    imagem_avatar VARCHAR(200),
    imagem_capa VARCHAR(200),
    PRIMARY KEY(id_usuario)
);

CREATE TABLE relacionamento_amizade_usuario(
	id_usuario_1 INT(5) NOT NULL,
	id_usuario_2 INT(5) NOT NULL,
    data_inicio TIMESTAMP NOT NULL,
    PRIMARY KEY(id_usuario_1, id_usuario_2),
    FOREIGN KEY(id_usuario_1) REFERENCES usuario(id_usuario),
    FOREIGN KEY(id_usuario_2) REFERENCES usuario(id_usuario)
);

CREATE TABLE mensagem(
	id_mensagem INT(10) NOT NULL AUTO_INCREMENT,
	mensagem TEXT,
	data_mensagem TIMESTAMP NOT NULL,
	visualizada BOOLEAN,
	remetente INT(5) NOT NULL,
	destinatario INT(5) NOT NULL,
	PRIMARY KEY(id_mensagem),
	FOREIGN KEY(remetente) REFERENCES usuario(id_usuario),
	FOREIGN KEY(destinatario) REFERENCES usuario(id_usuario)
);

CREATE TABLE anime(
	id_anime INT(10) NOT NULL,
	slug VARCHAR(75) NOT NULL UNIQUE,
	sinopse TEXT,
	titulo_canonico VARCHAR(200),
	titulo_ingles VARCHAR(200),
	titulo_romanizado VARCHAR(200),
	titulo_japones VARCHAR(200),
	status TINYINT(1), 
	indicacao INT(2),
	data_inicio DATE,
	data_fim DATE,
	cover VARCHAR(200),
	poster VARCHAR(200),
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	qtd_temporadas INT(4),
	video_youtube VARCHAR(11),
	PRIMARY KEY(id_conteudo)
);

CREATE TABLE manga(
	id_manga INT(10) NOT NULL,
	slug VARCHAR(75) NOT NULL UNIQUE,
	sinopse TEXT,
	titulo_canonico VARCHAR(200),
	titulo_ingles VARCHAR(200),
	titulo_romanizado VARCHAR(200),
	titulo_japones VARCHAR(200),
	status TINYINT(1), 
	indicacao INT(2),
	data_inicio DATE,
	data_fim DATE,
	cover VARCHAR(200),
	poster VARCHAR(200),
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	qtd_capitulos INT(4),
	qtd_volumes INT(4),
	PRIMARY KEY(id_conteudo)
);

CREATE TABLE item_favorito(
    data_favoritado TIMESTAMP NOT NULL,
	id_conteudo INT(10) NOT NULL,
	id_usuario INT(4) NOT NULL,
    PRIMARY KEY(id_conteudo, id_usuario),
    FOREIGN KEY(id_usuario) REFERENCES usuario(id_usuario),
	FOREIGN KEY(id_conteudo) REFERENCES conteudo_anime_manga(id_conteudo)
);

CREATE TABLE item_assinado(
    data_assinado TIMESTAMP NOT NULL,
	id_conteudo INT(10) NOT NULL,
	id_usuario INT(4) NOT NULL,
    PRIMARY KEY(id_conteudo, id_usuario),
    FOREIGN KEY(id_usuario) REFERENCES usuario(id_usuario),
	FOREIGN KEY(id_conteudo) REFERENCES conteudo_anime_manga(id_conteudo)
);

CREATE TABLE personagem_anime_manga(
	id_personagem INT(10) NOT NULL,
	slug VARCHAR(75) NOT NULL UNIQUE,
	nome VARCHAR(75) NOT NULL,
	descricao TEXT,
	imagem_personagem VARCHAR(200),
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_personagem)
);

CREATE TABLE relacionamento_personagem_anime(
	id_personagem INT(10) NOT NULL,
	id_anime INT(10) NOT NULL,
	PRIMARY KEY(id_personagem, id_anime),
	FOREIGN KEY(id_personagem) REFERENCES personagem_anime_manga(id_personagem),
	FOREIGN KEY(id_anime) REFERENCES anime(id_anime)
);

CREATE TABLE relacionamento_personagem_manga(
	id_personagem INT(10) NOT NULL,
	id_manga INT(10) NOT NULL,
	PRIMARY KEY(id_personagem, id_manga),
	FOREIGN KEY(id_personagem) REFERENCES personagem_anime_manga(id_personagem),
	FOREIGN KEY(id_manga) REFERENCES manga(id_manga)
);

CREATE TABLE episodio_anime(
	id_episodio INT(10) NOT NULL,
	titulo VARCHAR(75),
	numero_episodio INT(10) NOT NULL,
	temporada INT(5),
	sinopse TEXT,
	foi_ao_ar DATE,
	tamanho INT(3),
	thumbnail VARCHAR(200),
	id_conteudo INT(10) NOT NULL,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_episodio),
	FOREIGN KEY(id_anime) REFERENCES anime(id_anime)
);

CREATE TABLE capitulo_manga(
	id_capitulo INT(10) NOT NULL,
	titulo VARCHAR(200),
	numero_capitulo INT(10) NOT NULL,
	volume INT(5),
	sinopse TEXT,
	publicado DATE,
	tamanho INT(3),
	thumbnail VARCHAR(200),
	id_conteudo INT(10) NOT NULL,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_capitulo),
	FOREIGN KEY(id_conteudo) REFERENCES conteudo_anime_manga(id_conteudo)
);

CREATE TABLE comentario(
	id_comentario INT(10) NOT NULL AUTO_INCREMENT,
	comentario VARCHAR(300) NOT NULL,
    data_comentario TIMESTAMP NOT NULL,
    tipo_comentario ENUM('C', 'R') NOT NULL,
	comentario_resposta INT(10),
    PRIMARY KEY(id_comentario),
	FOREIGN KEY(comentario_resposta) REFERENCES comentario(id_comentario)
);

CREATE TABLE editora_hq(
	id_editora INT(10) NOT NULL,
	nome VARCHAR(75),
	descricao TEXT,
	cidade VARCHAR(75),
	estado VARCHAR(75),
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_editora)
);

CREATE TABLE editora_hq_imagem(
	id_editora INT(10) NOT NULL,
	imagem VARCHAR(100),
	PRIMARY KEY(id_editora, imagem),
	FOREIGN KEY(id_editora) REFERENCES editora_hq(id_editora)
);

CREATE TABLE hq_volume(
	id_volume INT(10) NOT NULL,
	nome VARCHAR(75),
	ano INT(4),
	descricao TEXT,
	id_editora INT(10) NOT NULL,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_volume)
);

CREATE TABLE imagem_hq_volume(
	id_volume INT(10) NOT NULL,
	imagem VARCHAR(100),
	PRIMARY KEY(id_volume, imagem),
	FOREIGN KEY(id_volume) REFERENCES hq_volume(id_volume)
);

CREATE TABLE issue(
	id_issue INT(10) NOT NULL,
	numero_issue INT(10),
	nome VARCHAR(75),
	data_criacao_imgs date,
	descricao TEXT,
	id_volume_hq INT(10),
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_issue),
	FOREIGN KEY(id_volume_hq) REFERENCES hq_volume(id_volume)
);

CREATE TABLE issue_imagem(
	id_issue INT(10) NOT NULL,
	imagem VARCHAR(100),
	PRIMARY KEY(id_issue, imagem),
	FOREIGN KEY(id_issue) REFERENCES issue(id_issue)
);

CREATE TABLE team(
	id_team INT(10) NOT NULL,
	nome VARCHAR(75),
	descricao TEXT,
	id_issue_team_dissolvida INT(10) NOT NULL,
	id_issue_primeira_aparicao INT(10) NOT NULL,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_team)
);

CREATE TABLE team_imagem(
	id_team INT(10) NOT NULL,
	imagem VARCHAR(100),
	PRIMARY KEY(id_team, imagem),
	FOREIGN KEY(id_team) REFERENCES team(id_team)
);

CREATE TABLE arco_historia(
	id_arco_historia INT(10) NOT NULL,
	dack VARCHAR(200),
	aliases VARCHAR(75),
	nome VARCHAR(75),
	descricao TEXT,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_arco_historia)
);

CREATE TABLE arco_historia_issue(
	id_arco_historia INT(10) NOT NULL,
	id_issue INT(10) NOT NULL,
	PRIMARY KEY(id_arco_historia, id_issue),
	FOREIGN KEY(id_arco_historia) REFERENCES arco_historia(id_arco_historia),
	FOREIGN KEY(id_issue) REFERENCES issue(id_issue)
);

CREATE TABLE team_apareceu_issue(
	id_team INT(10) NOT NULL,
	id_issue INT(10) NOT NULL,
	PRIMARY KEY(id_team, id_issue),
	FOREIGN KEY(id_team) REFERENCES team(id_team),
	FOREIGN KEY(id_issue) REFERENCES issue(id_issue)
);

CREATE TABLE personagem_hq(
	id_personagem INT(10) NOT NULL,
	nome_real VARCHAR(75),
	genero ENUM('m', 'f', 'o'),
	nascimento DATE,
	descricao TEXT,
	id_issue_primeira_aparicao INT(10) NOT NULL,
	id_origem INT(10) NOT NULL,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_personagem)
);

CREATE TABLE personagem_hq_imagem(
	id_personagem INT(10) NOT NULL,
	imagem VARCHAR(100),
	PRIMARY KEY(id_personagem, imagem),
	FOREIGN KEY(id_personagem) REFERENCES personagem_hq(id_personagem)
);

CREATE TABLE issue_morreu_personagem(
	id_personagem INT(10) NOT NULL,
	id_issue INT(10) NOT NULL,
	PRIMARY KEY(id_personagem, id_issue),
	FOREIGN KEY(id_issue) REFERENCES issue(id_issue),
	FOREIGN KEY(id_personagem) REFERENCES personagem_hq(id_personagem)
);

CREATE TABLE issue_apareceu_personagem(
	id_personagem INT(10) NOT NULL,
	id_issue INT(10) NOT NULL,
	PRIMARY KEY(id_personagem, id_issue),
	FOREIGN KEY(id_issue) REFERENCES issue(id_issue),
	FOREIGN KEY(id_personagem) REFERENCES personagem_hq(id_personagem)
);

CREATE TABLE personagem_afetividade_team(
	id_personagem INT(10) NOT NULL,
	id_team INT(10) NOT NULL,
	afetividade ENUM('a', 'i'), /*a: amiga, i: inimiga*/
	PRIMARY KEY(id_personagem, id_team),
	FOREIGN KEY(id_team) REFERENCES team(id_team),
	FOREIGN KEY(id_personagem) REFERENCES personagem_hq(id_personagem)
);

CREATE TABLE personagem_participa_team(
	id_personagem INT(10) NOT NULL,
	id_team INT(10) NOT NULL,
	PRIMARY KEY(id_personagem, id_team),
	FOREIGN KEY(id_team) REFERENCES team(id_team),
	FOREIGN KEY(id_personagem) REFERENCES personagem_hq(id_personagem)
);

CREATE TABLE poderes(
	id_poder INT(10) NOT NULL,
	nome VARCHAR(75),
	descricao TEXT,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_poder)
);

CREATE TABLE poderes_personagem(
	id_personagem INT(10) NOT NULL,
	id_poder INT(10) NOT NULL,
	PRIMARY KEY(id_personagem, id_poder),
	FOREIGN KEY(id_poder) REFERENCES poderes(id_poder),
	FOREIGN KEY(id_personagem) REFERENCES personagem_hq(id_personagem)
);

CREATE TABLE origem_personagem_hq(
	id_origem INT(10) NOT NULL,
	nome VARCHAR(75),
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_origem)
);

CREATE TABLE criador_hq(
	id_criador INT(10) NOT NULL,
	descricao TEXT,
	nascimento DATE,
	genero ENUM('m', 'f'),
	nome VARCHAR(75),
	pais VARCHAR(75),
	morte DATE,
	cidade VARCHAR(75),
	website VARCHAR(200),
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_criador)
);

CREATE TABLE criador_hq_imagem(
	id_criador INT(10) NOT NULL,
	imagem VARCHAR(100),
	PRIMARY KEY(id_criador, imagem),
	FOREIGN KEY(id_criador) REFERENCES criador_hq(id_criador)
);

CREATE TABLE criador_criou_personagem(
	id_personagem INT(10) NOT NULL,
	id_criador INT(10) NOT NULL,
	PRIMARY KEY(id_personagem, id_criador),
	FOREIGN KEY(id_criador) REFERENCES criador_hq(id_criador),
	FOREIGN KEY(id_personagem) REFERENCES personagem_hq(id_personagem)
);

CREATE TABLE personagem_relacao_personagem(
	id_personagem_1 INT(10) NOT NULL,
	id_personagem_2 INT(10) NOT NULL,
	relacao ENUM('a', 'i'), /*a: amigo, i: inimigo*/
	PRIMARY KEY(id_personagem_1, id_personagem_2),
	FOREIGN KEY(id_personagem_1) REFERENCES personagem_hq(id_personagem),
	FOREIGN KEY(id_personagem_2	) REFERENCES personagem_hq(id_personagem)	
);

CREATE TABLE producers_anime_manga(
	id_producer INT(10) NOT NULL,
	slug VARCHAR(75),
	nome VARCHAR(75),
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_producer)
);

CREATE TABLE relacionamento_producer_anime(
	id_producer INT(10) NOT NULL,
	id_anime INT(10) NOT NULL,
	PRIMARY KEY(id_producer, id_anime),
	FOREIGN KEY(id_producer) REFERENCES producers_anime_manga(id_producer),
	FOREIGN KEY(id_anime) REFERENCES anime(id_anime)	
);

CREATE TABLE visualizacao_conteudo(
	id_conteudo INT(10) NOT NULL,
    tipo_conteudo ENUM('a', 'm', 'h') NOT NULL,
    visualizacao INT(15) default 0,
    PRIMARY KEY(id_conteudo, tipo_conteudo)
);

CREATE TABLE generos_anime_manga(
	id_genero INT(4) NOT NULL,
	nome VARCHAR(75) NOT NULL,
    slug VARCHAR(75) NOT NULL,
    descricao TEXT,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_genero)
);

CREATE TABLE relacionamento_anime_manga(
	id_anime INT(10) NOT NULL,
	id_manga INT(10) NOT NULL,
	tipo_relacionamento VARCHAR(75),
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_anime, id_manga),
    FOREIGN KEY(id_anime) REFERENCES anime(id_anime),
    FOREIGN KEY(id_manga) REFERENCES manga(id_manga)
);

CREATE TABLE relacionamento_anime_anime(
	id_anime_1 INT(10) NOT NULL,
	id_anime_2 INT(10) NOT NULL,
	tipo_relacionamento VARCHAR(75),
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_anime_1, id_anime_2),
    FOREIGN KEY(id_anime_1) REFERENCES anime(id_anime),
    FOREIGN KEY(id_anime_2) REFERENCES anime(id_anime)
);

CREATE TABLE relacionamento_manga_manga(
	id_manga_1 INT(10) NOT NULL,
	id_manga_2 INT(10) NOT NULL,
	tipo_relacionamento VARCHAR(75),
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(id_manga_1, id_manga_2),
    FOREIGN KEY(id_manga_1) REFERENCES manga(id_manga_1),
    FOREIGN KEY(id_manga_2) REFERENCES manga(id_manga_2)
);

CREATE TABLE relacionamento_genero_anime_manga(
	id_genero INT(4) NOT NULL,
    id_anime INT(10) NOT NULL,
    PRIMARY KEY(id_genero, id_anime),
    FOREIGN KEY(id_genero) REFERENCES generos_anime_manga(id_genero),
    FOREIGN KEY(id_anime) REFERENCES anime(id_anime)
);