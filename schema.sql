
DROP DATABASE IF EXISTS Records;
CREATE DATABASE Records;
USE Records;

DROP TABLE IF EXISTS userInfo;

CREATE TABLE Users (
    id VARCHAR(20) NOT NULL PRIMARY KEY,
    firstName char(40) NOT NULL ,
    lastName char(40) NOT NULL,
    password char(40) NOT NULL,
    email char(40) NOT NULL,
    date_joined char(10) NOT NULL
);
insert into Users values(0,"John","Doe","password123",'admin@bugme.com',"01/01/01");


DROP TABLE IF EXISTS Issues;
CREATE TABLE Issues(
id char(20)  NOT NULL PRIMARY KEY,
title char(40) DEFAULT NULL,
description char(255) DEFAULT NULL,
type char(40) DEFAULT NULL,
priority char(40) DEFAULT NULL,
status char(40) DEFAULT NULL,
assigned_to char(20) DEFAULT NULL,
created_by char(20) DEFAULT NULL,
created char(20) DEFAULT NULL,
updated char(20) DEFAULT NULL

);

insert into Issues values("0","Broken Homepage","I need to implement the ajax, sql and php","Bug","High","OPEN","admin@bugme.com","Technician #4","11/28/2019","12/01/2019");
insert into Issues values("100","XSS Vulnerability in Add User Form"," Dah XSS got problems homeboy","Proposal","Medium","OPEN","magnolia.james@gmail.com","Technician #72","11/27/2019","11/28/2019");
