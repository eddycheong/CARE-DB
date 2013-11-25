CREATE TABLE Employee (
eid int,
ename char(50),
sin char(9),
loginID char(20),
password char(50),
PRIMARY KEY (eid));

CREATE TABLE Doctor (
eid int,
ename char(50),
sin char(9),
loginID char(20),
password char(50),
qualification char(30),
license char(6),
PRIMARY KEY (eid));

CREATE TABLE Patient(
pid integer, 
pname char(50), 
address char(40), 
phone char(10), 
email char(30), 
carecard char(10),
PRIMARY KEY (pid));

CREATE TABLE Appointment(
eid int,
time timestamp(0),
fee int,
pid int,
PRIMARY KEY (eid, time),
FOREIGN KEY (eid) REFERENCES Doctor,
FOREIGN KEY (pid) REFERENCES Patient);

CREATE TABLE Insurance (
type char(10),
cname char(20),
PRIMARY KEY (type, cname));

CREATE TABLE CoveredBy (
pid int,
type char(10),
cname char(50),
cost integer,
PRIMARY KEY( pid, type, cName),
FOREIGN KEY (pid) REFERENCES Patient);

CREATE TABLE Diagnose (
eid int,
pid int,
prescription char(50),
PRIMARY KEY (eid, pid),
FOREIGN KEY (eid) REFERENCES Doctor,
FOREIGN KEY (pid) REFERENCES Patient);

CREATE TABLE Payment (
eid int, 
time timestamp, 
adate date, 
pid int, 
type char(10),
PRIMARY KEY (eid, time, aDate, pid),
FOREIGN KEY (eid, aDate, time) REFERENCES appointment,
FOREIGN KEY (pid) REFERENCES patient);

CREATE TABLE Schedule(
reid int,
deid int,
pid int,
time timestamp(0),
primary key(reid, deid, time),
foreign key (reid) references employee,
foreign key (pid) references patient,
foreign key (deid, time) references appointment on delete cascade);

CREATE TABLE has_fHistory(
pid int,
pname char(50),
fName char(50),
relation char(20),
condition char(20),
PRIMARY KEY (pid, pName, fName, condition),
FORIEGN KEY (pid, pName) REFERENCES has_MedicalRecords)

CREATE TABLE contains_pHistory(
pid int,
pname char(50),
pDate date,
condition char(20),
medication char(50),
PRIMARY KEY (pid, pname, pDate, condition),
FORIEGN KEY (pid, pname) REFERENCES has_MedicalRecords)

CREATE TABLE has_MedicalRecords(
pid int,
pname char(50),
allergies char(30),
emerContacts char(50),
PRIMARY KEY (pid, pname),
FOREIGN KEY (pid) REFERENCES Patient)
