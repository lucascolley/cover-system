CREATE TABLE users (
usersEmail VARCHAR(255) PRIMARY KEY NOT NULL,
usersPwd VARCHAR(64) DEFAULT "$2y$10$ahWSLqMBx0Z.trlh6x3x9ejA8vSrdGiVB72VVcSmjgVE2ui5ejA7u",
usersTitle VARCHAR(4),
usersForename VARCHAR(255),
usersSurname VARCHAR(255),
usersStaffCode VARCHAR(3),
usersAdmin TINYINT(1) DEFAULT 0,
usersTeacher TINYINT(1) DEFAULT 1,
usersSLT TINYINT(1) DEFAULT 0) -- SLT means Senior Leadership Team
CHARACTER SET latin1 COLLATE latin1_general_cs;
