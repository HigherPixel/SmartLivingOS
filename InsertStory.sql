/*This is the first insert that will occur after the user is created and when Account settings are ajusted*/
insert into User(UserName, Password, Email, FirstName, LastName, DOB, Height, Sex)
values("SamIAm","awsomePassword","sadraper95@gmail.com","Samuel","Draper","1995-2-22",69, "male");


/*When the user logs in the following will need to execute:*/
/*The script will need to retreve the user's password and make sure it is correct. (I don't know anything about security but this is insecure)*/
select PassWord from User where UserName = 'SamIAm';
/*If the user's password is correct, we will need to get the user's AccountNum with the following*/
select AccountNum from User where UserName='SamIAm';

/*As soon as the user logs in the script should create a new day using the following insert*/
insert into Day(AccountNum, DayDate)
values(3, "2018-12-12");

/*This querie is used to find the DayID which is used to access sleep, Nutrition Exercise, Mood tables*/
select DayID from Day where (AccountNum=3) and (DayDate='2018-12-12');

/*When the user adds sleep data the following will be used*/
insert into Sleep(DayID, Quality, Duration, TimeInBed)
values(4, 7, '0700', '0900');

/*When the user adds Nutrition the following will exectue (!!!Only, If we don't get the api set up!!!)*/
/*Use the user's input to find the FoodID of the food they have put into the webpage*/
select FoodID from FoodOrDrink where FoodName='Gummy Leaches';
insert into Nutrition(DayID, FoodID)
values(4, 3);

/*When the user adds Exercise the following will execute*/
insert into Exercise(DayID, ActivityName, Duration, Repititions, Sets)
values(4, 'This Project', '2400', 0, 0);
update Day
set Steps=40000
where DayID=4;
/*note: if the user doesn't put reps or sets then the PHP script will need to insert 0's or null in thier place*/

/*When the user adds Emotion the follwoing will execute*/
insert into Mood(DayID, Anxiety, Anger, Sadness, Numbness, Rumination, LossOfAppitite, ExcessiveAppitite, TroubleSleeping, LowSelfEsteem, Mania, Tiredness, Unmotivated, MoodSwings, OverallMood)
values(4,1,0,0,0,0,0,0,0,0,0,0,0,0,2);

/*When the user adds Medical information the following will execute*/
insert into Allergies(AccountNum, AllergyDate, AllergyType)
values (3, '1995-2-22', 'Dust');
insert into BloodPressure(AccountNum, BPMeasurementDate, Systolic, Diastolic)
values (3, '1995-2-22', 30, 40);
insert into BloodSugar(AccountNum, BSMeasurementDate, BloodSugarMeasure)
values (3, '1995-2-22', 12);
insert into Cholesterol(AccountNum, CholMeasurementDate, CholMeasure)
values (3, '1995-2-22',12);
insert into Diagnosis(AccountNum, DiagnosisDate, DiagnosisType, DiagnosisStatus)
values (3, '1995-2-22', 'Social Anxiety', 'Ongoing');
insert into	EncounterHistory(AccountNum, EncounterDate, Facility, Specialty, Clinitian, Reason, VisitType)
values (3, '1995-2-22', 'VA Alexandria', 'General', 'Dr. Gary', 'Sick','visit');
insert into Immunizations(AccountNum, ImmunizationDate, ImmunizationType, NumberRecieved)
values (3, '1995-2-22', 'Rabies', '4');
insert into Medications(AccountNum, DatePerscribed, MedicationName, LastFilled, Perscription)
values (3, '1995-2-22', 'Chill Pills', '1995-12-30','Take 4 by mouth hourly');
update Day
set Weight=145, AverageHeartRate=78
where DayID=4;
/********************************All of the Below will be used for the reports********************************/

