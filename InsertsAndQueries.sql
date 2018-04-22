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
insert into Allergies(AccountNum, AllergyDate, AllergyType, AllergyStatus)
values (3, '1995-2-22', 'Dust', 'Ongoing');
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
/*The following are the medical queries.*/
/*This Query gets the encoutner history of the user*/
select EncounterDate, Facility, Specialty, Clinitian, Reason, VisitType from EncounterHistory where AccountNum=3;
/*This query gets the medication history of the user*/
select DatePerscribed, MedicationName, LastFilled, Perscription from Medications where AccountNum=3;
/*This query gets the Diagnosis for the user*/
select DiagnosisDate, DiagnosisType, DiagnosisStatus from Diagnosis where AccountNum=3;
/*This query gets the Immunization from the user*/
select ImmunizationDate, ImmunizationType, NumberRecieved from Immunizations where AccountNum=3;
/*This query gets the Allerys from the user*/
select AllergyDate, AllergyType, AllergyStatus from Allergies where AccountNum=3;

/*The following is the user's sleep queries.*/
/*The time that the user has slept*/
select Duration from Sleep where DayID=4;
/*Time the user went to bed OR the time the user spent in bed in TOTAL. (The use of this variable can change if we want track time spent in bed insead of the specific times the user went to bed and woke up.)*/
select TimeInBed from Sleep where DayID=4;
/*The quality of the users sleep*/
select Quality from Sleep where DayID=4;
/*The following grabs all three of the above at once.*/
select Duration, Quality, TimeInBed from Sleep where DayID=4;

/*The following are the user's Exercise queries.*/
/*The time spent exercising on a particular day. It returns a list of the times the user spent on each exercise.*/
select Duration from Exercise where DayID=4;
/*The user's steps on a particular day*/
select Steps from Day where DayID=4;
/*The following queries pull the user's time, reps, and sets spent on a particular exercise on a particular day. 
The name of the activity is needed to pull all of the following.*/
/*Time sepent on a particular exercise on a particular day*/
select Duration from exercise where (DayID=4) and (ActivityName='Dying');
/*Reps spent on a aprticular exercise on a particualr day*/
select Sets from exercise where (DayID=4) and (ActivityName='Dying');
/*Time, Reps, and Sets spent on a particualr exercise. *Acombination of all of the above*/
select Duration, Repititions, Sets from exercise where (DayID=4) and (ActivityName='Dying');

/*The following queries get all of the user's nutrition information for a day.
The first queries get one piece of information. The last one gets all of the users nutrition info for that
Day at once*/
/*Calories, Sodium, Sugars, Protien, Trans Fat, Cholesterol on a particular day.*/
select Calories, Sodium, Sugars, Protien, TransFat, SaturatedFat, Cholesterol, DiataryFiber
from Nutrition 
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where DayID = 4;
/*Calories*/
select Calories
from Nutrition 
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where DayID = 4;
/*Sodium*/
select Sodium
from Nutrition 
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where DayID = 4;
/*Sugars*/
select Sugars
from Nutrition 
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where DayID = 4;
/*Protien*/
select Protien
from Nutrition 
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where DayID = 4;
/*Trans Fat*/
select TransFat
from Nutrition 
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where DayID = 4;
/*Saturated Fat*/
select SaturatedFat
from Nutrition 
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where DayID = 4;
/*Cholesterol*/
select Cholesterol
from Nutrition 
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where DayID = 4;
/*DiataryFiber*/
select DiataryFiber
from Nutrition 
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where DayID = 4;

/*The following queries get the user's mood information for a particular day*/
/*The user's overall mood (measured on a scale)*/
select OverallMood from Mood where DayId=4;
/*The answers to all of the user's true or false questions*/
select Anxiety, Anger, Sadness, Numbness, Rumination, LossOfAppitite, ExcessiveAppitite, TroubleSleeping, LowSelfEsteem, Mania, Tiredness, Unmotivated, MoodSwings
from Mood
where DayID=4;

/*The following queries retrieve the user's weight, Heart Rate, Cholesterol, Blood Sugar, Blood Pressure, Heart Variability*/
/*Weight*/
select weight from Day where DayID=4;
/*Heart Rate*/
select AverageHeartRate from Day where DayID=4;
/*Cholesterol*/
select CholMeasurementDate, CholMeasure 
from Cholesterol 
where CholMeasurementDate=(select Max(cholMeasurementDate) from Cholesterol) and AccountNum=3;
/*Blood Sugar*/
select BSMeasurementDate, BloodSugarMeasure
from BloodSugar
where BSMeasurementDate=(select Max(BSMeasurementDate) from BloodSugar) and AccountNum=3;
/*Blood Pressure*/
select BPMeasurementDate, Systolic, Diastolic
from BloodPressure
where BPMeasurementDate=(select Max(BPMeasurementDate) from BloodPressure) and AccountNum=3;
/*Heart Variability*/
select AverageHeartVariablility from Day where DayID=4;

/*The next Queries get all of the Average MonthTable info*/
select MonthDate, AveBedTime, AveSpentAsleep, AveExerciseTime, AveSteps, AveCal, AveSodium, AveTransFat, AveProtien, AveSaturatedFat, AveCholesterol, AveMood, MoodFlag
from MonthlyAve
where (MonthDate=(select Max(MonthDate) from MonthlyAve where AccountNum=1)) and (AccountNum=1);
/*The next Queries get all of the Average WeekTable info*/
select WeekDate, AveBedTime, AveSpentAsleep, AveExerciseTime, AveSteps, AveCal, AveSodium, AveTransFat, AveProtien, AveSaturatedFat, AveCholesterol, AveMood, MoodFlag
from WeeklyAve
where (WeekDate=(select Max(WeekDate) from WeeklyAve where AccountNum=1)) and (AccountNum=1);

select Duration, DayDate
from Day
inner join Exercise
on Day.DayID = Exercise.DayID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);

select Steps, DayDate
from Day
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);

select Duration, DayDate
from Day
inner join Exercise
on Day.DayID = Exercise.DayID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastYear . "') and (AccountNum = 3);

select EncounterDate, Facility, Specialty, Clinitian, Reason, VisitType from EncounterHistory where AccountNum=3;

select DiagnosisDate, DiagnosisType, DiagnosisStatus from Diagnosis where AccountNum=3;

select DatePerscribed, MedicationName, LastFilled, Perscription from Medications where AccountNum=3;

select ImmunizationDate, ImmunizationType, NumberRecieved from Immunizations where AccountNum=3;

select AllergyDate, AllergyType,AllergyStatus from Allergies where AccountNum=3;

select BSMeasurementDate, BloodSugarMeasure
from BloodSugar
where BSMeasurementDate=(select Max(BSMeasurementDate) from BloodSugar) and AccountNum=3

select BPMeasurementDate, Systolic, Diastolic
from BloodPressure
where BPMeasurementDate=(select Max(BPMeasurementDate) from BloodPressure) and AccountNum=3;

select CholMeasurementDate, CholMeasure 
from Cholesterol 
where CholMeasurementDate=(select Max(cholMeasurementDate) from Cholesterol) and AccountNum=3;

select Calories, DayDate
from Day
inner join Nutrition
on Day.DayID = Nutrition.DayID
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastYear . "') and (AccountNum = 3);

select Calories, Sodium, Sugars, Protien, TransFat, SaturatedFat, Cholesterol, DiataryFiber
from Day
inner join Nutrition
on Day.DayID = Nutrition.DayID
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);

select Calories, DayDate
from Day
inner join Nutrition
on Day.DayID = Nutrition.DayID
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);

select DayDate, Quality, Duration
	from Day
	inner join Sleep
	on Sleep.DayID = Day.DayID
	where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);
  
  select Duration, DayDate
from Day
inner join Exercise
on Day.DayID = Exercise.DayID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);

select Steps, DayDate
from Day
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);

select OverallMood, DayDate
from Day
inner join Mood
on Day.DayID = Mood.DayID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);

select DayDate, Quality, Duration, TimeInBed
	from Day
	inner join Sleep
	on Sleep.DayID = Day.DayID
	where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);
  
  select DayDate, Quality, Duration
	from Day
	inner join Sleep
	on Sleep.DayID = Day.DayID
	where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastYear . "') and (AccountNum = 3);
  
  
