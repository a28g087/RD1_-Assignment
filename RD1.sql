CREATE DATABASE RD1;

USE RD1;

CREATE TABLE Weather (
    locationName varchar(20),
    startTime DATETIME,
    endTime DATETIME,
    PoP12h varchar(20),
    T varchar(20),
    MaxT varchar(20),
    MinT varchar(20),
    MaxAT varchar(20),
    MinAT varchar(20),
    Td varchar(20),
    UVI varchar(20),
    RH varchar(20),
    MaxCI varchar(20),
    MinCI varchar(20),
    WS varchar(20), 
    Wx varchar(20),
    WeatherDescription varchar(1000),
    WD varchar(20)
)

CREATE TABLE location(
    locationName varchar(20) PRIMARY KEY,
    geocode varchar(20),
    lat varchar(20),
    lon varchar(20)
)