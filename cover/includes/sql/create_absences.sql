CREATE TABLE absences (staffCode VARCHAR(3) NOT NULL,
absenceDate date NOT NULL,
p1 TINYINT(1) DEFAULT 1,
p2 TINYINT(1) DEFAULT 1,
p3 TINYINT(1) DEFAULT 1,
p4 TINYINT(1) DEFAULT 1,
p5 TINYINT(1) DEFAULT 1,
p6 TINYINT(1) DEFAULT 1,
PRIMARY KEY (staffCode, absenceDate));
