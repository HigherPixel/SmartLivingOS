<?php
// Get a connection for the database
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
	$sum = 0.0;
	$n = count($array);
	while($ii < $n)
	{
		$sum = $array[$ii] + $sum;
		$ii = $ii + 1;
	}
	return $sum/$n;
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


//Get today's date, last year's date, and last weeks date.
$todayStr = Date("Y-m-d");
$dateAr = getDate(strtotime("-1 week"));
$lastWeek = $dateAr['year'] . "-" . $dateAr['mon'] . "-" . $dateAr['mday'];
$dateAr = getDate(strtotime("-1 year"));
$lastYear = $dateAr['year'] . "-" . $dateAr['mon'] . "-" . $dateAr['mday'];

//Query the database for information.

$weekq = "select DayDate, Quality, Duration, TimeInBed
	from Day
	inner join Sleep
	on Sleep.DayID = Day.DayID
	where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);";

$yearq = "select DayDate, Quality, Duration
	from Day
	inner join Sleep
	on Sleep.DayID = Day.DayID
	where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastYear . "') and (AccountNum = 3);";

$weekResp = @mysqli_query($dbc, $weekq);
$yearResp = @mysqli_query($dbc, $yearq);

if(! $weekResp)
{
	die('Could not get data: ' . mysql_error());
}
if (! $yearResp)
{
	die('Could not get data: ' . mysql_erro());
}

$yquality = array();
$yqualitydate = array();

$yduration = array();
$ydurationdate = array();

$wquality = array();
$wqualitydate = array();

$wduration = array();
$wdruationdate = array();

$timeinbed = array();

while($wrow = mysqli_fetch_array($weekResp))
{
	$wquality[] = $wrow['Quality'];
	$wqualitydate[] = strtotime($wrow['DayDate']);

	$tempdur = dbtophptime($wrow['Duration']);
	$wduration[] = getDate($tempdur)['minutes']/60.0 + getDate($tempdur)['hours']; 
	$wdurationdate[] = strtotime($wrow['DayDate']);

	$temptimeinbed = dbtophptime($wrow['TimeInBed']);
	$timeinbed[] = getDate($temptimeinbed)['minutes']/60.0 + getDate($temptimeinbed)['hours'];
}

while($yrow = mysqli_fetch_array($yearResp))
{
	$yquality[] = $yrow['Quality'];
	$yqualitydate[] = strtotime($yrow['DayDate']);

	$tempdur = dbtophptime($yrow['Duration']);
	$yduration[] = getDate($tempdur)['minutes']/60.0 + getDate($tempdur)['hours'];
	$ydurationdate[] = strtotime($yrow['DayDate']);
}

$y1 = eliminateNullData($yduration, $ydurationdate);
$y2 = eliminateNullData($yquality, $yqualitydate);
$w1 = eliminateNullData($wquality, $wqualitydate);
$w2 = eliminateNullData($wduration, $wdurationdate);

$yquality = $y2[0];
$yqualitydate = $y2[1];

$yduration = $y1[0];
$ydurationdate = $y1[1];

$wquality = $w1[0];
$wqualitydate = $w1[1];

$wduration = $w2[0];
$wdruationdate = $w2[1];


$yquality = dataDiv($yquality, 12);
$yqualitydate = dataDiv($yqualitydate, 12);

$yduration = dataDiv($yduration, 12);
$ydurationdate = dataDiv($ydurationdate, 12);

$wquality = dataDiv($wquality, 7);
$wqualitydate = dataDiv($wqualitydate, 7);

$wduration = dataDiv($wduration, 7);
$wdurationdate = dataDiv($wdurationdate, 7);

//Need to change to php time before doing this
$avetimeinbed = average($timeinbed);
$avetimeasleep = average($wduration);
$timeawakeinbed = $avetimeinbed - $avetimeasleep;
$timeawakeinbed = getDate($timeawakeinbed)["hours"] + getDate($timeawakeinbed)["minutes"]/60.0;

$yqualdate = array();
$ydurdate = array();
$wqualdate = array();
$wdurdate = array();
for($ii = 0; $ii < 12; $ii++)
{
	$yqualdate[] = getDate($yqualitydate[$ii]);
	$ydurdate[] = getDate($ydurationdate[$ii]);
}
for($ii = 0; $ii < 7; $ii++)
{
	$wqualdate[] = getDate($wqualitydate[$ii]);
	$wdurdate[] = getDate($wdurationdate[$ii]);
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

var wquality= new CanvasJS.Chart("chartContainer", {
	colorSet: "uglyColors",
	animationEnabled: true,
	title:{
		text: "Sleep Quality"
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
			{x: new Date(' . $wqualdate[0]["year"] .  ',' . $wqualdate[0]["mon"] . ',' . $wqualdate[0]["mday"] . '), y: ' . $wquality[0] . '},
			{x: new Date(' . $wqualdate[1]["year"] .  ',' . $wqualdate[1]["mon"] . ',' . $wqualdate[1]["mday"] . '), y: ' . $wquality[1] . '},
			{x: new Date(' . $wqualdate[2]["year"] .  ',' . $wqualdate[2]["mon"] . ',' . $wqualdate[2]["mday"] . '), y: ' . $wquality[2] . '},
			{x: new Date(' . $wqualdate[3]["year"] .  ',' . $wqualdate[3]["mon"] . ',' . $wqualdate[3]["mday"] . '), y: ' . $wquality[3] . '},
			{x: new Date(' . $wqualdate[4]["year"] .  ',' . $wqualdate[4]["mon"] . ',' . $wqualdate[4]["mday"] . '), y: ' . $wquality[4] . '},
			{x: new Date(' . $wqualdate[5]["year"] .  ',' . $wqualdate[5]["mon"] . ',' . $wqualdate[5]["mday"] . '), y: ' . $wquality[5] . '},
			{x: new Date(' . $wqualdate[6]["year"] .  ',' . $wqualdate[6]["mon"] . ',' . $wqualdate[6]["mday"] . '), y: ' . $wquality[6] . '}'
			?>
		]
	}]
});
var wduration= new CanvasJS.Chart("wduration", {
	colorSet: "uglyColors",
	animationEnabled: true,
	title:{
		text: "Sleep Quality"
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
			{x: new Date(' . $wdurdate[0]["year"] .  ',' . $wdurdate[0]["mon"] . ',' . $wdurdate[0]["mday"] . '), y: ' . $wduration[0] . '},
			{x: new Date(' . $wdurdate[1]["year"] .  ',' . $wdurdate[1]["mon"] . ',' . $wdurdate[1]["mday"] . '), y: ' . $wduration[1] . '},
			{x: new Date(' . $wdurdate[2]["year"] .  ',' . $wdurdate[2]["mon"] . ',' . $wdurdate[2]["mday"] . '), y: ' . $wduration[2] . '},
			{x: new Date(' . $wdurdate[3]["year"] .  ',' . $wdurdate[3]["mon"] . ',' . $wdurdate[3]["mday"] . '), y: ' . $wduration[3] . '},
			{x: new Date(' . $wdurdate[4]["year"] .  ',' . $wdurdate[4]["mon"] . ',' . $wdurdate[4]["mday"] . '), y: ' . $wduration[4] . '},
			{x: new Date(' . $wdurdate[5]["year"] .  ',' . $wdurdate[5]["mon"] . ',' . $wdurdate[5]["mday"] . '), y: ' . $wduration[5] . '},
			{x: new Date(' . $wdurdate[6]["year"] .  ',' . $wdurdate[6]["mon"] . ',' . $wdurdate[6]["mday"] . '), y: ' . $wduration[6] . '}'
			?>
		]
	}]
});

var yquality= new CanvasJS.Chart("yquality", {
	colorSet: "uglyColors",
	animationEnabled: true,
	title:{
		text: "Sleep Quality"
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
			{x: new Date(' . $yqualdate[0]["year"] .  ',' . $yqualdate[0]["mon"] . ',' . $yqualdate[0]["mday"] . '), y: ' . $yquality[0] . '},
			{x: new Date(' . $yqualdate[1]["year"] .  ',' . $yqualdate[1]["mon"] . ',' . $yqualdate[1]["mday"] . '), y: ' . $yquality[1] . '},
			{x: new Date(' . $yqualdate[2]["year"] .  ',' . $yqualdate[2]["mon"] . ',' . $yqualdate[2]["mday"] . '), y: ' . $yquality[2] . '},
			{x: new Date(' . $yqualdate[3]["year"] .  ',' . $yqualdate[3]["mon"] . ',' . $yqualdate[3]["mday"] . '), y: ' . $yquality[3] . '},
			{x: new Date(' . $yqualdate[4]["year"] .  ',' . $yqualdate[4]["mon"] . ',' . $yqualdate[4]["mday"] . '), y: ' . $yquality[4] . '},
			{x: new Date(' . $yqualdate[5]["year"] .  ',' . $yqualdate[5]["mon"] . ',' . $yqualdate[5]["mday"] . '), y: ' . $yquality[5] . '},
			{x: new Date(' . $yqualdate[6]["year"] .  ',' . $yqualdate[6]["mon"] . ',' . $yqualdate[6]["mday"] . '), y: ' . $yquality[6] . '},
			{x: new Date(' . $yqualdate[7]["year"] .  ',' . $yqualdate[7]["mon"] . ',' . $yqualdate[7]["mday"] . '), y: ' . $yquality[7] . '},
			{x: new Date(' . $yqualdate[8]["year"] .  ',' . $yqualdate[8]["mon"] . ',' . $yqualdate[8]["mday"] . '), y: ' . $yquality[8] . '},
			{x: new Date(' . $yqualdate[9]["year"] .  ',' . $yqualdate[9]["mon"] . ',' . $yqualdate[9]["mday"] . '), y: ' . $yquality[9] . '},
			{x: new Date(' . $yqualdate[10]["year"] .  ',' . $yqualdate[10]["mon"] . ',' . $yqualdate[10]["mday"] . '), y: ' . $yquality[10] . '},
			{x: new Date(' . $yqualdate[11]["year"] .  ',' . $yqualdate[11]["mon"] . ',' . $yqualdate[11]["mday"] . '), y: ' . $yquality[11] . '}'
			?>
		]
	}]
});

var yduration= new CanvasJS.Chart("yduration", {
	colorSet: "uglyColors",
	animationEnabled: true,
	title:{
		text: "Sleep Duration"
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
			{x: new Date(' . $ydurdate[0]["year"] .  ',' . $ydurdate[0]["mon"] . ',' . $ydurdate[0]["mday"] . '), y: ' . $yduration[0] . '},
			{x: new Date(' . $ydurdate[1]["year"] .  ',' . $ydurdate[1]["mon"] . ',' . $ydurdate[1]["mday"] . '), y: ' . $yduration[1] . '},
			{x: new Date(' . $ydurdate[2]["year"] .  ',' . $ydurdate[2]["mon"] . ',' . $ydurdate[2]["mday"] . '), y: ' . $yduration[2] . '},
			{x: new Date(' . $ydurdate[3]["year"] .  ',' . $ydurdate[3]["mon"] . ',' . $ydurdate[3]["mday"] . '), y: ' . $yduration[3] . '},
			{x: new Date(' . $ydurdate[4]["year"] .  ',' . $ydurdate[4]["mon"] . ',' . $ydurdate[4]["mday"] . '), y: ' . $yduration[4] . '},
			{x: new Date(' . $ydurdate[5]["year"] .  ',' . $ydurdate[5]["mon"] . ',' . $ydurdate[5]["mday"] . '), y: ' . $yduration[5] . '},
			{x: new Date(' . $ydurdate[6]["year"] .  ',' . $ydurdate[6]["mon"] . ',' . $ydurdate[6]["mday"] . '), y: ' . $yduration[6] . '},
			{x: new Date(' . $ydurdate[7]["year"] .  ',' . $ydurdate[7]["mon"] . ',' . $ydurdate[7]["mday"] . '), y: ' . $yduration[7] . '},
			{x: new Date(' . $ydurdate[8]["year"] .  ',' . $ydurdate[8]["mon"] . ',' . $ydurdate[8]["mday"] . '), y: ' . $yduration[8] . '},
			{x: new Date(' . $ydurdate[9]["year"] .  ',' . $ydurdate[9]["mon"] . ',' . $ydurdate[9]["mday"] . '), y: ' . $yduration[9] . '},
			{x: new Date(' . $ydurdate[10]["year"] .  ',' . $ydurdate[10]["mon"] . ',' . $ydurdate[10]["mday"] . '), y: ' . $yduration[10] . '},
			{x: new Date(' . $ydurdate[11]["year"] .  ',' . $ydurdate[11]["mon"] . ',' . $ydurdate[11]["mday"] . '), y: ' . $yduration[11] . '}'
			?>
		]
	}]
});

wquality.render();
wduration.render();
yquality.render();
yduration.render();

}
</script>
</head>
<body>
<h2 class="center">Your Sleep This Week</h2><br>
<p class="center"> On average this week you spent <?php echo $timeawakeinbed?> hours awake in bed per day. </p><br><br>
<div class="center" id="chartContainer" style="height: 300px; width: 60%;"></div><br><br>
<div class="center" id="wduration" style="height: 300px; width: 60%;"></div><br>
<h2 class="center">Your Sleep This Year</h2><br>
<div class="center" id="yquality" style="height: 300px; width: 60%;"></div><br><br>
<div class="center" id="yduration" style="height: 300px; width: 60%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>



