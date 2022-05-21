DROP database tcc_app;

CREATE database tcc_app;

\ c tcc_app;

CREATE EXTENSION earthdistance CASCADE;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    nickname character varying(20) NOT NULL,
    birthday date,
    passwd character varying(64) NOT NULL,
    email character varying(60) NOT NULL,
    radius integer,
    description character varying(150),
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

INSERT INTO
    games (name)
VALUES
    ('jogo1');

INSERT INTO
    games (name)
VALUES
    ('jogo2');

INSERT INTO
    games (name)
VALUES
    ('jogo3');

INSERT INTO
    users (nickname, passwd, email, radius)
VALUES
    (
        'spellzito',
        'd3d26f1e61ff157eb2e41f7ef2f6f47f3e67164e440eb27cfe2ee3a3d7e3cd69',
        'spellzito@oleirosoftware.com.br',
        15
    );

INSERT INTO
    users (nickname, passwd, email, radius)
VALUES
    (
        'user1',
        'd3d26f1e61ff157eb2e41f7ef2f6f47f3e67164e440eb27cfe2ee3a3d7e3cd69',
        'user1@oleirosoftware.com.br',
        15
    );

INSERT INTO
    users (nickname, passwd, email, radius)
VALUES
    (
        'user2',
        'd3d26f1e61ff157eb2e41f7ef2f6f47f3e67164e440eb27cfe2ee3a3d7e3cd69',
        'user2@oleirosoftware.com.br',
        15
    );

INSERT INTO
    users (nickname, passwd, email, radius)
VALUES
    (
        'user3',
        'd3d26f1e61ff157eb2e41f7ef2f6f47f3e67164e440eb27cfe2ee3a3d7e3cd69',
        'user3@oleirosoftware.com.br',
        15
    );

INSERT INTO
    users (nickname, passwd, email, radius)
VALUES
    (
        'user4',
        'd3d26f1e61ff157eb2e41f7ef2f6f47f3e67164e440eb27cfe2ee3a3d7e3cd69',
        'user4@oleirosoftware.com.br',
        15
    );

INSERT INTO
    users (nickname, passwd, email, radius)
VALUES
    (
        'user5',
        'd3d26f1e61ff157eb2e41f7ef2f6f47f3e67164e440eb27cfe2ee3a3d7e3cd69',
        'user5@oleirosoftware.com.br',
        15
    );

INSERT INTO
    users (nickname, passwd, email, radius)
VALUES
    (
        'user6',
        'd3d26f1e61ff157eb2e41f7ef2f6f47f3e67164e440eb27cfe2ee3a3d7e3cd69',
        'user6@oleirosoftware.com.br',
        15
    );

INSERT INTO
    users (nickname, passwd, email, radius)
VALUES
    (
        'user7',
        'd3d26f1e61ff157eb2e41f7ef2f6f47f3e67164e440eb27cfe2ee3a3d7e3cd69',
        'user7@oleirosoftware.com.br',
        15
    );

INSERT INTO
    users (nickname, passwd, email, radius)
VALUES
    (
        'user8',
        'd3d26f1e61ff157eb2e41f7ef2f6f47f3e67164e440eb27cfe2ee3a3d7e3cd69',
        'user8@oleirosoftware.com.br',
        15
    );

CREATE TABLE positions(
    id SERIAL PRIMARY KEY,
    id_user INTEGER,
    lat NUMERIC(23, 20),
    long NUMERIC(23, 20),
    time TIMESTAMP DEFAULT NOW()
);

CREATE
OR REPLACE FUNCTION delete_old_rows() RETURNS trigger LANGUAGE plpgsql AS $$ BEGIN
DELETE FROM
    positions
WHERE
    time < CURRENT_TIMESTAMP - INTERVAL '1 hour';

RETURN NULL;

END;

$$;

CREATE TRIGGER trigger_delete_old_rows
AFTER
INSERT
    ON positions EXECUTE PROCEDURE delete_old_rows();

-- SELECT *,ROUND(earth_distance(ll_to_earth(-32.179610591118, -52.153114242071), ll_to_earth(lat, long)) :: NUMERIC,2) AS distance
-- FROM positions WHERE earth_box(ll_to_earth (-32.179610591118, -52.153114242071), 10000) @> ll_to_earth (lat, long)
--                  AND earth_distance(ll_to_earth (-32.179610591118, -52.153114242071), ll_to_earth (lat, long)) < 10000
--                  AND earth_distance(ll_to_earth (-32.179610591118, -52.153114242071), ll_to_earth (lat, long)) > 0
-- ORDER BY distance;