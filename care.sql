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

CREATE TABLE Schedule(
reid int,
deid int,
pid int,
time timestamp(0),
primary key(reid, deid, time),
foreign key (reid) references employee,
foreign key (pid) references patient,
foreign key (deid, time) references appointment on delete cascade);

CREATE TABLE hasMedicalRecords(
pid int,
pname char(50),
allergies char(30),
emerContacts char(50),
PRIMARY KEY (pid),
FOREIGN KEY (pid) REFERENCES Patient);

CREATE TABLE hasfhistory(
pid int,
pname char(50),
fname char(50),
relation char(20),
condition char(20),
PRIMARY KEY (pid, pname, fname, condition),
FOREIGN KEY (pid) REFERENCES hasMedicalRecords);

CREATE TABLE containspHistory(
pid int,
pname char(50),
pDate char(20),
condition char(20),
medication char(70),
PRIMARY KEY (pid, pname, pDate, condition),
FOREIGN KEY (pid) REFERENCES hasMedicalRecords);


INSERT INTO employee VALUES (2, 'John', '111111111', 'jsmith', 'password');
INSERT INTO employee VALUES (20, 'Jesse Pinkmen', '123123123', 'jpinkmen', 'password');

INSERT INTO doctor VALUES (10, 'Ralph Dovonee', '102829992', 'rdovonee', 'password', 'qualified', '123123');
INSERT INTO doctor VALUES (20, 'Sam', '112112112', 'sfisher', 'password', 'not', '110110');

INSERT INTO employee VALUES (2283, 'Sam Simon', '212998768', 'jsmith','5f4dcc3b5aa765d61d8327deb882cf99');
INSERT INTO employee VALUES (6122, 'Julie Kavner', '151280582', 'jkavner', '25a1669723f671c8254ca1760313b2b5');
INSERT INTO employee VALUES (1121, 'Hank Azaria', '110238914','hazaria','b5884fb8c8785eae504f2816f0451e42');
INSERT INTO employee VALUES (8862,'Nancy Cartwright', '976229178','ncartwright','5b13b76226599ce87037f9b4ca32be22');
INSERT INTO employee VALUES (4605,'Danny Elfman','197287889','delfman','5d907853a9617cfd55fb62eae803595b');

INSERT INTO doctor VALUES (8923,'Bryan Cranston','186719990','bcranston','fed1f37d40a3e8a0103bf5e95875a82f','Doctor of Medicine','228192');
INSERT INTO doctor VALUES (1345,'Aaron Paul','972163229','apaul','316928e0d260556eaccb6627f2ed657b','Doctor of Osteopathic Medicine','550971');
INSERT INTO doctor VALUES (7074,'RJ Mitte','585228090','rmitte','0fd73b3614ede6a8536f1a3fb191abf2','Bachelor of Medicine','518762');
INSERT INTO doctor VALUES (5515,'Dean Norris','558859100','dnorris','99754106633f94d350db34d548d6091a','Bachelor of Surgery','119271');
INSERT INTO doctor VALUES (4528,'Vince Gilligan','111097889','vgilligan','90434ae19345b6fd7a2e96b25186bd28','Bachelor of Neurology','529663');

INSERT INTO patient VALUES (5113, 'Olene Kay','34251 Abbott St.','6044354565','okay@gmail.com','6748086656');
INSERT INTO patient VALUES (1239,'Hans Difalco','5122 McRae Ave.','6049593048','hans33@hotmail.com','6087456254');
INSERT INTO patient VALUES (5543,'Terrie Pittsley','8221 Oak St.','7780345943','tpittsley@live.ca','2913393323');
INSERT INTO patient VALUES (6357,'Inez Hollis','1677 Park Dr.','7784952031','inezhollis@hotmail.com','4101243637');
INSERT INTO patient VALUES (3543,'Wilfred Iorio','1264 Dunbar St.','7781924825','willioro@gmail.com','8365926321');

INSERT INTO appointment VALUES (8923,'13-09-30 3:00:00',135, 5113);
INSERT INTO appointment VALUES (1345,'13-10-14 11:00:00',150,1239);
INSERT INTO appointment VALUES (7074,'13-10-25 9:00:00',85, 5543);
INSERT INTO appointment VALUES (5515,'13-11-07 4:00:00',60, 6357);

INSERT INTO schedule VALUES (2283, 8923, 5113,'13-09-30 3:00:00');
INSERT INTO schedule VALUES (6122,1345,1239,'13-10-14 11:00:00');
INSERT INTO schedule VALUES (1121,7074,5543,'13-10-25 9:00:00');
INSERT INTO schedule VALUES (8862,5515,6357,'13-11-07 4:00:00');

INSERT INTO hasmedicalrecords VALUES (5113,'Olene Kay','penicillin','Maggie Kay : 604-333-1234');
INSERT INTO hasmedicalrecords VALUES (1239,'Hans Difalco','N/A','Ben Difalco : 604-123-5678');
INSERT INTO hasmedicalrecords VALUES (5543,'Terrie Pittsley','avocado,mango','Jess Diagonal : 778-555-4312');
INSERT INTO hasmedicalrecords VALUES (6357,'Inez Hollis','penicillin','Mario Hollis : 604-676-8899');
INSERT INTO hasmedicalrecords VALUES (3543,'Wilfred Iorio','bee venom','Daisy Iorio : 778-321-4565');

INSERT INTO hasfhistory VALUES (1239,'Hans Difalco','Joseph Difalco','Father','Color Blindness');
INSERT INTO hasfhistory VALUES (1239,'Hans Difalco','Mary Difalco','Mother','Cystic Fibrosis');
INSERT INTO hasfhistory VALUES (1239,'Hans Difalco','Alfred Difalco','Uncle','Color Blindness');
INSERT INTO hasfhistory VALUES (1239,'Hans Difalco','Tom Difalco','Brother','Color Blindness');
INSERT INTO hasfhistory VALUES (1239,'Hans Difalco','Sally Difalco','Sister','Haemophilia');

INSERT INTO containsphistory VALUES (5113,'Olene Kay','Mar. 3, 2011','Mono','Ibuprofen 200mg x 10 tablets, Prednisone 40mg x 10 tablets');
INSERT INTO containsphistory VALUES (5113,'Olene Kay','Sep. 20, 2012','Flu','Acetaminophin 150mg x 10 tablets');
INSERT INTO containsphistory VALUES (1239,'Hans Difalco','Feb. 13, 2013','Carpal Tunnel','Ibuprofen 300mg x 30 tablets');
INSERT INTO containsphistory VALUES (1239,'Hans Difalco','Mar. 24, 2013','Flu','Acetaminophin 150mg x 10 tablets');
