CREATE TABLE usersNEW (
    Email varchar(255) PRIMARY KEY NOT NULL,
    PasswordHash varchar(64),
    Admin tinyint(1) DEFAULT 0,
    Teacher tinyint(1) DEFAULT 0,
    StaffCode char(3),
    Title varchar(4),
    Forename varchar(255),
    Surname varchar(255)
)
