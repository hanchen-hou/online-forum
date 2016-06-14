-- //
-- // CS_USERS
-- // user.php
-- //
-- insert into CS_USERS
insert into CS_USERS 
			(id, email, name, pw_md5, salt, register_datetime, status) values
			(:id, :email, :name, :pw_md5, :salt, CURRENT_TIMESTAMP, :status)

-- select all users in the table
select * from CS_USERS

-- select a user with target id
select * from CS_USERS where id=:user_id

-- select a user with target name
select * from CS_USERS where name=:user_name

-- change a user password
update CS_USERS set pw_md5=:new_pw_md5 where id=:id

-- how many posts a user makes in each category
select category_name, count(*)
from CS_POSTS_DETAIL
where user_id = :id
group by category_name
UNION
select category_name, 0
from CS_POSTS_DETAIL
where category_name not in (select category_name
														from CS_POSTS_DETAIL
														where user_id = :id)

-- find the most diligent user
-- the most diligent user means a user makes posts in each category in the last 24 hours
select * 
from CS_Users u
where not exists ((select c.id
					         from CS_CATEGORIES c)
					         MINUS
					         (select category_id
					         from CS_POSTS_DETAIL pd
					         where pd.user_id = u.id and pd.DATETIME > (select current_timestamp - 1 from dual)))


-- //
-- // CS_ADMINS
-- // admins.php
-- //
-- insert into CS_ADMINS
insert into CS_ADMINS 
(id, senior_id ) values
(:id, :senior_id )

-- select all admins in the table
select * 
from CS_ADMINS a, CS_USERS u
where a.id = u.id

-- select an admin with target id
select * 
from CS_ADMINS a, CS_USERS u 
where a.id = u.id and u.id=:id and a.id=:id

-- select an admin with target name
select * 
from CS_ADMINS a, CS_USERS u 
where a.id = u.id and u.name=:user_name

-- //
-- // CS_CATEGORIES
-- // categories.php
-- //
-- insertion
insert into CS_CATEGORIES 
(name, datetime, admin_id) values
(:name, CURRENT_TIMESTAMP, :admin_id)

-- select category by category_id
select * from CS_CATEGORIES where id=:id

-- select all categories
select * from CS_CATEGORIES

-- get hottest category
-- the category which has the most number of posts
-- P.s. can return more than one categories
select CATEGORY_ID, CATEGORY_NAME, POSTS_NUM
from CATEGORIES_VIEW
where POSTS_NUM = (select max(POSTS_NUM) from CATEGORIES_VIEW)

-- //
-- // CS_MSGS
-- // msgs.php
-- //
-- insert into CS_MSGS
insert into CS_MSGS (id, user_id, datetime, content) values
			(:id, :user_id, CURRENT_TIMESTAMP, :content)

-- select all 
select * from CS_MSGS

-- select by msgs id
select * from CS_MSGS where id=:id

-- delete by msgs id (these two queries can be used to delete both posts and comments)
-- if the id is comment, it will delete the comment
-- if the id is post, it will delete the post and comments
-- we only need to operate the CS_MSGS table since 
-- the CS_POSTS andf CS_COMMENTS have FK to CS_MSGS
delete from CS_MSGS where id in (select id from CS_COMMENTS where post_id = :id);
delete from CS_MSGS where id = :id;

-- //
-- // MSGS_SEQ
-- // msgs_seq.php
-- //
-- get next value
select MSGS_SEQ.nextval from dual

-- get current value
select MSGS_SEQ.currval from dual

-- //
-- // CS_POSTS
-- // posts.php
-- //
-- insertion
insert into CS_POSTS (id, category_id, title) values
			(:id, :category_id, :title)

-- select all
select * from CS_POSTS

-- select a post by post_id
select p.id, p.title, m.content, TO_CHAR(m.datetime,'YYYY-MM-DD') as DATETIME, u.name as USER_NAME
from CS_POSTS p, CS_MSGS m, CS_USERS u 
where p.id = :id and p.id = m.id and m.user_id = u.id

-- select posts by category_id
select p.id, p.title, m.content, TO_CHAR(m.datetime,'YYYY-MM-DD') as DATETIME, u.id as USER_ID, u.name as USER_NAME, u.status as USER_STATUS
from CS_POSTS p, CS_MSGS m, CS_USERS u 
where p.category_id=:category_id and p.id = m.id and m.user_id = u.id 
Order BY m.datetime DESC

-- //
-- // CS_COMMENTS
-- // comments.php
-- //
-- insert 
insert into CS_COMMENTS (id, post_id) values
			(:id, :post_id)

-- select all
select * from CS_COMMENTS

-- select a comment by its id
Select m.content, m.datetime, u.name, m.visible 
From CS_COMMENTS c, CS_MSGS m, CS_USERS u 
Where c.id = :id and m.id = c.id and u.id = m.user_id 

-- select all comments in a post by post_id
Select m.id, m.content, TO_CHAR(m.datetime,'YYYY-MM-DD') as DATETIME, u.id as USER_ID, u.name as USER_NAME, u.status as USER_STATUS
From CS_COMMENTS c, CS_MSGS m, CS_USERS u 
Where c.post_id=:post_id and c.id = m.id and u.id = m.user_id
Order BY m.datetime ASC

-- //
-- // CS_BANNED_USERS
-- // banned_users.php
-- //
-- ban by user_id
-- an admin cannot be banned
-- use IF statement to check
DECLARE 
 num1 NUMBER;
 num2 NUMBER;
BEGIN
 Select count(*) INTO num1 from CS_ADMINS where id=:user_id;
 Select count(*) INTO num2 from CS_ADMINS where id=:admin_id;
IF (num1 = 0 and num2 != 0)
Then
INSERT INTO CS_BANNED_USERS 
(ID, ADMIN_ID, BANNED_DATETIME) 
values 
(:user_id, :admin_id, CURRENT_TIMESTAMP);
UPDATE CS_USERS SET STATUS=1 WHERE ID=:user_id;
END IF;
END;

--unban by user_id
Delete from CS_BANNED_USERS 
Where id=:user_id;
update CS_USERS set STATUS=0 where id = :user_id;

