CREATE TABLE lessons (lessonID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
teacherEmail VARCHAR(255) NOT NULL,
classCode VARCHAR(7),
week TINYINT(1),
day VARCHAR(3),
period TINYINT(1),
room VARCHAR(3));
