CREATE TABLE poule(
   poule_id VARCHAR(50) ,
   PRIMARY KEY(poule_id)
);

CREATE TABLE matchs(
   match_id VARCHAR(50) ,
   match_date TIMESTAMP,
   is_tournament BOOLEAN,
   PRIMARY KEY(match_id)
);

CREATE TABLE team(
   team_id VARCHAR(50) ,
   name VARCHAR(50) ,
   poule_id VARCHAR(50) ,
   PRIMARY KEY(team_id),
   FOREIGN KEY(poule_id) REFERENCES poule(poule_id)
);

CREATE TABLE team_invite(
   token VARCHAR(50) ,
   team_id VARCHAR(50)  NOT NULL,
   PRIMARY KEY(token),
   FOREIGN KEY(team_id) REFERENCES team(team_id)
);

CREATE TABLE game(
   game_id VARCHAR(50) ,
   a_score INTEGER,
   b_score INTEGER,
   match_id VARCHAR(50)  NOT NULL,
   team_id_b VARCHAR(50)  NOT NULL,
   team_id_a VARCHAR(50)  NOT NULL,
   PRIMARY KEY(game_id),
   FOREIGN KEY(match_id) REFERENCES matchs(match_id),
   FOREIGN KEY(team_id_b) REFERENCES team(team_id),
   FOREIGN KEY(team_id_a) REFERENCES team(team_id)
);

CREATE TABLE player(
   discord_id VARCHAR(50) ,
   tag VARCHAR(50) ,
   pseudo VARCHAR(50) ,
   avatar VARCHAR(255) ,
   is_leader BOOLEAN,
   riot_id VARCHAR(255) ,
   mmr INTEGER,
   team_id VARCHAR(50),
   PRIMARY KEY(discord_id),
   FOREIGN KEY(team_id) REFERENCES team(team_id)
);
