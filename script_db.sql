CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    nickname character varying(20) NOT NULL,
    birthday date,
    passwd character varying(64) NOT NULL,
    email character varying(60) NOT NULL,
    rep integer,
    description character varying(150),
    longitude integer,
    latitude integer,
    photography bytea
);

CREATE TABLE connections (
    id SERIAL PRIMARY KEY,
    id_user1 integer NOT NULL REFERENCES users(id),
    id_user2 integer NOT NULL REFERENCES users(id),
    status character varying(15)
);

CREATE TABLE games (
    id SERIAL PRIMARY KEY,
    name character varying(50) NOT NULL,
    photo bytea
);

CREATE TABLE user_game (
    id SERIAL PRIMARY KEY,
    id_user integer NOT NULL REFERENCES users(id),
    id_game integer NOT NULL REFERENCES games(id)
);

INSERT INTO games (name) VALUES ('jogo1');
INSERT INTO games (name) VALUES ('jogo2');
INSERT INTO games (name) VALUES ('jogo3');

INSERT INTO users (nickname, passwd, email) VALUES ('spellzito','d3d26f1e61ff157eb2e41f7ef2f6f47f3e67164e440eb27cfe2ee3a3d7e3cd69','spellzito@oleirosoftware.com.br');
