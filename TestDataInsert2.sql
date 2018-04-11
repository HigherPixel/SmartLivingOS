use healthdb;

insert into User(AccountNum, UserName, Password, Email, FirstName, LastName, DOB, Height)
values(1, "kob", "kbbobbb", "kot@bobby.com","kobby","kobbbybobken",'2002-2-22',22);

insert into User(AccountNum, UserName, Password, Email, FirstName, LastName, DOB, Height)
values(2, "cath", "cathy", "chat@bobby.com","chathie","chatbybobken",'2002-2-22',22);

insert into Day values(1,1, '2020-2-22',14, 1200, 60, 20);
insert into Day values(2,1,'2014-5-03',15, 1300, 13, 0);
insert into Day values(3,2,'1999-4-30',4,4,4,4);

insert into FoodOrDrink(Calories, SaturatedFat, TransFat, Cholesterol, Sodium, DiataryFiber, VA, VB, VC, VD, VE, VK, Iron, Potassium, Calcium, Magnisium, Omega3, Water, FoodName)
values(12,12,12,12,12,12,12,12,0,0,0,0,0,0,0,0,0,0,"transFat");
insert into FoodOrDrink values(2,'Taco Bell',1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
insert into FoodOrDrink values(3,'Gummy Leaches',2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,4,4);
insert into FoodOrDrink values(4,'Baked pot',3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,6,7);

insert into Mood values (2,1,1,1,1,1,1,1,1,1,1,1,1,1,8);

insert into Sleep(DayID, Quality, Duration, TimeInBed, TimeFellAsleep, TimeOfWake, TimeOutOFBed)
values(1,1,6,'1230','1430','1630','1740');

insert into Sleep values(2,3,4,'0440','1504','1505','1847');
insert into Sleep values(3,5,3,'0012','0013','0014','0014');

insert into Diagnosis values(NULL, 1, '2016-12-12', 'The flu', 'Resolved');
insert into EncounterHistory values(Null, 2, '2018-03-29', 'The Hospital', 'Healthcare', 'The Doctor', 'Not Well', 'Appointment');
insert into Immunizations values(Null, 2, '2018-05-05', 'Coodies', 200);
insert into medications values(Null, 2, '2018-3-4', 'Temocil', '2019-4-4', 'take 3 per day by mouth');
insert into allergies values(Null, 2, '2018-2-22', 'Boys', 'Resloved');
insert into BloodPressure values(Null, 1, Null, 12, 12);
insert into BloodSugar values(Null, 1, Null, 12);
insert into BloodSugar values(NUll, 2, Null, 23);
insert into Cholesterol values(null, 2, null, 12);
insert into Cholesterol values(null, 2, Null, 23);


insert into Exercise values(null, 2,'bob is sleding', null, 2,3);
insert into Exercise values(null, 3,'fate lifting','1340',null,null);
insert into Exercise values(null, 3,'rats','0013',400, null);

insert into Nutrition values(1,1,2);
insert into Nutrition values(1,3,14);
insert into Nutrition values(1,4,50);
insert into Nutrition values(3,2,14);

insert into MonthlyAve values(Null, 1, 1,  '0060', '0040', '0800', '0030','0003','0050',30000,40000,4,3,2,4,5,6,7);
insert into WeeklyAve values(Null, 1, 1,  '0060', '0040', '0800', '0030','0003','0050',30000,40000,4,3,2,4,5,6,7);



