--TABLES 
--
create table users(
uid int primary key auto_increment,
first_name varchar(30) not null,
last_name varchar(30),
age int(3) not null,
password varchar(30) not null,
money int default 100000, --TODO edit the default amount
email varchar(100) not null unique,
check(length(password)>5)
);

create table company(
cid int primary key auto_increment,
name varchar(30) not null,
no_shares int not null,
company_type varchar(30) not null,
history text,
worth varchar(10) --this is not used anywhere hence varchar is enough
);
-- time is in HH:MM:SS format
create table news(
time time not null,
description text,
title varchar(100)
);

create table buy_sell(
transaction_time timestamp,
total_price int,
no_of_shares int,
isbuy boolean,
uid int references users(uid),

);

create table stock_record(
time time not null,
price_per_share int not null,
cid int references company(cid)
);

create table owns_shares_of(
uid int references users(uid),
cid int references company(cid),
no_of_shares int not null,
);

create table company_locations(
cid int references company(cid),
location varchar(30) not null
);
--TABLES END HERE

--DATABASE INFORMATION to THE ADMIN
SELECT table_name,
sum( data_length + index_length ) / (1024*1024) db_size, 
sum( data_free )/ (1024*1024) db_free
FROM information_schema.tables
where table_schema='mockstock' group by table_name;
--GROUP BY table_schema ; 

-- GET FULL NAME
select concat(first_name,' ',last_name) as user_name from users;

--GET TIME STATUS OF THE GAME
drop procedure if exists get_time_status;
create procedure get_time_status()
begin
	declare fetch_time time;
    declare cur_time time;
    select start_time into fetch_time from gameconf;
	set cur_time=curtime();
    if fetch_time-cur_time<0 and addtime(fetch_time,'01:00:00')-cur_time>0
        then
        select "Game ends in" as game_status,subtime(addtime(fetch_time,'01:00:00'),cur_time) as time;
    elseif fetch_time-cur_time>0
        then
        select "Game starts in" as game_status,subtime(fetch_time,cur_time) as time;
    else
        select "Game Over" as game_status;
    end if;
end


-- EVENT which deletes useless news and puts it to a buffer news;
SET GLOBAL event_scheduler = ON;
SET GLOBAL event_scheduler = OFF;
create event speedup_news
on schedule every 5 minute
do begin
    declare fetch_time time;
    select start_time into fetch_time from gameconf;
    insert into news_history select * from news where addtime(fetch_time,addtime(time,'00:05:00'))<curtime();
    delete from news where addtime(fetch_time,addtime(time,'00:05:00'))<curtime();
end
alter event speedup_news disable;
alter event speedup_news enable;


--procedure to recover news from news_history
create procedure recover_news()
begin
    insert into news select * from news_history;
    delete from news_history;
end

--get current news
select news.* from news,gameconf where addtime(gameconf.start_time,news.time)<curtime() and addtime(gameconf.start_time,news.time)>subtime(curtime(),'00:05:00');
