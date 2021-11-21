CREATE TABLE walk (
    walkKey int primary key auto_increment,
    hostKey int not null,
    hostID varchar(30) not null,
    hostNickname varchar(255) not null,
    title varchar(100) not null,
    depLatitude decimal(17, 14) not null,
    depLongitude decimal(17, 14) not null,
    nowMemberCount int not null,
    maxMemberCount int not null,
    applyMemberCount int not null,
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

CREATE FUNCTION HAVERSINE(x1 DECIMAL(17, 14), y1 DECIMAL(17, 14), x2 DECIMAL(17, 14), y2 DECIMAL(17, 14))
        RETURNS DOUBLE
BEGIN
    DECLARE earthRadius DOUBLE;
    DECLARE deltaX DOUBLE;
    DECLARE deltaY DOUBLE;
    DECLARE sinX DOUBLE;
    DECLARE sinY DOUBLE;
    DECLARE distance DOUBLE;
    
        SET earthRadius = 6371;
        SET deltaX = RADIANS(ABS(x1 - x2));
        SET deltaY = RADIANS(ABS(y1 - y2));
        SET sinX = SIN(deltaX / 2) * SIN(deltaX / 2);
        SET sinY = SIN(deltaY / 2) * SIN(deltaY / 2);

        SET distance = 2 * earthRadius * ASIN(SQRT(sinX + COS(RADIANS(x1)) * COS(RADIANS(x2)) * sinY));
    
    RETURN distance;
END;
