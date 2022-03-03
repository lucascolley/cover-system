CREATE TABLE covers (coverDate date NOT NULL,
lessonID INT NOT NULL,
coverTeacherCode VARCHAR(3) NOT NULL,
PRIMARY KEY (coverDate, lessonID));
