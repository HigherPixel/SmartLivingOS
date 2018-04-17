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

function dataDiv($data, $n)
{
	$arrayLen = count($data);
	$subArLen = $arrayLen/$n;
	$retAr = array();
	$subAr = array();
	if($n > $arrayLen)
	{
		$lastDat = 0;
		$extra = $n - $arrayLen;
		foreach($data as $dat)
		{
			$retAr[] = $dat;
			$lastDat = $dat;
		}
		while($extra > 0)
		{
			$retAr[] = $lastDat;
			$extra--;
		}
	}
	else
	{
		$counter = 0;
		for($ii = 0; $ii < $n; $ii++) //This loop repleats n times
		{
			if($arrayLen - $counter >= $subArLen)//If there are more than subArLen empty spots in the return array
			{
				for($temp = $subArLen; $temp > 0; $temp--)//This repeats subArLen times
				{
					$subAr[] = $data[$counter];
					$counter++; 
				}
				$retAr[] = average($subAr);
				$subAr = array();
			}
			else //If there are fewer than subArLen spots left to be filled in the retAr
			{
				if($arrayLen-$counter > 0)
				{
					while($arrayLen-$counter > 0) //
					{
						$subAr[] = $data[$counter];
						$counter++;
					}
					$retAr[] = average($subAr);
				}
				else
				{
					$retAr[] = $data[$arrayLen - 1];
				}
				
			}
		}
	}
	return $retAr;
}

$todayStr = Date("Y-m-d");
$dateAr = getDate(strtotime("-1 week"));
$lastWeek = $dateAr['year'] . "-" . $dateAr['mon'] . "-" . $dateAr['mday'];
$dateAr = getDate(strtotime("-1 year"));
$lastYear = $dateAr['year'] . "-" . $dateAr['mon'] . "-" . $dateAr['mday'];

$weekq1 = 
"
select Duration, DayDate
from Day
inner join Exercise
on Day.DayID = Exercise.DayID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);
";


$weekq2=
"
select Steps, DayDate
from Day
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);
";

$yearq = 
"
select Duration, DayDate
from Day
inner join Exercise
on Day.DayID = Exercise.DayID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastYear . "') and (AccountNum = 3);
";

$week1Resp = @mysqli_query($dbc, $weekq1);
$week2Resp = @mysqli_query($dbc, $weekq2);
$yearResp = @mysqli_query($dbc, $yearq);

if(! $week1Resp)
{
	die('Could not get data: ' . mysql_error());
}
if(! $week2Resp)
{
	die('Could not get data: ' . mysql_error());
}
if (! $yearResp)
{
	die('Could not get data: ' . mysql_erro());
}

$exw = array();
$exwdate = array();

$exy = array();
$exydate = array();

$steps = array();
$stepsdate = array();

while(($wrow = mysqli_fetch_array($week1Resp)) and ($wrow2 = mysqli_fetch_array($week2Resp)))
{
	$tempdur = dbtophptime($wrow['Duration']);
	$exw[] = getDate($tempdur)['minutes']/60.0 + getDate($tempdur)['hours'];
	$exwdate[] = strtotime($wrow['DayDate']);

	$steps[] = $wrow2['Steps'];
	$stepsdate[] = strtotime($wrow2['DayDate']);
}

while($yrow = mysqli_fetch_array($yearResp))
{
	$tempdur = dbtophptime($yrow['Duration']);
	$exy[] = getDate($tempdur)['minutes']/60.0 + getDate($tempdur)['hours'];
	$exydate[] = strtotime($yrow['DayDate']);
}

$n1 = eliminateNullData($exw, $exwdate);
$n2 = eliminateNullData($steps, $stepsdate);
$n3 = eliminateNullData($exy, $exydate);

$exw = $n1[0];
$exwdate = $n1[1];

$exy = $n3[0];
$exydate = $n3[1];

$steps = $n2[0];
$stepsdate = $n2[1];

array_multisort($exwdate,SORT_ASC, SORT_NUMERIC, $exw);
array_multisort($stepsdate,SORT_ASC, SORT_NUMERIC, $steps);
array_multisort($exydate,SORT_ASC, SORT_NUMERIC, $exy);

$exery = array();
$exerydate = array();
$lastday = $exydate[0];
$tempcal = $exy[0];
for($ii = 1; $ii<count($exydate); $ii++)
{
	if($exydate[$ii] == $lastday)
	{
		$tempcal = $tempcal + $exy[$ii];
	}
	else
	{
		$exery[] = $tempcal;
		$exerydate[] = $lastday;
		$tempcal = $exy[$ii];
		$lastday = $exydate[$ii];
	}
}
$exery[] = $tempcal;
$exerydate[] = $lastday;

$exerw = array();
$exerwdate = array();
$lastday = $exwdate[0];
$tempcal = $exw[0];
for($ii = 1; $ii<count($exwdate); $ii++)
{
	if($exwdate[$ii] == $lastday)
	{
		$tempcal = $tempcal + $exw[$ii];
	}
	else
	{
		$exerw[] = $tempcal;
		$exerwdate[] = $lastday;
		$tempcal = $exw[$ii];
		$lastday = $exwdate[$ii];
	}
}
$exerw[] = $tempcal;
$exerwdate[] = $lastday;


$exerw = dataDiv($exerw, 7);
$exerwdate = dataDiv($exerwdate, 7);

$steps = dataDiv($steps, 7);
$stepsdate = dataDiv($stepsdate, 7);

$exery = dataDiv($exery, 12);
$exerydate = dataDiv($exerydate, 12);


$aveSteps = average($steps);
$aveExerTime = average($exerw);

$exydate = array();
$exwdate = array();
$stepdate = array();
for($ii = 0; $ii < 12; $ii++)
{
	$exydate[] = getDate($exerydate[$ii]);
}
for($ii = 0; $ii < 7; $ii++)
{
	$stepdate[] = getDate($stepsdate[$ii]);
	$exwdate[] = getDate($exerwdate[$ii]);
}
?>

<!DOCTYPE HTML>
<html>
<head>  
<style>
.center
{
	margin: auto;
	width: 20%;
	padding: 20px;
}
</style>
<script>
window.onload = function () {
CanvasJS.addColorSet("uglyColors",[
	"#483d8b"
	]);

var weekEx= new CanvasJS.Chart("weekEx", {
	colorSet: "uglyColors",
	animationEnabled: true,
	title:{
		text: "Exercise Time This Week"
	},
	axisX: {
		valueFormatString: "DD MMM"
	},
	axisY:{
		includeZero: false
	},
	data: [{
		type: "stepLine",
		connectNullData: true,
		dataPoints: [
			<?php
			echo '
			{x: new Date(' . $exwdate[0]["year"] .  ',' . $exwdate[0]["mon"] . ',' . $exwdate[0]["mday"] . '), y: ' . $exerw[0] . '},
			{x: new Date(' . $exwdate[1]["year"] .  ',' . $exwdate[1]["mon"] . ',' . $exwdate[1]["mday"] . '), y: ' . $exerw[1] . '},
			{x: new Date(' . $exwdate[2]["year"] .  ',' . $exwdate[2]["mon"] . ',' . $exwdate[2]["mday"] . '), y: ' . $exerw[2] . '},
			{x: new Date(' . $exwdate[3]["year"] .  ',' . $exwdate[3]["mon"] . ',' . $exwdate[3]["mday"] . '), y: ' . $exerw[3] . '},
			{x: new Date(' . $exwdate[4]["year"] .  ',' . $exwdate[4]["mon"] . ',' . $exwdate[4]["mday"] . '), y: ' . $exerw[4] . '},
			{x: new Date(' . $exwdate[5]["year"] .  ',' . $exwdate[5]["mon"] . ',' . $exwdate[5]["mday"] . '), y: ' . $exerw[5] . '},
			{x: new Date(' . $exwdate[6]["year"] .  ',' . $exwdate[6]["mon"] . ',' . $exwdate[6]["mday"] . '), y: ' . $exerw[6] . '}
			'
			?>
		]
	}]
});

var weekSteps= new CanvasJS.Chart("weekSteps", {
	colorSet: "uglyColors",
	animationEnabled: true,
	title:{
		text: "Steps This Week"
	},
	axisX: {
		valueFormatString: "DD MMM"
	},
	axisY:{
		includeZero: false
	},
	data: [{
		type: "stepLine",
		connectNullData: true,
		dataPoints: [
			<?php
			echo '
			{x: new Date(' . $stepdate[0]["year"] .  ',' . $stepdate[0]["mon"] . ',' . $stepdate[0]["mday"] . '), y: ' . $steps[0] . '},
			{x: new Date(' . $stepdate[1]["year"] .  ',' . $stepdate[1]["mon"] . ',' . $stepdate[1]["mday"] . '), y: ' . $steps[1] . '},
			{x: new Date(' . $stepdate[2]["year"] .  ',' . $stepdate[2]["mon"] . ',' . $stepdate[2]["mday"] . '), y: ' . $steps[2] . '},
			{x: new Date(' . $stepdate[3]["year"] .  ',' . $stepdate[3]["mon"] . ',' . $stepdate[3]["mday"] . '), y: ' . $steps[3] . '},
			{x: new Date(' . $stepdate[4]["year"] .  ',' . $stepdate[4]["mon"] . ',' . $stepdate[4]["mday"] . '), y: ' . $steps[4] . '},
			{x: new Date(' . $stepdate[5]["year"] .  ',' . $stepdate[5]["mon"] . ',' . $stepdate[5]["mday"] . '), y: ' . $steps[5] . '},
			{x: new Date(' . $stepdate[6]["year"] .  ',' . $stepdate[6]["mon"] . ',' . $stepdate[6]["mday"] . '), y: ' . $steps[6] . '}
			'
			?>
		]
	}]
});

var yearEx= new CanvasJS.Chart("yearEx", {
	colorSet: "uglyColors",
	animationEnabled: true,
	title:{
		text: "Yearly Exercise Time"
	},
	axisX: {
		valueFormatString: "DD MMM"
	},
	axisY:{
		includeZero: false
	},
	data: [{
		type: "spline",
		connectNullData: true,
		dataPoints: [
			<?php
			echo '
			{x: new Date(' . $exydate[0]["year"] .  ',' . $exydate[0]["mon"] . ',' . $exydate[0]["mday"] . '), y: ' . $exery[0] . '},
			{x: new Date(' . $exydate[1]["year"] .  ',' . $exydate[1]["mon"] . ',' . $exydate[1]["mday"] . '), y: ' . $exery[1] . '},
			{x: new Date(' . $exydate[2]["year"] .  ',' . $exydate[2]["mon"] . ',' . $exydate[2]["mday"] . '), y: ' . $exery[2] . '},
			{x: new Date(' . $exydate[3]["year"] .  ',' . $exydate[3]["mon"] . ',' . $exydate[3]["mday"] . '), y: ' . $exery[3] . '},
			{x: new Date(' . $exydate[4]["year"] .  ',' . $exydate[4]["mon"] . ',' . $exydate[4]["mday"] . '), y: ' . $exery[4] . '},
			{x: new Date(' . $exydate[5]["year"] .  ',' . $exydate[5]["mon"] . ',' . $exydate[5]["mday"] . '), y: ' . $exery[5] . '},
			{x: new Date(' . $exydate[6]["year"] .  ',' . $exydate[6]["mon"] . ',' . $exydate[6]["mday"] . '), y: ' . $exery[6] . '},
			{x: new Date(' . $exydate[7]["year"] .  ',' . $exydate[7]["mon"] . ',' . $exydate[7]["mday"] . '), y: ' . $exery[7] . '},
			{x: new Date(' . $exydate[8]["year"] .  ',' . $exydate[8]["mon"] . ',' . $exydate[8]["mday"] . '), y: ' . $exery[8] . '},
			{x: new Date(' . $exydate[9]["year"] .  ',' . $exydate[9]["mon"] . ',' . $exydate[9]["mday"] . '), y: ' . $exery[9] . '},
			{x: new Date(' . $exydate[10]["year"] .  ',' . $exydate[10]["mon"] . ',' . $exydate[10]["mday"] . '), y: ' . $exery[10] . '},
			{x: new Date(' . $exydate[11]["year"] .  ',' . $exydate[11]["mon"] . ',' . $exydate[11]["mday"] . '), y: ' . $exery[11] . '},
			'
			?>
		]
	}]
});


weekEx.render();
weekSteps.render();
yearEx.render();

}
</script>
</head>
<body>
<h2 class="center">Your Exercise This Week</h2><br>
<p class="center"> This week you took an average of <?php echo $aveSteps?> steps per day, and you spent an average of <?php echo $aveExerTime?> hours exercising.  </p><br><br>
<div class="center" id="weekEx" style="height: 300px; width: 60%;"></div><br><br>
<div class="center" id="weekSteps" style="height: 300px; width: 60%;"></div><br>
<h2 class="center">Your Exercise This Year</h2><br>
<div class="center" id="yearEx" style="height: 300px; width: 60%;"></div><br><br>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
