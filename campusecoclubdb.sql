/* 1. Delete the old database so we start fresh */
DROP DATABASE IF EXISTS campusecoclub;

/* 2. Create the new database */
CREATE DATABASE campusecoclub;
USE campusecoclub;

/* 3. Create Users Table */
CREATE TABLE Users (
    UserID int NOT NULL AUTO_INCREMENT,
    name varchar(100),
    email varchar(100),
    password varchar(100),
    role varchar(50),
    status varchar(50),
    PRIMARY KEY (UserID)
);

/* 4. Create Student Table */
CREATE TABLE Student (
    UserID int NOT NULL,
    ecoPoints int DEFAULT 0,
    PRIMARY KEY (UserID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE
);

/* 5. Create Event Table */
CREATE TABLE Event (
    eventID varchar(20),
    name varchar(100),
    date date,
    location varchar(100),
    description varchar(255),
    status varchar(50),
    PRIMARY KEY (eventID)
);

/* 6. Create RecyclingLog Table */
CREATE TABLE RecyclingLog (
    logID varchar(20),
    type varchar(50),
    weight float,
    status varchar(50),
    userID int,
    PRIMARY KEY (logID),
    FOREIGN KEY (userID) REFERENCES Users(UserID)
);

/* 7. Create Task Table */
CREATE TABLE Task (
    taskID varchar(20),
    name varchar(100),
    description varchar(255),
    status varchar(50),
    eventID varchar(20),
    userID int,
    PRIMARY KEY (taskID),
    FOREIGN KEY (eventID) REFERENCES Event(eventID),
    FOREIGN KEY (userID) REFERENCES Users(UserID)
);

/* 8. Create Notification Table */
CREATE TABLE Notification (
    notificationID varchar(20),
    message varchar(255),
    time datetime,
    userID int,
    PRIMARY KEY (notificationID),
    FOREIGN KEY (userID) REFERENCES Users(UserID)
);

/* 9. Create Registration Table */
CREATE TABLE Registration (
    registrationID varchar(20),
    status varchar(50),
    userID int,
    eventID varchar(20),
    PRIMARY KEY (registrationID),
    FOREIGN KEY (userID) REFERENCES Users(UserID),
    FOREIGN KEY (eventID) REFERENCES Event(eventID)
);

/* 10. Create Announcement Table */
CREATE TABLE Announcement (
    announcementID varchar(20),
    message varchar(255),
    date date,
    eventID varchar(20),
    PRIMARY KEY (announcementID),
    FOREIGN KEY (eventID) REFERENCES Event(eventID)
);
