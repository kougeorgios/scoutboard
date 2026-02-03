CREATE DATABASE IF NOT EXISTS analytics
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE analytics;
--  TEAMS TABLE
CREATE TABLE teams (
    id INT NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

--  MATCHES TABLE
CREATE TABLE matches (
  id BIGINT NOT NULL PRIMARY KEY,
  match_date DATETIME,
  home_team INT NOT NULL,
  away_team INT NOT NULL,
  home_score INT,
  away_score INT,

  FOREIGN KEY (home_team) REFERENCES teams(id),
  FOREIGN KEY (away_team) REFERENCES teams(id)
);

CREATE INDEX idx_match_date ON matches (match_date);
CREATE INDEX idx_match_teams ON matches (home_team_id, away_team_id);

