CREATE TABLE `users` (
    id varchar(200),
    password varchar(50) not null,
    fullname varchar(50),
    email varchar(50) not null UNIQUE,
    dob date,
    gender int,
    PRIMARY KEY (id)
);
