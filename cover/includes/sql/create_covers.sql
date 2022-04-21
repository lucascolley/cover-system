CREATE TABLE covers (coverDate date NOT NULL,
lessonID INT NOT NULL,
coverStaffCode VARCHAR(3) NOT NULL,
period TINYINT(1) NOT NULL,
room VARCHAR(3) NOT NULL,
staffCode VARCHAR(3) NOT NULL,
classCode VARCHAR(10),
PRIMARY KEY (coverDate, lessonID));
