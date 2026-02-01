CREATE DATABASE campusecoclub;

CREATE TABLE Users (
	UserID int,
    UserName varchar(100),
    Email varchar(200),
    UserPassword varchar(20),
    UserRole varchar(20),
    UserStatus varchar(20)
);

CREATE TABLE Student (
	EcoPoints int
);

CREATE TABLE Event (
	EventID varchar(20),
    EventName varchar(100),
    EventDate date,
	Location varchar(100),
    EventDescription varchar(150),
	EventStatus varchar(50)
);

CREATE TABLE RecyclingLog (
	LogID varchar(20),
	LogType varchar(50),
    weight decimal(3,2),
    LogStatus varchar(20),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE Task (
	TaskID varchar(20),
    TaskName varchar (50),
    TaskDescription (150),
    TaskStatus (20),
    FOREIGN KEY (EventID) REFERENCES Events(EventID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE Notification (
	NotificationID varchar(20),
    message varchar(30),
    NotificationTime datetime,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE Registration (
	RegistrationID varchar(20),
    RegistrationStatus varchar (20),
    FOREIGN KEY (EventID) REFERENCES Events(EventID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE Announcement (
	AnnouncementID varchar(20),
    message varchar(30),
    AnnouncementDate datetime,
     FOREIGN KEY (EventID) REFERENCES Events(EventID)
);