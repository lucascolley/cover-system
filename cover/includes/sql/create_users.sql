CREATE TABLE usersNEW (
    Email varchar(255) PRIMARY KEY NOT NULL,
    PasswordHash varchar(64) DEFAULT $2y$10$6FSGAYS/ERFxl.54LLMB7.7MEo34Y.s5RKYIIda7zLnxJOn22vShW%,
    Title varchar(4),
    Forename varchar(255),
    Surname varchar(255),
    StaffCode char(3),
    Admin tinyint(1) DEFAULT 0,
    Teacher tinyint(1) DEFAULT 1
)
