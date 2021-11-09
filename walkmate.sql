CREATE TABLE user (
    userKey int primary key, 
    ID  varchar(30) not null unique,
    password varchar(255) not null,
    gender varchar(10) not null,
    age int not null,
    email varchar(100) not null,
    address varchar(255) not null
    nickname varchar(50) not null
);