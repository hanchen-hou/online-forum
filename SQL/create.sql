--
-- First drop any existing tables. Any errors are ignored.
--
DROP VIEW CS_CATEGORIES_DETAIL cascade constraints;
DROP VIEW CS_POSTS_DETAIL cascade constraints;
DROP TRIGGER CS_ADD_COMMENTS;
DROP TABLE CS_COMMENTS cascade constraints;
DROP TRIGGER CS_ADD_POSTS;
DROP TABLE CS_POSTS cascade constraints;
DROP TRIGGER CS_ADD_MSGS;
DROP TABLE CS_MSGS cascade constraints;
DROP SEQUENCE CS_MSGS_SEQ;
DROP TRIGGER CS_ADD_CATEGORIES;
DROP SEQUENCE CS_CATEGORIES_SEQ;
DROP TABLE CS_CATEGORIES cascade constraints;
DROP TABLE CS_BANNED_USERS cascade constraints;
DROP TABLE CS_ADMINS cascade constraints;
DROP TABLE CS_USERS cascade constraints;

--
-- Now, add each table.
--
CREATE TABLE CS_USERS 
			(
			id INT PRIMARY KEY,
			email VARCHAR2(256) UNIQUE NOT NULL,
			name VARCHAR2(15) UNIQUE NOT NULL,
			pw_md5 CHAR(32) NOT NULL,
			salt CHAR(4) NOT NULL,
			register_datetime TIMESTAMP NOT NULL,
			status INT NOT NULL
			);
			
CREATE TABLE CS_ADMINS 
			(
			id INT PRIMARY KEY, 
			senior_id INT,
			FOREIGN KEY (id) REFERENCES CS_USERS (id),
			FOREIGN KEY (senior_id ) REFERENCES CS_ADMINS (id)
			);

CREATE TABLE CS_BANNED_USERS 
				(
				id INT PRIMARY KEY, 
				banned_datetime TIMESTAMP NOT NULL,
				admin_id INT NOT NULL,
				FOREIGN KEY (id) REFERENCES CS_USERS (id),
				FOREIGN KEY (admin_id) REFERENCES CS_ADMINS (id)
				);

CREATE TABLE CS_CATEGORIES 
			(
			id INT PRIMARY KEY, 
			name VARCHAR2(20) NOT NULL UNIQUE,
			datetime TIMESTAMP NOT NULL,
			admin_id INT,
			FOREIGN KEY (admin_id) REFERENCES CS_ADMINS(id)
			);
			
CREATE SEQUENCE CS_CATEGORIES_SEQ;

CREATE OR REPLACE TRIGGER CS_ADD_CATEGORIES 
			BEFORE INSERT ON CS_CATEGORIES 
			FOR EACH ROW
			BEGIN
			 SELECT CS_CATEGORIES_SEQ.NEXTVAL
			 INTO  :new.id
			 FROM  dual;
			END;
/

CREATE SEQUENCE CS_MSGS_SEQ;

CREATE TABLE CS_MSGS 
			(
			id INT PRIMARY KEY,
			user_id INT NOT NULL,
			datetime TIMESTAMP NOT NULL,
			content VARCHAR2( 512 ) NOT NULL,
			FOREIGN KEY (user_id) REFERENCES CS_USERS (id)
			);

CREATE OR REPLACE TRIGGER CS_ADD_MSGS 
			BEFORE INSERT ON CS_MSGS 
			FOR EACH ROW
			BEGIN
			  SELECT CS_MSGS_SEQ.NEXTVAL
			  INTO   :new.id
			  FROM   dual;
			END;
/

CREATE TABLE CS_POSTS 
			(
			id INT PRIMARY KEY,
			category_id INT NOT NULL,
			title VARCHAR2( 50 ) NOT NULL,
			FOREIGN KEY (id) REFERENCES CS_MSGS (id) ON DELETE CASCADE,
			FOREIGN KEY (category_id) REFERENCES CS_CATEGORIES (id) ON DELETE CASCADE
			);

CREATE OR REPLACE TRIGGER CS_ADD_POSTS 
			BEFORE INSERT ON CS_POSTS 
			FOR EACH ROW
			BEGIN
			  SELECT CS_MSGS_SEQ.CURRVAL
			  INTO   :new.id
			  FROM   dual;
			END;
/

CREATE TABLE CS_COMMENTS 
			(
			id INT PRIMARY KEY,
			post_id INT NOT NULL,
			FOREIGN KEY (id) REFERENCES CS_MSGS (id) ON DELETE CASCADE,
			FOREIGN KEY (post_id) REFERENCES CS_POSTS (id) ON DELETE CASCADE
			);

CREATE OR REPLACE TRIGGER CS_ADD_COMMENTS 
			BEFORE INSERT ON CS_COMMENTS 
			FOR EACH ROW
			BEGIN
			  SELECT CS_MSGS_SEQ.CURRVAL
			  INTO   :new.id
			  FROM   dual;
			END;
/
		
create view CS_CATEGORIES_DETAIL(category_id, category_name, posts_num) AS
				select c.id, c.name, count(*)
				from CS_CATEGORIES c, CS_POSTS p
				where c.ID = p.CATEGORY_ID
				group by c.name, c.id
				order by c.id;
				
create view CS_POSTS_DETAIL(CATEGORY_ID, CATEGORY_NAME, USER_ID, USER_NAME, POST_ID, TITLE, CONTENT, DATETIME) As
				 select c.id, c.name, u.id, u.name, p.id, p.title, m.content, m.datetime
				 from CS_MSGS m, CS_POSTS p, CS_USERS u, CS_CATEGORIES c 
				 where m.id = p.id and u.id = m.user_id and c.id = p.category_id;
				 
--
--CS_USERS
--
REM INSERTING into CS_USERS
SET DEFINE OFF;
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (963042884,'tempor.bibendum.Donec@commodohendrerit.com','Joan','249d7068c679c2c7eb1ac3f6908f8c44','nnhK',to_timestamp('16-06-12 14:07:30.834418000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (899901892,'auctor@semeget.ca','Nola','d410152ec0e597091591ca1e8852ee7a','Nh06',to_timestamp('16-06-12 14:07:30.873235000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (449055923,'Phasellus.at@tempor.ca','Shelley','f0f81677eaace495136517f1fb10293a','duXm',to_timestamp('16-06-12 14:07:30.908270000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (901881543,'Phasellus.in@utipsumac.net','Finn','91f8c32540311c51ad646e2dfc98319a','EL01',to_timestamp('16-06-12 14:07:30.942739000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (694174312,'in@nullaat.com','Porter','b9d8c378cf958c3e3b8da2191b500b78','MTxs',to_timestamp('16-06-12 14:07:30.977589000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (294073332,'turpis.Nulla.aliquet@pede.com','Ora','1b495ff2c08af5f189f17418ea6b33a4','Fg9p',to_timestamp('16-06-12 14:07:31.012634000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (742366732,'malesuada.fringilla.est@interdum.net','Hunter','0c0dab6c13719879a98a90c5cf6e32d3','C5uY',to_timestamp('16-06-12 14:07:31.046537000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (911131203,'egestas.Sed@sollicitudinamalesuada.net','Avram','08a246818f6ccabf38bfc6ad92a35f6d','NJoA',to_timestamp('16-06-12 14:07:31.080785000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (164650115,'ipsum.cursus.vestibulum@ipsum.net','Libby','5417564f2645a662621183647a97fbe1','Rk6o',to_timestamp('16-06-12 14:07:31.115604000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (466330133,'non.magna.Nam@non.co.uk','Maisie','294a52808a48157bdb3f49081d56296a','dsVi',to_timestamp('16-06-12 14:07:31.150419000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (789110754,'egestas.Aliquam@ligula.com','Kalia','4f88c8774dc5dd0eb8c1f040adecbaec','n5ZW',to_timestamp('16-06-12 14:07:31.185414000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (180529738,'justo.eu@sedfacilisisvitae.net','Celeste','676378cbc7a1c7674e22fcd959b1b1f4','mkyF',to_timestamp('16-06-12 14:07:31.219622000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (551530123,'Duis.a.mi@orci.edu','Ingrid','291c8692aaef29fcf12a6eebcc59bd7b','vbSk',to_timestamp('16-06-12 14:07:31.253982000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (13011912,'egestas.Sed.pharetra@Suspendisseseddolor.org','Barrett','20668fb1756df67fa84833b5bc375d2c','nYCA',to_timestamp('16-06-12 14:07:31.289361000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (910843102,'Suspendisse.aliquet@bibendumDonecfelis.org','Gemma','015afd9101e30d6a940cd04ead5203e3','A5lU',to_timestamp('16-06-12 14:07:31.324753000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (459335581,'fringilla.porttitor@Aeneanegestashendrerit.com','Lacy','bba93ee82cdfb3a4f1de74a0fcc171a8','vp38',to_timestamp('16-06-12 14:07:31.359465000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (486301748,'erat@ametdapibusid.ca','Rhoda','cc8e7c6d5d72e23009131b68e2d6a95d','jj3g',to_timestamp('16-06-12 14:07:31.393958000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (30352832,'adipiscing.ligula.Aenean@dignissim.edu','Brett','763fe7f12600321f8db26dc6ac5d4017','3mMc',to_timestamp('16-06-12 14:07:31.428104000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (529332265,'ut@tincidunt.edu','Linus','81bc881caeb596668504bceefc6f1204','9SSy',to_timestamp('16-06-12 14:07:31.462979000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (213610313,'Etiam.laoreet@ametmassaQuisque.ca','Colette','16eee13011d5c5d7e70e36183eed5ea0','ITP0',to_timestamp('16-06-12 14:07:31.510423000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (777664288,'condimentum.Donec@quis.ca','Keelie','a4bcbe5ee2515f09d7ee0006ddaa8cb6','VQYs',to_timestamp('16-06-12 14:07:31.547054000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (574222592,'turpis.Nulla@sagittis.edu','Fleur','ba765bfe65b69933031f351f107640e1','fSYN',to_timestamp('16-06-12 14:07:31.661378000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (653254411,'Quisque@enimmitempor.net','Nicholas','f42238e8ae34867caf427cf97a705f4e','h5JE',to_timestamp('16-06-12 14:07:31.697297000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (576779473,'lacus@egestasurnajusto.co.uk','Stewart','e91816aa3188892d81e23bf2ac121fbf','HaLm',to_timestamp('16-06-12 14:07:31.732814000','RR-MM-DD HH24:MI:SSXFF'),0);
Insert into CS_USERS (ID,EMAIL,NAME,PW_MD5,SALT,REGISTER_DATETIME,STATUS) values (716264410,'montes.nascetur@arcuMorbisit.org','Ray','07b108a63886253ac83c6ac69d16ed48','Up5O',to_timestamp('16-06-12 14:07:31.769006000','RR-MM-DD HH24:MI:SSXFF'),0);

--
--CS_ADMINS
--
REM INSERTING into CS_ADMINS
SET DEFINE OFF;
Insert into CS_ADMINS (ID,SENIOR_ID) values (777664288,null);
Insert into CS_ADMINS (ID,SENIOR_ID) values (574222592,null);
Insert into CS_ADMINS (ID,SENIOR_ID) values (653254411,null);
Insert into CS_ADMINS (ID,SENIOR_ID) values (576779473,null);
Insert into CS_ADMINS (ID,SENIOR_ID) values (716264410,null);

--
-- CS_BANNED_USERS
--
REM INSERTING into CS_BANNED_USERS
SET DEFINE OFF;

--
--CS_CATEGORIES
--
REM INSERTING into CS_CATEGORIES
SET DEFINE OFF;
Insert into CS_CATEGORIES (ID,NAME,DATETIME,ADMIN_ID) values (null,'Life',to_timestamp('16-06-12 14:07:31.932778000','RR-MM-DD HH24:MI:SSXFF'),null);
Insert into CS_CATEGORIES (ID,NAME,DATETIME,ADMIN_ID) values (null,'Books',to_timestamp('16-06-12 14:07:31.969276000','RR-MM-DD HH24:MI:SSXFF'),null);
Insert into CS_CATEGORIES (ID,NAME,DATETIME,ADMIN_ID) values (null,'Travel',to_timestamp('16-06-12 14:07:32.003925000','RR-MM-DD HH24:MI:SSXFF'),null);
Insert into CS_CATEGORIES (ID,NAME,DATETIME,ADMIN_ID) values (null,'Fashion',to_timestamp('16-06-12 14:07:32.038144000','RR-MM-DD HH24:MI:SSXFF'),null);
Insert into CS_CATEGORIES (ID,NAME,DATETIME,ADMIN_ID) values (null,'Games',to_timestamp('16-06-12 14:07:32.072016000','RR-MM-DD HH24:MI:SSXFF'),null);
Insert into CS_CATEGORIES (ID,NAME,DATETIME,ADMIN_ID) values (null,'Movies',to_timestamp('16-06-12 14:07:32.109404000','RR-MM-DD HH24:MI:SSXFF'),null);
Insert into CS_CATEGORIES (ID,NAME,DATETIME,ADMIN_ID) values (null,'Food',to_timestamp('16-06-12 14:07:32.144229000','RR-MM-DD HH24:MI:SSXFF'),null);
Insert into CS_CATEGORIES (ID,NAME,DATETIME,ADMIN_ID) values (null,'Music',to_timestamp('16-06-12 14:07:32.179644000','RR-MM-DD HH24:MI:SSXFF'),null);
Insert into CS_CATEGORIES (ID,NAME,DATETIME,ADMIN_ID) values (null,'Family',to_timestamp('16-06-12 14:07:32.213820000','RR-MM-DD HH24:MI:SSXFF'),null);

--
-- CS_MSGS / CS_POSTS / CS_COMMENTS
-- Note, since the trigger of CS_MSGS / CS_POSTS / CS_COMMENTS
-- we cannot insert data one by one table
--
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (1,30352832,to_timestamp('14-JUN-16 05.04.06.677213000 PM','DD-MON-RR HH.MI.SSXFF AM'),'lorem ipsum sodales purus, in molestie tortor nibh sit amet orci.');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (1,7,'semper rutrum. Fusce dolor');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (2,213610313,to_timestamp('14-JUN-16 05.04.06.876746000 PM','DD-MON-RR HH.MI.SSXFF AM'),'mauris id sapien. Cras dolor dolor, tempus non, lacinia');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (2,2,'Phasellus libero mauris, aliquam eu, accumsan');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (3,899901892,to_timestamp('14-JUN-16 05.04.07.013954000 PM','DD-MON-RR HH.MI.SSXFF AM'),'quam quis diam. Pellentesque habitant morbi tristique senectus et netus');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (3,4,'ut, nulla. Cras eu tellus');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (4,653254411,to_timestamp('14-JUN-16 05.04.07.148789000 PM','DD-MON-RR HH.MI.SSXFF AM'),'non, lobortis quis, pede. Suspendisse dui. Fusce diam');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (4,7,'Quisque fringilla euismod enim.');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (5,13011912,to_timestamp('14-JUN-16 05.04.07.285682000 PM','DD-MON-RR HH.MI.SSXFF AM'),'Cras sed leo. Cras vehicula aliquet libero. Integer in');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (5,1,'tempus mauris erat eget');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (6,459335581,to_timestamp('14-JUN-16 05.04.07.421681000 PM','DD-MON-RR HH.MI.SSXFF AM'),'Sed nulla ante, iaculis nec, eleifend non, dapibus');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (6,2,'ac mattis semper, dui');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (7,13011912,to_timestamp('14-JUN-16 05.04.07.558158000 PM','DD-MON-RR HH.MI.SSXFF AM'),'posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (7,8,'nunc. In at pede. Cras');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (8,694174312,to_timestamp('14-JUN-16 05.04.07.693567000 PM','DD-MON-RR HH.MI.SSXFF AM'),'nibh. Donec est mauris, rhoncus id, mollis nec,');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (8,2,'interdum. Nunc sollicitudin commodo ipsum.');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (9,466330133,to_timestamp('14-JUN-16 05.04.07.829661000 PM','DD-MON-RR HH.MI.SSXFF AM'),'non dui nec urna suscipit nonummy. Fusce fermentum');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (9,8,'nec, imperdiet nec, leo.');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (10,466330133,to_timestamp('14-JUN-16 05.04.07.967076000 PM','DD-MON-RR HH.MI.SSXFF AM'),'eros nec tellus. Nunc lectus pede, ultrices a,');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (10,1,'nec ante blandit viverra. Donec');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (11,901881543,to_timestamp('14-JUN-16 05.04.08.104755000 PM','DD-MON-RR HH.MI.SSXFF AM'),'Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (11,1,'conubia nostra, per inceptos hymenaeos. Mauris');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (12,653254411,to_timestamp('14-JUN-16 05.04.08.242073000 PM','DD-MON-RR HH.MI.SSXFF AM'),'arcu. Vestibulum ut eros non enim commodo hendrerit. Donec porttitor');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (12,3,'mauris eu elit. Nulla');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (13,164650115,to_timestamp('14-JUN-16 05.04.08.386851000 PM','DD-MON-RR HH.MI.SSXFF AM'),'eu turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (13,1,'morbi tristique senectus et netus');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (14,213610313,to_timestamp('14-JUN-16 05.04.08.521835000 PM','DD-MON-RR HH.MI.SSXFF AM'),'enim nec tempus scelerisque, lorem ipsum sodales purus, in molestie tortor');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (14,9,'pellentesque a, facilisis non, bibendum');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (15,694174312,to_timestamp('14-JUN-16 05.04.08.655336000 PM','DD-MON-RR HH.MI.SSXFF AM'),'fermentum vel, mauris. Integer sem elit, pharetra ut, pharetra sed,');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (15,4,'arcu iaculis enim, sit amet');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (16,694174312,to_timestamp('14-JUN-16 05.04.08.790548000 PM','DD-MON-RR HH.MI.SSXFF AM'),'ut cursus luctus, ipsum leo elementum sem, vitae');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (16,7,'Donec est. Nunc ullamcorper,');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (17,742366732,to_timestamp('14-JUN-16 05.04.08.927210000 PM','DD-MON-RR HH.MI.SSXFF AM'),'ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus et,');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (17,8,'fringilla ornare placerat, orci lacus vestibulum');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (18,963042884,to_timestamp('14-JUN-16 05.04.09.064887000 PM','DD-MON-RR HH.MI.SSXFF AM'),'dolor. Fusce mi lorem, vehicula et, rutrum eu,');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (18,6,'venenatis vel, faucibus id, libero. Donec');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (19,529332265,to_timestamp('14-JUN-16 05.04.09.201047000 PM','DD-MON-RR HH.MI.SSXFF AM'),'nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est.');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (19,3,'non lorem vitae odio sagittis');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (20,466330133,to_timestamp('14-JUN-16 05.04.09.338656000 PM','DD-MON-RR HH.MI.SSXFF AM'),'risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper');
Insert into CS_POSTS (ID,CATEGORY_ID,TITLE) values (20,3,'lacus. Etiam bibendum fermentum');
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (61,576779473,to_timestamp('14-JUN-16 05.08.03.571027000 PM','DD-MON-RR HH.MI.SSXFF AM'),'arcu. Vestibulum ante ipsum primis in faucibus orci luctus et');
Insert into CS_COMMENTS (ID,POST_ID) values (61,3);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (62,742366732,to_timestamp('14-JUN-16 05.08.03.707967000 PM','DD-MON-RR HH.MI.SSXFF AM'),'ut, pharetra sed, hendrerit a, arcu. Sed et libero. Proin');
Insert into CS_COMMENTS (ID,POST_ID) values (62,15);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (63,901881543,to_timestamp('14-JUN-16 05.08.03.846914000 PM','DD-MON-RR HH.MI.SSXFF AM'),'Sed nunc est, mollis non, cursus non, egestas a,');
Insert into CS_COMMENTS (ID,POST_ID) values (63,10);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (64,742366732,to_timestamp('14-JUN-16 05.08.03.985841000 PM','DD-MON-RR HH.MI.SSXFF AM'),'posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis');
Insert into CS_COMMENTS (ID,POST_ID) values (64,17);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (65,164650115,to_timestamp('14-JUN-16 05.08.04.124139000 PM','DD-MON-RR HH.MI.SSXFF AM'),'at pede. Cras vulputate velit eu sem. Pellentesque ut');
Insert into CS_COMMENTS (ID,POST_ID) values (65,17);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (66,901881543,to_timestamp('14-JUN-16 05.08.04.261840000 PM','DD-MON-RR HH.MI.SSXFF AM'),'mattis semper, dui lectus rutrum urna, nec luctus felis purus ac');
Insert into CS_COMMENTS (ID,POST_ID) values (66,1);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (67,466330133,to_timestamp('14-JUN-16 05.08.04.401740000 PM','DD-MON-RR HH.MI.SSXFF AM'),'nec enim. Nunc ut erat. Sed nunc est,');
Insert into CS_COMMENTS (ID,POST_ID) values (67,12);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (68,716264410,to_timestamp('14-JUN-16 05.08.04.541742000 PM','DD-MON-RR HH.MI.SSXFF AM'),'enim, sit amet ornare lectus justo eu arcu. Morbi sit amet massa.');
Insert into CS_COMMENTS (ID,POST_ID) values (68,20);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (69,716264410,to_timestamp('14-JUN-16 05.08.04.680167000 PM','DD-MON-RR HH.MI.SSXFF AM'),'Sed nulla ante, iaculis nec, eleifend non, dapibus');
Insert into CS_COMMENTS (ID,POST_ID) values (69,4);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (70,716264410,to_timestamp('14-JUN-16 05.08.04.816922000 PM','DD-MON-RR HH.MI.SSXFF AM'),'ante lectus convallis est, vitae sodales nisi magna sed');
Insert into CS_COMMENTS (ID,POST_ID) values (70,5);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (71,789110754,to_timestamp('14-JUN-16 05.08.04.953178000 PM','DD-MON-RR HH.MI.SSXFF AM'),'auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa');
Insert into CS_COMMENTS (ID,POST_ID) values (71,14);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (72,899901892,to_timestamp('14-JUN-16 05.08.05.092949000 PM','DD-MON-RR HH.MI.SSXFF AM'),'cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit amet');
Insert into CS_COMMENTS (ID,POST_ID) values (72,15);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (73,899901892,to_timestamp('14-JUN-16 05.08.05.232461000 PM','DD-MON-RR HH.MI.SSXFF AM'),'erat. Etiam vestibulum massa rutrum magna. Cras convallis convallis');
Insert into CS_COMMENTS (ID,POST_ID) values (73,3);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (74,716264410,to_timestamp('14-JUN-16 05.08.05.371103000 PM','DD-MON-RR HH.MI.SSXFF AM'),'mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla');
Insert into CS_COMMENTS (ID,POST_ID) values (74,3);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (75,164650115,to_timestamp('14-JUN-16 05.08.05.509178000 PM','DD-MON-RR HH.MI.SSXFF AM'),'ac facilisis facilisis, magna tellus faucibus leo, in lobortis');
Insert into CS_COMMENTS (ID,POST_ID) values (75,10);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (76,529332265,to_timestamp('14-JUN-16 05.08.05.652138000 PM','DD-MON-RR HH.MI.SSXFF AM'),'Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus');
Insert into CS_COMMENTS (ID,POST_ID) values (76,8);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (77,653254411,to_timestamp('14-JUN-16 05.08.05.791783000 PM','DD-MON-RR HH.MI.SSXFF AM'),'elit. Curabitur sed tortor. Integer aliquam adipiscing lacus.');
Insert into CS_COMMENTS (ID,POST_ID) values (77,1);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (78,294073332,to_timestamp('14-JUN-16 05.08.05.930404000 PM','DD-MON-RR HH.MI.SSXFF AM'),'aliquet nec, imperdiet nec, leo. Morbi neque tellus,');
Insert into CS_COMMENTS (ID,POST_ID) values (78,5);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (79,449055923,to_timestamp('14-JUN-16 05.08.06.067217000 PM','DD-MON-RR HH.MI.SSXFF AM'),'tincidunt, neque vitae semper egestas, urna justo faucibus lectus, a');
Insert into CS_COMMENTS (ID,POST_ID) values (79,11);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (80,459335581,to_timestamp('14-JUN-16 05.08.06.205083000 PM','DD-MON-RR HH.MI.SSXFF AM'),'tincidunt. Donec vitae erat vel pede blandit congue.');
Insert into CS_COMMENTS (ID,POST_ID) values (80,12);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (81,574222592,to_timestamp('14-JUN-16 05.08.06.340888000 PM','DD-MON-RR HH.MI.SSXFF AM'),'pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in');
Insert into CS_COMMENTS (ID,POST_ID) values (81,16);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (82,551530123,to_timestamp('14-JUN-16 05.08.06.478280000 PM','DD-MON-RR HH.MI.SSXFF AM'),'Nam ligula elit, pretium et, rutrum non, hendrerit id, ante.');
Insert into CS_COMMENTS (ID,POST_ID) values (82,20);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (83,466330133,to_timestamp('14-JUN-16 05.08.06.615786000 PM','DD-MON-RR HH.MI.SSXFF AM'),'mi eleifend egestas. Sed pharetra, felis eget varius');
Insert into CS_COMMENTS (ID,POST_ID) values (83,6);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (84,694174312,to_timestamp('14-JUN-16 05.08.06.753937000 PM','DD-MON-RR HH.MI.SSXFF AM'),'Maecenas libero est, congue a, aliquet vel, vulputate eu, odio.');
Insert into CS_COMMENTS (ID,POST_ID) values (84,7);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (85,777664288,to_timestamp('14-JUN-16 05.08.06.898325000 PM','DD-MON-RR HH.MI.SSXFF AM'),'aliquam eros turpis non enim. Mauris quis turpis');
Insert into CS_COMMENTS (ID,POST_ID) values (85,11);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (86,164650115,to_timestamp('14-JUN-16 05.08.07.037594000 PM','DD-MON-RR HH.MI.SSXFF AM'),'Sed nulla ante, iaculis nec, eleifend non, dapibus');
Insert into CS_COMMENTS (ID,POST_ID) values (86,12);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (87,466330133,to_timestamp('14-JUN-16 05.08.07.175801000 PM','DD-MON-RR HH.MI.SSXFF AM'),'vel, vulputate eu, odio. Phasellus at augue id ante');
Insert into CS_COMMENTS (ID,POST_ID) values (87,2);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (88,459335581,to_timestamp('14-JUN-16 05.08.07.322678000 PM','DD-MON-RR HH.MI.SSXFF AM'),'erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie');
Insert into CS_COMMENTS (ID,POST_ID) values (88,20);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (89,574222592,to_timestamp('14-JUN-16 05.08.07.459535000 PM','DD-MON-RR HH.MI.SSXFF AM'),'ac turpis egestas. Fusce aliquet magna a neque.');
Insert into CS_COMMENTS (ID,POST_ID) values (89,1);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (90,459335581,to_timestamp('14-JUN-16 05.08.07.598772000 PM','DD-MON-RR HH.MI.SSXFF AM'),'Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac');
Insert into CS_COMMENTS (ID,POST_ID) values (90,11);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (91,449055923,to_timestamp('14-JUN-16 05.08.07.739017000 PM','DD-MON-RR HH.MI.SSXFF AM'),'consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat');
Insert into CS_COMMENTS (ID,POST_ID) values (91,10);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (92,164650115,to_timestamp('14-JUN-16 05.08.07.877785000 PM','DD-MON-RR HH.MI.SSXFF AM'),'velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus.');
Insert into CS_COMMENTS (ID,POST_ID) values (92,12);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (93,529332265,to_timestamp('14-JUN-16 05.08.08.015507000 PM','DD-MON-RR HH.MI.SSXFF AM'),'Ut tincidunt orci quis lectus. Nullam suscipit, est ac facilisis facilisis,');
Insert into CS_COMMENTS (ID,POST_ID) values (93,6);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (94,529332265,to_timestamp('14-JUN-16 05.08.08.152684000 PM','DD-MON-RR HH.MI.SSXFF AM'),'a purus. Duis elementum, dui quis accumsan convallis, ante');
Insert into CS_COMMENTS (ID,POST_ID) values (94,12);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (95,576779473,to_timestamp('14-JUN-16 05.08.08.290112000 PM','DD-MON-RR HH.MI.SSXFF AM'),'vel quam dignissim pharetra. Nam ac nulla. In');
Insert into CS_COMMENTS (ID,POST_ID) values (95,17);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (96,466330133,to_timestamp('14-JUN-16 05.08.08.426426000 PM','DD-MON-RR HH.MI.SSXFF AM'),'nec, imperdiet nec, leo. Morbi neque tellus, imperdiet');
Insert into CS_COMMENTS (ID,POST_ID) values (96,15);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (97,529332265,to_timestamp('14-JUN-16 05.08.08.563776000 PM','DD-MON-RR HH.MI.SSXFF AM'),'Phasellus vitae mauris sit amet lorem semper auctor. Mauris vel turpis. Aliquam');
Insert into CS_COMMENTS (ID,POST_ID) values (97,7);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (98,694174312,to_timestamp('14-JUN-16 05.08.08.700616000 PM','DD-MON-RR HH.MI.SSXFF AM'),'arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci, adipiscing');
Insert into CS_COMMENTS (ID,POST_ID) values (98,13);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (99,789110754,to_timestamp('14-JUN-16 05.08.08.839471000 PM','DD-MON-RR HH.MI.SSXFF AM'),'nisi a odio semper cursus. Integer mollis. Integer');
Insert into CS_COMMENTS (ID,POST_ID) values (99,4);
Insert into CS_MSGS (ID,USER_ID,DATETIME,CONTENT) values (100,486301748,to_timestamp('14-JUN-16 05.08.08.978315000 PM','DD-MON-RR HH.MI.SSXFF AM'),'vel est tempor bibendum. Donec felis orci, adipiscing non,');
Insert into CS_COMMENTS (ID,POST_ID) values (100,15);
