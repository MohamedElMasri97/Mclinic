DROP database if exists `health_care`;
create database `health_care`;
use `health_care`;


-- notifications types
-- 0 -> included note that will be displayed as it is
-- 2 warnign included note 
-- 3 warning included note
-- 4 welcome massege for patient (name saved in note)
-- 5 welcome massege for doctor (name saved in note)
-- 6 welcome massege for pharmacy (name saved in note)
-- 7 welcome massege for lab (name saved in note)
-- 8 tellign patient your doctor req has been registered
-- 9 tellign patient your pharmacy req has been registered
-- 10 tellign patient your doctor req has been registered


-- !!registration part!!
CREATE TABLE `users_registration`(
    userid int unsigned AUTO_INCREMENT PRIMARY KEY, -- id is alwasy provided for all users
    usertype int(1), -- 0 -> patient, 1 -> doctor, 2 -> pharmacy, 3 -> lab
    email varchar(50) UNIQUE,
    userpassword varchar(50) ,
    ustate varchar(1) not null default "0"
);

CREATE TABLE `users_patient`(
    patientid int unsigned AUTO_INCREMENT PRIMARY KEY, -- id is alwasy provided for all users
    fuserid int unsigned,
    username varchar(50), -- manager name or patient name or doctor anme
    birth date, -- SQL> INSERT INTO users(birth) VALUES(TO_DATE('17/12/2015', 'DD/MM/YYYY'));
    city varchar(4),
    phone varchar(14),
    np varchar(400),
    nationalnumber varchar(12),    
    gender int(1), -- 0 -> mail, -> Female
    note varchar(400) -- note that the patient want to provide like "i have alergy agianst ...", 
                      -- also, used for doctors and labs to provide information for doctors and labs and pharmacies 
                      -- that would be shown for all patients
);
Alter table `users_patient` ADD CONSTRAINT FK_puserid FOREIGN KEY (fuserid) REFERENCES users_registration(userid);
-- welcome msg trigger
-- 4
CREATE  TRIGGER `rigestration_trig_notificationTBL_userspatientTBL` AFTER insert ON `users_patient` FOR EACH ROW  insert into notifications(note,touser) values(concat((select msg from notificationmsgs where id = 4),' ',NEW.username),NEW.fuserid); -- done


CREATE TABLE `users_doctor`(
    doctorid int unsigned AUTO_INCREMENT PRIMARY KEY, -- id is alwasy provided for all users
    fuserid int unsigned,
    verified int(1), -- used to verify the labs and doctor and pharmacies, 0 -> not, 1 -> verified
    username varchar(50), -- manager name or patient name or doctor anme
    birth date, -- SQL> INSERT INTO users(birth) VALUES(TO_DATE('17/12/2015', 'DD/MM/YYYY'));
    city varchar(4) ,
    phone varchar(14),
    np varchar(400),
    nationalnumber varchar(12),    
    gender int(1), -- 0 -> mail, -> Female
    note varchar(400), -- note that the patient want to provide like "i have alergy agianst ...", 
                      -- also, used for doctors and labs to provide information for doctors and labs and pharmacies 
                      -- that would be shown for all patients
    speciality varchar(100) -- this is provided for doctors so they can use
);
Alter table `users_doctor` ADD CONSTRAINT FK_duserid FOREIGN KEY (fuserid) REFERENCES users_registration(userid);
CREATE  TRIGGER `rigestration_trig_notificationTBL_usersdoctorTBL` AFTER insert ON `users_doctor` FOR EACH ROW  insert into notifications(note,touser) values(concat((select msg from notificationmsgs where id = 5),' ',NEW.username),NEW.fuserid); -- done

CREATE TABLE `users_pharmacy`(
    pharmacyid int unsigned AUTO_INCREMENT PRIMARY KEY, -- id is alwasy provided for all users
    fuserid int unsigned,
    username varchar(50) ,
    title varchar(50),
    verified int(1), -- used to verify the labs and doctor and pharmacies, 0 -> not, 1 -> verified
    city varchar(4),
    phone varchar(14),
    np varchar(400),
    note varchar(400) -- note that the patient want to provide like "i have alergy agianst ...", 
                      -- also, used for doctors and labs to provide information for doctors and labs and pharmacies 
                      -- that would be shown for all patients
);
Alter table `users_pharmacy` ADD CONSTRAINT FK_phuserid FOREIGN KEY (fuserid) REFERENCES users_registration(userid);
CREATE  TRIGGER `rigestration_trig_notificationTBL_userspharmacyTBL` AFTER insert ON `users_pharmacy` FOR EACH ROW  insert into notifications(note,touser) values(concat((select msg from notificationmsgs where id = 6),' ',NEW.username),NEW.fuserid); -- doen

CREATE TABLE `users_lab`(
    labid int unsigned AUTO_INCREMENT PRIMARY KEY, -- id is alwasy provided for all users
    fuserid int unsigned,
    verified int(1), -- used to verify the labs and doctor and pharmacies, 0 -> not, 1 -> verified
    city varchar(4),
    username varchar(50),
    title varchar(50),
    phone varchar(14),
    np varchar(400),
    note varchar(400) -- note that the patient want to provide like "i have alergy agianst ...", 
                      -- also, used for doctors and labs to provide information for doctors and labs and pharmacies 
                      -- that would be shown for all patients
);
Alter table `users_lab` ADD CONSTRAINT FK_luserid FOREIGN KEY (fuserid) REFERENCES users_registration(userid);
CREATE  TRIGGER `rigestration_trig_notificationTBL_userslabTBL` AFTER insert ON `users_lab` FOR EACH ROW  insert into notifications(note,touser) values(concat((select msg from notificationmsgs where id = 7),' ',NEW.username),NEW.fuserid); -- done

-- when insertign user we do as follow:
--  INSERT INTO `user_registration`(email,userpassword,type) values(###,###,###);
--  based on type if(type == 0){
--  insert into `user_patient`(!!all parameter!!) values(allvalues, fuserid -> (select userid from user_registration where email = '#'))
-- }
-- tracking part 
CREATE table `track`(
    trackid int unsigned AUTO_INCREMENT PRIMARY KEY,
    fuserid int unsigned,
    ts datetime not null DEFAULT CURRENT_TIMESTAMP,
    ustate varchar(2)
);
Alter table `track` ADD CONSTRAINT FK_track_userid FOREIGN KEY (fuserid) REFERENCES users_registration(userid);
-- tracking trigger
CREATE  TRIGGER `tracking_trig_trackTBL_userregTBL` AFTER UPDATE ON `users_registration` FOR EACH ROW INSERT INTO `track`(fuserid,ustate) values(NEW.userid, NEW.ustate);



-- !!patient request part!!

-- doctor personal request registration 
create table patient_req_doctor(
    req_id int unsigned AUTO_INCREMENT PRIMARY key,
    fuserid int unsigned,
    requestby varchar(1) default "0", -- 0  for patient request and 1 for third party, 3 deleted, 5 complete
    req_state varchar(1) default '0', -- where detect if in waiting or in offer 0, or complete or deleted '1'
    note varchar(1000),
    city varchar(4),
    gender varchar(1),
    np varchar(300), -- the nearest point to the patient in time of request 
    geographiclocation varchar(100), -- for futur development 
    ts datetime not null default CURRENT_TIMESTAMP
);
Alter table `patient_req_doctor` ADD CONSTRAINT FKdprequserid FOREIGN KEY (fuserid) REFERENCES users_registration(userid);
-- 8
CREATE  TRIGGER `req_trig_notificationTBL_patientreqdoctorTBL_new` AFTER insert ON `patient_req_doctor` FOR EACH ROW  insert into notifications(note,touser) values((select msg from notificationmsgs where id = 8),NEW.fuserid); -- done
CREATE DEFINER=`root`@`localhost` TRIGGER `req_trig_notificationTBL_patientreqdoctorTBL_delete` AFTER UPDATE ON `patient_req_doctor` FOR EACH ROW insert into notifications(note,touser) select (select msg from notificationmsgs where id = 9),NEW.fuserid from DUAL where NEW.req_state = 3; -- doen
CREATE DEFINER=`root`@`localhost` TRIGGER `req_trig_notificationTBL_patientreqdoctorTBL_appointment` AFTER UPDATE ON `patient_req_doctor` FOR EACH ROW insert into notifications(note,touser) select (select msg from notificationmsgs where id = 10),NEW.fuserid from DUAL where NEW.req_state = 1 and old.req_state = 0; -- doen
CREATE DEFINER=`root`@`localhost` TRIGGER `req_trig_notificationTBL_patientreqdoctorTBL_complete` AFTER UPDATE ON `patient_req_doctor` FOR EACH ROW insert into notifications(note,touser) select (select msg from notificationmsgs where id = 11),NEW.fuserid from DUAL where NEW.req_state = 5 and old.req_state = 1; -- done







-- pharmacy personal request registration 
create table patient_req_pharmacy(
    req_id int unsigned AUTO_INCREMENT PRIMARY key,
    fuserid int unsigned,
    requestby varchar(1) default "0", -- 0  for patient request and 1 for third party request, 3 deleted, 5 complete
    req_state varchar(1) default '0', -- where detect if in waiting or in offer 0, or complete or deleted '1'
    note varchar(1000),
    city varchar(4),
    barcode varchar(20),
    why varchar(300),
    np varchar(300), -- the nearest point to the patient in time of request 
    geographiclocation varchar(100), -- for futur development 
    ts datetime not null default CURRENT_TIMESTAMP
);
Alter table `patient_req_pharmacy` ADD CONSTRAINT FKpprequserid FOREIGN KEY (fuserid) REFERENCES users_registration(userid); -- done
CREATE  TRIGGER `req_trig_notificationTBL_patientreqpharmacyTBL_new` AFTER insert ON `patient_req_pharmacy` FOR EACH ROW  insert into notifications(note,touser) values((select msg from notificationmsgs where id = 39),NEW.fuserid); -- done
CREATE DEFINER=`root`@`localhost` TRIGGER `req_trig_notificationTBL_patientreqpharmacyTBL_delete` AFTER UPDATE ON `patient_req_pharmacy` FOR EACH ROW insert into notifications(note,touser) select (select msg from notificationmsgs where id = 12),NEW.fuserid from DUAL where NEW.req_state = 3 ; -- done
CREATE DEFINER=`root`@`localhost` TRIGGER `req_trig_notificationTBL_patientreqpharmacyTBL_appointment` AFTER UPDATE ON `patient_req_pharmacy` FOR EACH ROW insert into notifications(note,touser) select (select msg from notificationmsgs where id = 13),NEW.fuserid from DUAL where NEW.req_state = 1 and old.req_state = 0; -- done
CREATE DEFINER=`root`@`localhost` TRIGGER `req_trig_notificationTBL_patientreqpharmacyTBL_complete` AFTER UPDATE ON `patient_req_pharmacy` FOR EACH ROW insert into notifications(note,touser) select (select msg from notificationmsgs where id = 14),NEW.fuserid from DUAL where NEW.req_state = 5 and old.req_state = 1; -- done



-- lab personal request registration 
create table patient_req_lab(
    req_id int unsigned AUTO_INCREMENT PRIMARY key,
    fuserid int unsigned,
    requestby varchar(1) default "0", -- 0  for patient request and 1 for third party request, 3 deleted, 5 complete
    req_state varchar(1) default '0', -- where detect if in waiting or in offer 0, or complete or deleted '1'
    note varchar(1000),
    city varchar(4),
    why varchar(300),
    np varchar(300), -- the nearest point to the patient in time of request 
    geographiclocation varchar(100), -- for futur development 
    ts datetime not null default CURRENT_TIMESTAMP
);
Alter table `patient_req_lab` ADD CONSTRAINT FKlprequserid FOREIGN KEY (fuserid) REFERENCES user_registration(userid);
CREATE  TRIGGER `req_trig_notificationTBL_patientreqlabTBL_new` AFTER insert ON `patient_req_lab` FOR EACH ROW  insert into notifications(note,touser) values((select msg from notificationmsgs where id = 40),NEW.fuserid); -- done
CREATE DEFINER=`root`@`localhost` TRIGGER `req_trig_notificationTBL_patientreqlabTBL_delete` AFTER UPDATE ON `patient_req_lab` FOR EACH ROW insert into notifications(note,touser) select (select msg from notificationmsgs where id = 15),NEW.fuserid from DUAL where NEW.req_state = 3; -- done
CREATE DEFINER=`root`@`localhost` TRIGGER `req_trig_notificationTBL_patientreqlabTBL_appointment` AFTER UPDATE ON `patient_req_lab` FOR EACH ROW insert into notifications(note,touser) select (select msg from notificationmsgs where id = 16),NEW.fuserid from DUAL where NEW.req_state = 1 and old.req_state = 0; -- done
CREATE DEFINER=`root`@`localhost` TRIGGER `req_trig_notificationTBL_patientreqlabTBL_complete` AFTER UPDATE ON `patient_req_lab` FOR EACH ROW insert into notifications(note,touser) select (select msg from notificationmsgs where id = 17),NEW.fuserid from DUAL where NEW.req_state = 5 and old.req_state = 1; -- done



-- العرض المقدم من قبل الطبيب
create table doctor_offer(
    offerid int unsigned AUTO_INCREMENT PRIMARY key,
    req_id int unsigned,
    state varchar(1) not null default '0',
    doctorid int unsigned,
    patientid int unsigned,
    offernote varchar(500),
    appointment varchar(200),
    pricing varchar(100),
    ts datetime not null default CURRENT_TIMESTAMP
);
Alter table `doctor_offer` ADD CONSTRAINT FKpdofferid FOREIGN KEY (patientid) REFERENCES users_registration(userid);
Alter table `doctor_offer` ADD CONSTRAINT FKddofferid FOREIGN KEY (doctorid) REFERENCES users_registration(userid);
Alter table `doctor_offer` ADD CONSTRAINT FKrdofferid FOREIGN KEY (req_id) REFERENCES patient_req_doctor(req_id);


-- CREATE  TRIGGER `offer_trig_notificationTBL_doctorofferTBL_new` AFTER insert ON `doctor_offer` FOR EACH ROW   ( insert into notifications(note,touser) values(concat((select msg from notificationmsgs where id = 18),(select username from users_patient where fuserid = NEW.patientid)),NEW.doctorid); insert into notifications(note,touser) values(concat((select msg from notificationmsgs where id = 19),(select username from users_doctor where fuserid = NEW.doctorid)),NEW.patientid););
-- CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_doctorofferTBL_delete` AFTER UPDATE ON `doctor_offer` FOR EACH ROW (insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 20),(select username from users_patient where fuserid = NEW.patientid)),NEW.doctorid from DUAL where NEW.state = 3; insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 21),(select username from users_doctor where fuserid = NEW.doctorid)),NEW.patientid from DUAL where ((NEW.state = 3) and exists (select req_state from patient_req_doctor where req_id = NEW.req_id and req_state = '0')););
-- CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_doctorofferTBL_appointment` AFTER UPDATE ON `doctor_offer` FOR EACH ROW (insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 22),(select username from users_patient where fuserid = NEW.patientid)),NEW.doctorid from DUAL where NEW.state = 1 and old.state = 0;);
-- CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_doctorofferTBL_complete` AFTER UPDATE ON `doctor_offer` FOR EACH ROW (insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 23),(select username from users_patient where fuserid = NEW.patientid)),NEW.doctorid from DUAL where NEW.state = 5 and old.state = 1;);
-- CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_doctorofferTBL_reject` AFTER UPDATE ON `doctor_offer` FOR EACH ROW (insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 24),(select username from users_patient where fuserid = NEW.patientid)),NEW.doctorid from DUAL where NEW.state = 6 and old.state = 4;);


-- 18
CREATE  TRIGGER `offer_trig_notificationTBL_doctorofferTBL_new_doctor` AFTER insert ON `doctor_offer` FOR EACH ROW insert into notifications(note,touser) values(concat((select msg from notificationmsgs where id = 18),(select username from users_patient where fuserid = NEW.patientid)),NEW.doctorid);

CREATE  TRIGGER `offer_trig_notificationTBL_doctorofferTBL_new_patient` AFTER insert ON `doctor_offer` FOR EACH ROW insert into notifications(note,touser) values(concat((select msg from notificationmsgs where id = 19),(select username from users_doctor where fuserid = NEW.doctorid)),NEW.patientid);

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_doctorofferTBL_delete_doctor` AFTER UPDATE ON `doctor_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 20),(select username from users_patient where fuserid = NEW.patientid)),NEW.doctorid from DUAL where NEW.state = 3;

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_doctorofferTBL_delete_patient` AFTER UPDATE ON `doctor_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 21),(select username from users_doctor where fuserid = NEW.doctorid)),NEW.patientid from DUAL where ((NEW.state = 3) and exists (select req_state from patient_req_doctor where req_id = NEW.req_id and req_state = '0'));

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_doctorofferTBL_appointment` AFTER UPDATE ON `doctor_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 22),(select username from users_patient where fuserid = NEW.patientid)),NEW.doctorid from DUAL where NEW.state = 1 and old.state = 0;

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_doctorofferTBL_complete` AFTER UPDATE ON `doctor_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 23),(select username from users_patient where fuserid = NEW.patientid)),NEW.doctorid from DUAL where NEW.state = 5 and old.state = 1;

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_doctorofferTBL_reject` AFTER UPDATE ON `doctor_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 24),(select username from users_patient where fuserid = NEW.patientid)),NEW.doctorid from DUAL where NEW.state = 6 and old.state = 4;

-- العرض المقدم من المعمل: 
create table lab_offer(
    offerid int unsigned AUTO_INCREMENT PRIMARY key,
    req_id int unsigned,
    state varchar(1) not null default '0',
    labid int unsigned,
    patientid int unsigned,
    offernote varchar(500),
    appointment varchar(200),
    pricing varchar(100),
    ts datetime not null default CURRENT_TIMESTAMP
);
Alter table `lab_offer` ADD CONSTRAINT FKplofferid FOREIGN KEY (patientid) REFERENCES users_registration(userid);
Alter table `lab_offer` ADD CONSTRAINT FKllofferid FOREIGN KEY (labid) REFERENCES users_registration(userid);
Alter table `lab_offer` ADD CONSTRAINT FKrlofferid FOREIGN KEY (req_id) REFERENCES patient_req_lab(req_id);

-- 25
CREATE  TRIGGER `offer_trig_notificationTBL_labofferTBL_new_lab` AFTER insert ON `lab_offer` FOR EACH ROW insert into notifications(note,touser) values(concat((select msg from notificationmsgs where id = 25),(select username from users_patient where fuserid = NEW.patientid)),NEW.labid);

CREATE  TRIGGER `offer_trig_notificationTBL_labofferTBL_new_patient` AFTER insert ON `lab_offer` FOR EACH ROW insert into notifications(note,touser) values(concat((select msg from notificationmsgs where id = 26),(select username from users_lab where fuserid = NEW.labid)),NEW.patientid);

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_labofferTBL_delete_lab` AFTER UPDATE ON `lab_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 27),(select username from users_patient where fuserid = NEW.patientid)),NEW.labid from DUAL where NEW.state = 3;

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_labofferTBL_delete_patient` AFTER UPDATE ON `lab_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 28),(select username from users_lab where fuserid = NEW.labid)),NEW.patientid from DUAL where ((NEW.state = 3) and exists (select req_state from patient_req_lab where req_id = NEW.req_id and req_state = '0'));

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_labofferTBL_appointment` AFTER UPDATE ON `lab_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 29),(select username from users_patient where fuserid = NEW.patientid)),NEW.labid from DUAL where NEW.state = 1 and old.state = 0;

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_labofferTBL_complete` AFTER UPDATE ON `lab_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 30),(select username from users_patient where fuserid = NEW.patientid)),NEW.labid from DUAL where NEW.state = 5 and old.state = 1;

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_labofferTBL_reject` AFTER UPDATE ON `lab_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 31),(select username from users_patient where fuserid = NEW.patientid)),NEW.labid from DUAL where NEW.state = 6 and old.state = 4;



-- pharmacy offer
create table pharmacy_offer(
    offerid int unsigned AUTO_INCREMENT PRIMARY key,
    req_id int unsigned,
    state varchar(1) not null default '0',
    pharmacyid int unsigned,
    patientid int unsigned,
    offernote varchar(500),
    appointment varchar(200),
    pricing varchar(100),
    ts datetime not null default CURRENT_TIMESTAMP
);
Alter table `pharmacy_offer` ADD CONSTRAINT FKpphofferid FOREIGN KEY (patientid) REFERENCES users_registration(userid);
Alter table `pharmacy_offer` ADD CONSTRAINT FKphphofferid FOREIGN KEY (pharmacyid) REFERENCES users_registration(userid);
Alter table `pharmacy_offer` ADD CONSTRAINT FKrphofferid FOREIGN KEY (req_id) REFERENCES patient_req_pharmacy(req_id);

-- 32
CREATE  TRIGGER `offer_trig_notificationTBL_pharmacyofferTBL_new_pharmacy` AFTER insert ON `pharmacy_offer` FOR EACH ROW insert into notifications(note,touser) values(concat((select msg from notificationmsgs where id = 32),(select username from users_patient where fuserid = NEW.patientid)),NEW.pharmacyid);

CREATE  TRIGGER `offer_trig_notificationTBL_pharmacyofferTBL_new_patient` AFTER insert ON `pharmacy_offer` FOR EACH ROW insert into notifications(note,touser) values(concat((select msg from notificationmsgs where id = 33),(select username from users_pharmacy where fuserid = NEW.pharmacyid)),NEW.patientid);

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_pharmacyofferTBL_delete_pharmacy` AFTER UPDATE ON `pharmacy_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 34),(select username from users_patient where fuserid = NEW.patientid)),NEW.pharmacyid from DUAL where NEW.state = 3;

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_pharmacyofferTBL_delete_patient` AFTER UPDATE ON `pharmacy_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 35),(select username from users_pharmacy where fuserid = NEW.pharmacyid)),NEW.patientid from DUAL where ((NEW.state = 3) and exists (select req_state from patient_req_pharmacy where req_id = NEW.req_id and req_state = '0'));

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_pharmacyofferTBL_appointment` AFTER UPDATE ON `pharmacy_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 36),(select username from users_patient where fuserid = NEW.patientid)),NEW.pharmacyid from DUAL where NEW.state = 1 and old.state = 0;

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_pharmacyofferTBL_complete` AFTER UPDATE ON `pharmacy_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 37),(select username from users_patient where fuserid = NEW.patientid)),NEW.pharmacyid from DUAL where NEW.state = 5 and old.state = 1;

CREATE DEFINER=`root`@`localhost` TRIGGER `offer_trig_notificationTBL_pharmacyofferTBL_reject` AFTER UPDATE ON `pharmacy_offer` FOR EACH ROW insert into notifications(note,touser) select concat((select msg from notificationmsgs where id = 38),(select username from users_patient where fuserid = NEW.patientid)),NEW.pharmacyid from DUAL where NEW.state = 6 and old.state = 4;


-- messages included in the notifications
create table notificationmsgs(
    id unsigned int not null,
    msg varchar(400) not null
);

--  notification 
create table notifications(
    notifyid int unsigned AUTO_INCREMENT PRIMARY key,
    note varchar(500) not null,
    state varchar(1) not null default "0", -- 0 -> represent unread, 1 -> has been read,
    type varchar(2) default "0", -- this hold many type of notification, 0 here is regular notification with no sender
    fromuser int unsigned, -- not always used
    touser int unsigned,
    ts datetime not null default CURRENT_TIMESTAMP -- always used and record the user_registration id of the user to be notrified
);
Alter table `notifications` ADD CONSTRAINT FKnotify FOREIGN KEY (touser) REFERENCES users_registration(userid);

INSERT INTO users_registration(email, userpassword, usertype) values('p@t.com','123',"0");
insert into users_patient(fuserid,username,birth,phone,gender,city,np) values((select userid from users_registration where email = 'p@t.com'),'test patient','1996-11-11','091xxxxxxx',0,'1','test');

INSERT INTO users_registration(email, userpassword, usertype) values('d@t.com','123',"1");
insert into users_doctor(fuserid,username,birth,phone,gender,city,np) values((select userid from users_registration where email = 'd@t.com'),'test doctor','1996-11-11','091xxxxxxx',0,'1','test');

INSERT INTO users_registration(email, userpassword, usertype) values('ph@t.com','123',"2");
insert into users_pharmacy(fuserid,username,title,phone,city,np) values((select userid from users_registration where email = 'ph@t.com'),'test pharmacy','pharmacy','091xxxxxxx','1','test');

INSERT INTO users_registration(email, userpassword, usertype) values('l@t.com','123',"3");
insert into users_lab(fuserid,username,title,phone,city,np) values((select userid from users_registration where email = 'l@t.com'),'test pharmacy','lab','091xxxxxxx','1','test');

create table cities(
    id int unsigned AUTO_INCREMENT primary key,
    name varchar(40)
);

create table carousel(
    id int unsigned AUTO_INCREMENT primary key,
    imagepath varchar(300)
);



-- **TO HERE**
