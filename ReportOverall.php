<?php
require_once('../mysqli_connect.php');

function dbtophptime($dbtime)
{
	$newTime = substr_replace($dbtime,':',2,0);
	$date = '2000-00-00';
	$sec = ':00';
	$TimeString = $date . $newTime . $sec;
	return strtotime($TimeString);
}

function eliminateNullData($xData, $yData)
{
	$retArX = array();
	$retArY = array();
	$n = count($xData);
	$check = count($yData);
	if($n != $check)
	{
		return -1;
	}
	$ii = 0;
	while($ii < $n)
	{
		$currX = $xData[$ii];
		$currY = $yData[$ii];
		if($currX != null)
		{
			$retArX[] = $currX;
			$retArY[] = $currY;  
		}
		$ii = $ii + 1;
	}
	$retAr = array($retArX, $retArY);
	return $retAr;
}

function average($array)
{
	$ii = 0;
	$sum = array_sum($array);
	$n = count($array);
	if($n == 0)
	{
		return 0;
	}
	else
	{
		return $sum/$n;
	}
}

$todayStr = Date("Y-m-d");
$dateAr = getDate(strtotime("-1 week"));
$lastWeek = $dateAr['year'] . "-" . $dateAr['mon'] . "-" . $dateAr['mday'];
$dateAr = getDate(strtotime("-1 year"));
$lastYear = $dateAr['year'] . "-" . $dateAr['mon'] . "-" . $dateAr['mday'];


$calq =
"
select Calories, DayDate
from Day
inner join Nutrition
on Day.DayID = Nutrition.DayID
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);
"; 
$sleepq = 
"
select DayDate, Quality, Duration
	from Day
	inner join Sleep
	on Sleep.DayID = Day.DayID
	where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);
";
$exerciseq = 
"
select Duration, DayDate
from Day
inner join Exercise
on Day.DayID = Exercise.DayID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);
";
$stepsq = 
"
select Steps, DayDate
from Day
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);
";
$moodq = 
"
select OverallMood, DayDate
from Day
inner join Mood
on Day.DayID = Mood.DayID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);
";

$calr = @mysqli_query($dbc, $calq);
$sleepr = @mysqli_query($dbc, $sleepq);
$exerciser = @mysqli_query($dbc, $exerciseq);
$stepr = @mysqli_query($dbc, $stepsq);
$moodr = @mysqli_query($dbc, $moodq);



if((!$calr)or(!$sleepr)or(!$exerciser)or(!$stepr)or(!$moodr))
{
	die('Could not get data: ' . mysqli_error());
}

$cal = array();
$calday = array();
$sleepq = array();
$sleepd = array();
$exert = array();
$exerday = array();
$steps = array();
$mood = array();

while($row = mysqli_fetch_array($calr))
{
	$cal[] = $row["Calories"];
	$calday[] = strtotime($row['DayDate']);
}
if(count($cal) == 0)
{
	$cal[] = 0;
	$calday[] = 0;
}

while($row = mysqli_fetch_array($sleepr))
{
	$temp = dbtophptime($row['Duration']);
	$sleepd[] = getDate($temp)['minutes']/60.0 + getDate($temp)['hours'];

	$temp = dbtophptime($row['Quality']);
	$sleepq[] = getDate($temp)['minutes']/60.0 + getDate($temp)['hours'];
}

while($row = mysqli_fetch_array($exerciser))
{
	$temp = dbtophptime($row['Duration']);
	$ex[] = getDate($temp)['minutes']/60.0 + getDate($temp)['hours'];
	$exdate[] = strtotime($row['DayDate']);
}

while($row = mysqli_fetch_array($stepr))
{
	$steps[] = $row['Steps'];
}

while($row = mysqli_fetch_array($moodr))
{
	$mood = $row['OverallMood'];	
}

$Cal = array();
$Calday = array();
$lastday = $calday[0];
$tempcal = $cal[0];
for($ii = 1; $ii<count($calday); $ii++)
{
	if($calday[$ii] == $lastday)
	{
		$tempcal = $tempcal + $cal[$ii];
	}
	else
	{
		$Cal[] = $tempcal;
		$Calday[] = $lastday;
		$tempcal = $cal[$ii];
		$lastday = $calday[$ii];
	}
}
$Cal[] = $tempcal;
$Calday[] = $lastday;

$exer = array();
$exerdate = array();
$lastday = $exdate[0];
$tempcal = $ex[0];
for($ii = 1; $ii<count($exdate); $ii++)
{
	if($exdate[$ii] == $lastday)
	{
		$tempcal = $tempcal + $ex[$ii];
	}
	else
	{
		$exer[] = $tempcal;
		$exerdate[] = $lastday;
		$tempcal = $ex[$ii];
		$lastday = $exdate[$ii];
	}
}
$exer[] = $tempcal;
$exerdate[] = $lastday;

$avecal = average($Cal);
$aveexercise = average($exer);
$avesleepq = average($sleepq);
$avesleepd = average($sleepd);
$avesteps = average($steps);
$avemood = average($mood);

echo '<h2 align="center">General Stats</h2><br>';
echo '<table align="center" cellspacing="5" cellpadding="8">

<tr><td align="center"><b>Ave. Calories</b></td><td align="center"><b>'.$avecal.'</b></td>
<tr><td align="center"><b>Ave. Exercise Duration (h)</b></td><td align="center"><b>'. $aveexercise .'</b></td>
<tr><td align="center"><b>Ave. Sleep Quality</b></td><td align="center"><b>'. $avesleepq .'</b></td>
<tr><td align="center"><b>Ave. Sleep Duration</b></td><td align="center"><b>'. $avesleepd .'</b></td>
<tr><td align="center"><b>Ave. Steps</b></td><td align="center"><b>'. $avesteps .'</b></td>
<tr><td align="center"><b>Ave. Mood</b></td><td align="center"><b>'. $avemood .'</b></td></table>';

?>
