CREATE TABLE user (
    userKey int primary key auto_increment, 
    ID  varchar(30) not null unique,
    password varchar(255) not null,
    gender varchar(10) not null,
    age int not null,
    email varchar(100) not null,
    address varchar(255) not null
    nickname varchar(50) not null
);

CREATE TABLE walk (
    walkKey int primary key auto_increment,
    title varchar(100) not null,
    location varchar(255) not null,
    nowMemberCount int not null,
    memberList json not null,
    maxMemberCount int not null,
    applyList json not null,
    requireList json,
    description varchar(255),
    hostID varchar(30) not null
);