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

--get invested money
select sum(owns_shares_of.no_of_shares*stock_record.price_per_share) as investment from owns_shares_of,stock_record,gameconf where addtime(stock_record.time,gameconf.start_time)<curtime() and addtime(stock_record.time,gameconf.start_time)>subtime(curtime(),'00:05:00') and owns_shares_of.uid=$_SESSION['id'] and owns_shares_of.cid=stock_record.cid;

--ranking
create view ranking as
select users.money cash_in_hand,sum(owns_shares_of.no_of_shares*stock_record.price_per_share) cash_invested,sum(owns_shares_of.no_of_shares*stock_record.price_per_share)+users.money as total, concat(users.first_name,' ',users.last_name) as full_name from owns_shares_of,stock_record,gameconf,users where addtime(stock_record.time,gameconf.start_time)<curtime() and addtime(stock_record.time,gameconf.start_time)>subtime(curtime(),'00:05:00') and owns_shares_of.cid=stock_record.cid and owns_shares_of.uid=users.uid group by users.uid order by total desc;

--list companies
select * from company where name like "%a%" and company_type like "%a%" and cid in (select cid from company_locations where location like "%a%");

--stock rates
--TODO this will work only after first 5 minutes
--soluion create a procedure for this
delimiter //
create procedure stock_rates()
begin
    declare fetch_time time;
    select start_time into fetch_time from gameconf;
    if curtime()<addtime(fetch_time,'00:05:00') then
        select stock_record.price_per_share as new_price,0 as percent,company.name from gameconf,stock_record inner join company on company.cid=stock_record.cid where addtime(gameconf.start_time,stock_record.time)<curtime() and addtime(gameconf.start_time,stock_record.time)>subtime(curtime(),'00:05:00');
    else
        select new.price_per_share new_price,((old.price_per_share-new.price_per_share)/old.price_per_share)*100 as percent ,company.name from (select stock_record.price_per_share,stock_record.cid from stock_record,gameconf where addtime(gameconf.start_time,stock_record.time)<curtime() and addtime(gameconf.start_time,stock_record.time)>subtime(curtime(),'00:05:00')) as new,(select stock_record.price_per_share,stock_record.cid from stock_record,gameconf where addtime(gameconf.start_time,stock_record.time)<subtime(curtime(),'00:05:00')and addtime(gameconf.start_time,stock_record.time)>subtime(curtime(),'00:10:00')) as old inner join company on old.cid=company.cid where new.cid=old.cid ;
    end if;
end
//
delimiter ;

--this will update the tables once user buys shares 
--Things which should happen when i insert into buy_sell
--update or insert into owns_shares_of
--update money in users
--TODO TEST THIS
DELIMITER //
create trigger transaction after insert on buy_sell
    for each row
        begin
        declare already_owned_cid int;
        declare cost_involved int;
        select (new.no_of_shares*stock_record.price_per_share) into cost_involved from stock_record,gameconf where addtime(stock_record.time,gameconf.start_time)<curtime() and addtime(stock_record.time,gameconf.start_time)>subtime(curtime(),'00:05:00');
        select cid into already_owned_cid from owns_shares_of where cid=new.cid and uid=new.uid;
        if already_owned_cid is not null
            then
                if new.isbuy=0 then
                    update owns_shares_of set no_of_shares=no_of_shares-new.no_of_shares where owns_shares_of.uid=new.uid and owns_shares_of.cid=new.cid;
                    update users set money=money+cost_involved where uid=new.uid;
                else
                    update owns_shares_of set no_of_shares=no_of_shares+new.no_of_shares where owns_shares_of.uid=new.uid and owns_shares_of.cid=new.cid;
                    update users set money=money-cost_involved where uid=new.uid;
                end if;
            else
                insert into owns_shares_of values(new.uid,new.cid,new.no_of_shares);
                update users set money=money-cost_involved where uid=new.uid;
        end if;
    end;
//
delimiter ;

--number of shares left of a company
select company.name,company.no_shares-sum(owns_shares_of.no_of_shares) as shares_left from company,owns_shares_of where company.cid=1 and owns_shares_of.cid=company.cid;
