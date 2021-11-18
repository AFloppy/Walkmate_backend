CREATE TABLE walk (
    walkKey int primary key auto_increment,
    hostKey int not null,
    hostID varchar(30) not null,
    title varchar(100) not null,
    depLocation varchar(255) not null,
    nowMemberCount int not null,
    maxMemberCount int not null,
    applyMemberCount int not null,
    requireList json,
    description varchar(255),
    depTime datetime not null,
    writeTime datetime not null
);

CREATE TABLE memberList (
    walkKey int not null,
    memberKey int not null,
    memberID varchar(30) not null,
    nickname varchar(255) not null,
    joinTime datetime not null
);

CREATE TABLE applyList (
    walkKey int not null,
    memberKey int not null,
    memberID varchar(30) not null,
    nickname varchar(255) not null,
    applyTime datetime not null
);