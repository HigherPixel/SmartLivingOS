drop database if exists Healthdb;

create database Healthdb;

use healthdb;

create table User(
	AccountNum int unsigned not null auto_increment,
    UserName varchar(15), 
    Password varchar(45),
    Email varchar(50),
    StreetAddr varchar(50),
    AptNumAddr varchar (6),
    CityAddr varchar(20),
    StateAddr varchar(2),
    ZipAddr varchar(5),
    NationalAddr varchar(15),
    FirstName varchar(20),
    LastName varchar(20),
    DOB date,
    Height smallint(1) unsigned, /*in inches?*/
    Sex varchar(9),
    Gender varchar(15),
    /*Averages per day for the month*/
    primary key(AccountNum)
);

create table MonthlyAve(
	MonthID int unsigned not null auto_increment,
	AccountNum int unsigned,
    MonthN tinyint(1) unsigned not null, /*N keeps track of the number of days worth of info that have been stored in this table so far.
									It will be used to compute the average of each item in the database.*/
    AveTimeSpentInBed varchar(4),
    AveSpentAsleep varchar(4),
    AveBedTime varchar(4),
    AveWakeTime varchar(4),
    AveTimeAwakeInBed varchar(4),
    AveExerciseTime varchar(4),
    AveSteps mediumint(1),
    AveCal mediumint(1),
    AveSodium smallint(1),
    AveTransFat smallint(1),
    AveProtien smallint(1),
    AveSaturatedFat smallint(1),
    AveCholesterol smallint(1),
    AveMood smallint(1),
    MoodFlag tinyint(1),
    primary key(MonthID, AccountNum),
    constraint UserFK3
		foreign key(AccountNum)
        references User(AccountNum)
        on delete cascade on update cascade
);

create table WeeklyAve(
	WeekID int unsigned not null auto_increment,
    AccountNum int unsigned,
    MonthN tinyint(1) unsigned not null, /*N keeps track of the number of days worth of info that have been stored in this table so far.
									It will be used to compute the average of each item in the database.*/
    AveTimeSpentInBed varchar(4),
    AveSpentAsleep varchar(4),
    AveBedTime varchar(4),
    AveWakeTime varchar(4),
    AveTimeAwakeInBed varchar(4),
    AveExerciseTime varchar(4),
    AveSteps mediumint(1),
    AveCal mediumint(1),
    AveSodium smallint(1),
    AveTransFat smallint(1),
    AveProtien smallint(1),
    AveSaturatedFat smallint(1),
    AveCholesterol smallint(1),
    AveMood smallint(1),
    MoodFlag tinyint(1),
    primary key(WeekID, AccountNum),
    constraint UserFK4
		foreign key(AccountNum)
        references User(AccountNum)
        on delete cascade on update cascade
);

create table Day(
	DayID int unsigned not null auto_increment,
    AccountNum int unsigned,
	DayDate date not null,
    Weight smallint(1) unsigned, /*in lbs?*/
    Steps mediumint(1) unsigned, /*a mediumint stores up to 16-million values and an ultramarathon (125miles) is ~250,000 steps. So it is the best value*/
    AverageHeartRate tinyint(1) unsigned,
    AverageHeartVariablility tinyint(1), /*I think heart-rate variability is typically measured in miliseconds*/
    primary key(DayID, AccountNum),
    constraint UserFK1
		foreign key(AccountNum)
        references User(AccountNum)
        on delete cascade on update restrict
);


create table Mood(
    DayID int unsigned unique,
    Anxiety tinyint(1),
    Anger tinyint(1),
    Sadness tinyint(1),
    Numbness tinyint(1),
    Rumination tinyint(1),
    LossOfAppitite tinyint(1),
    ExcessiveAppitite tinyint(1),
    TroubleSleeping tinyint(1),
    LowSelfEsteem tinyint(1),
    Mania tinyint(1),
    Tiredness tinyint(1),
    Unmotivated tinyint(1),
    MoodSwings tinyint(1),
    OverallMood tinyint(1) unsigned,
    primary key(DayID),
    
    constraint DayFK1
		foreign key(DayID)
        references Day(DayID)
        on delete cascade on update cascade
);

create table sleep(
	DayID int unsigned,
    Quality tinyint(1) unsigned, 
    Duration varchar(4),
    TimeInBed varchar(4),	/*hh-mm. Could also use DateTime but we really only need the time part. This can be changes later pretty easily*/
    TimeFellAsleep varchar(4),
    TimeOfWake varchar(4),
    TimeOutOfBed varchar(4),
    primary key(DayID),
    
    constraint DayFK2
		foreign key(DayID)
        references Day(DayID)
        on delete cascade on update cascade
);



create table FoodOrDrink(
	FoodID int unsigned not null auto_increment,
    FoodName varchar(45) not null,
    Calories mediumint(1) unsigned,
    SaturatedFat smallint(1) unsigned,
    TransFat smallint(1) unsigned,
    Cholesterol smallint(1) unsigned,
    Sodium smallint(1) unsigned,
    DiataryFiber smallint(1) unsigned,
    Sugars smallint(1) unsigned,
    Protien smallint(1) unsigned,
    VA smallint(1) unsigned,
    VB smallint(1) unsigned,
    VC smallint(1) unsigned,
    VD smallint(1) unsigned,
    VE smallint(1) unsigned,
    VK smallint(1) unsigned,
    Iron smallint(1) unsigned,
    Potassium smallint(1) unsigned,
    Calcium smallint(1) unsigned,
    Magnisium smallint(1) unsigned,
    Omega3 smallint(1) unsigned,
    Water smallint(1) unsigned,
    primary key(FoodID)
);

create table Nutrition( /*Nutrition is an intermediary table between Day and FoodOrDrink. Day and FoodOrDrink
						have a many to many relationship because the user can consume multibple */
	DayID int unsigned,
    FoodID int unsigned,
    QuantityNum int unsigned,
    primary key(DayID, FoodID),
    
    constraint DayDateFK1
		foreign key(DayID)
        references Day(DayID)
        on delete cascade on update cascade,
	constraint FoodFK1
		foreign key(FoodID)
        references FoodOrDrink(FoodID)
        on delete cascade on update cascade
);

create table Exercise(
	ExerciseID int unsigned not null auto_increment,
	DayID int unsigned,
    ActivityName varchar(45),
    Duration varchar(4),
    Repititions smallint(1),
    sets tinyint(1),
    primary key(ExerciseID, DayID),
    
    constraint DayFK3
		foreign key(DayID)
        references Day(DayID)
        on delete cascade on update cascade
);

create table Medications(
	MedicationID int unsigned not null auto_increment,
    AccountNum int unsigned,
    DatePerscribed date,
    MedicationName varchar(20),
    LastFilled date,
    Perscription varchar(45),
    primary key(MedicationID, AccountNum),
    
    constraint UserFK5
		foreign key(AccountNum)
        references User(AccountNum)
        on delete cascade on update restrict
);

create table EncounterHistory(
	EncounterID int unsigned not null auto_increment,
    AccountNum int unsigned,
    EncounterDate date, /*date of appointment*/
    Facility varchar(20), /*where was the appointment*/
    Specialty varchar(20), /*what is the facility or doctors specialty*/
    clinitian varchar(40), /*what is the name of the medical personel visited*/
    Reason varchar(20), /*what is the reason for the visit*/
    VisitType varchar(20), /*what is the type of visti*/
    primary key(EncounterID, AccountNum),
    
    constraint UserFK6
		foreign key(AccountNum)
        references User(AccountNum)
        on delete cascade on update restrict
);

create table Immunizations(
	ImmunizationID int unsigned not null auto_increment,
    AccountNum int unsigned,
    ImmunizationDate date,
    ImmunizationType varchar(20),
    NumberRecieved smallint(1) unsigned,
    primary key(ImmunizationID, AccountNum),
    
    constraint UserFK7
		foreign key(AccountNum)
        references User(AccountNum)
        on delete cascade on update restrict
);

create table Diagnosis(
	DiagnosisID int unsigned not null auto_increment,
    AccountNum int unsigned,
    DiagnosisDate date,
    DiagnosisType varchar(20),
    DiagnosisStatus varchar(10), /*is it ongoing, or has it been resloved*/
    primary key(DiagnosisID, AccountNum),
    
    constraint UserFK8
		foreign key(AccountNum)
        references User(AccountNum)
        on delete cascade on update restrict
);

create table Allergies(
	AllergyID int unsigned not null auto_increment,
	AccountNum int unsigned,
	AllergyDate date, /*what date was the allergy diagnosed*/
    AllergyType varchar(20),
    AllergyStatus varchar(10), /*is the allergy ongoing or resolved*/
    primary key(AllergyID, AccountNum),
    
    constraint UserFK9
		foreign key(AccountNum)
        references User(AccountNum)
        on delete cascade on update restrict
);

/*The following are tables track things that the user may measure reguallarly or irreguarly.
For example. A user may measure blood sugar everytime they gets a checkup (every two years) or they
may measure blood sugar three times a day (If they have diabetes). These need to be in seperate tables
because the regularity of the measurement changes from user to user. This insures that there are not 
a lot of null columbs in the database.*/
create table BloodPressure(
	BloodPressureID int unsigned not null auto_increment,
    AccountNum int unsigned,
    BPMeasurementDate datetime,
    Systolic tinyint(1) unsigned, /*mmHg*/
    Diastolic tinyint(1) unsigned, /*mmHg*/
    primary key(BloodPressureID, AccountNum),
    
    constraint UserFK10
		foreign key(AccountNum)
		references User(AccountNum)
		on delete cascade on update cascade
);

create table BloodSugar(
	BloodSugarID int unsigned not null auto_increment,
    AccountNum int unsigned,
    BSMeasurementDate datetime,
    BloodSugarMeasure tinyint(1) unsigned,
    primary key(BloodSugarID, AccountNum),
    
    constraint UserFK11
		foreign key(AccountNum)
        references User(AccountNum)
        on delete cascade on update cascade
);
        
create table Cholesterol(
	CholesterolID int unsigned not null auto_increment,
    AccountNum int unsigned,
    CholMeasurementDate datetime,
    CholMeasure tinyint(1) unsigned,
    primary key(CholesterolID, AccountNum),
    
	constraint UserFK12
		foreign key(AccountNum)
        references User(AccountNum)
        on delete cascade on update cascade
);
