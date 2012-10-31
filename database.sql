--THIS FILE HAS ALL THE MAJOR SQL QUERIES USED IN THIS PROJECT
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
company_news text,
general_news text,
check((company_news is not null) or (general_news is not null))
);

create table buy_sell(
transaction_time timestamp,
total_price int,
no_of_shares int,
isbuy varchar(2),
uid int references users(uid),
cid int references company(cid)
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
--GET TIME REMAINING
delimiter //
drop procedure if exists get_time;
create procedure get_time()
begin
	declare start_time time;
	declare cur_time time;
	set start_time=(select start_time from gameconf);
	set cur_time=curtime();
	select start_time;
	select cur_time;
	--select if(cur_time()>start_time,(select subtime(cur_time(),) remain) ,(select subtime(start_time,cur_time)));
end
//
