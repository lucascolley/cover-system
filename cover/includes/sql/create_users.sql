CREATE TABLE users (
    usersEmail varchar(255) PRIMARY KEY NOT NULL,
    usersPwd varchar(64) DEFAULT "$2y$10$ahWSLqMBx0Z.trlh6x3x9ejA8vSrdGiVB72VVcSmjgVE2ui5ejA7u",
    usersTitle varchar(4),
    usersForename varchar(255),
    usersSurname varchar(255),
    usersStaffCode varchar(3),
    usersAdmin tinyint(1) DEFAULT 0,
    usersTeacher tinyint(1) DEFAULT 1
);
