<?php
require_once('../mysqli_connect.php');


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

$yearq = "
select Calories, DayDate
from Day
inner join Nutrition
on Day.DayID = Nutrition.DayID
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastYear . "') and (AccountNum = 3);";

$weekq = "
select Calories, Sodium, Sugars, Protien, TransFat, SaturatedFat, Cholesterol, DiataryFiber
from Day
inner join Nutrition
on Day.DayID = Nutrition.DayID
inner join FoodOrDrink
on Nutrition.FoodID = FoodOrDrink.FoodID
where (DayDate <='" . $todayStr . "') and (DayDate >='" . $lastWeek . "') and (AccountNum = 3);	
";

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

$cal = array();
$sod = array();
$sug = array();
$prot = array();
$trans = array();
$sat = array();
$chol = array();
$fib = array();
$ycal = array();
$yday = array();

while($wrow = mysqli_fetch_array($weekResp))
{
	$cal = $wrow['Calories'];
	$sod = $wrow['Sodium'];
	$sug = $wrow['Sugars'];
	$prot = $wrow['Protien'];
	$trans = $wrow['TransFat'];
	$sat = $wrow['SaturatedFat'];
	$chol = $wrow['Cholesterol'];
	$fib = $wrow['DiataryFiber'];
}

while($yrow = mysqli_fetch_array($yearResp))
{
	$yCal[] = $yrow['Calories'];
	$yDay[] = strtotime($yrow['DayDate']);
}

$cal = eliminateNullData($yCal, $yDay);
$yCal = $cal[0];
$yDay = $cal[1];

array_multisort($yDay, SORT_ASC, SORT_NUMERIC, $yCal);

$ycal = array();
$yday = array();
$lastday = $yDay[0];
$tempcal = $yCal[0];
for($ii = 1; $ii<count($yDay); $ii++)
{
	if($yDay[$ii] == $lastday)
	{
		$tempcal = $tempcal + $yCal[$ii];
	}
	else
	{
		$ycal[] = $tempcal;
		$yday[] = $lastday;
		$tempcal = $yCal[$ii];
		$lastday = $yDay[$ii];
	}
}
$ycal[] = $tempcal;
$yday[] = $lastday;


$ycal = dataDiv($ycal, 12);
$yday = dataDiv($yday, 12);

$cal = average($cal);
$sod = average($sod);
$sug = average($sug);
$prot = average($prot);
$trans = average($trans);
$sat = average($sat);
$chol = average($chol);
$fib = average($fib);

$yearday = array();

for($ii = 0; $ii < 12; $ii++)
{
	$yearday[] = getDate($yday[$ii]);
}

echo '<h2 align="center">Your Average Daily Intake This Week:';

echo '<table align="center"
cellspacing="5" cellpadding="8">

<tr><td align="center"><b>Calories</b></td>
<td align="center"><b>Sodium</b></td>
<td align="center"><b>Sugar</b></td>
<td align="center"><b>Protien</b></td>
<td align="center"><b>Trans Fat</b></td>
<td align="center"><b>Saturated Fat</b></td>
<td align="center"><b>Choleterol</b></td>
<td align="center"><b>Diatary Fiber</b></td>
';

echo '<tr><td align="center">' .
$cal . '</td><td align="center">' .
$sod . '</td><td align="center">' .
$sug . '</td><td align="center">' .
$prot . '</td><td align="center">' .
$trans . '</td><td align="center">' . 
$sat . '</td><td align="center">' .
$chol . '</td><td align="center">' .
$fib . '</td><td align="center">';
echo '</tr>';
echo '</table>';
echo '<br>';
echo '<h2 align="center">Your Average Daily Calory Intake This Year:';

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

var yCal= new CanvasJS.Chart("yCal", {
	colorSet: "uglyColors",
	animationEnabled: true,
	title:{
		text: "Ave. Daily Calories"
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
			{x: new Date(' . $yearday[0]["year"] .  ',' . $yearday[0]["mon"] . ',' . $yearday[0]["mday"] . '), y: ' . $ycal[0] . '},
			{x: new Date(' . $yearday[1]["year"] .  ',' . $yearday[1]["mon"] . ',' . $yearday[1]["mday"] . '), y: ' . $ycal[1] . '},
			{x: new Date(' . $yearday[2]["year"] .  ',' . $yearday[2]["mon"] . ',' . $yearday[2]["mday"] . '), y: ' . $ycal[2] . '},
			{x: new Date(' . $yearday[3]["year"] .  ',' . $yearday[3]["mon"] . ',' . $yearday[3]["mday"] . '), y: ' . $ycal[3] . '},
			{x: new Date(' . $yearday[4]["year"] .  ',' . $yearday[4]["mon"] . ',' . $yearday[4]["mday"] . '), y: ' . $ycal[4] . '},
			{x: new Date(' . $yearday[5]["year"] .  ',' . $yearday[5]["mon"] . ',' . $yearday[5]["mday"] . '), y: ' . $ycal[5] . '},
			{x: new Date(' . $yearday[6]["year"] .  ',' . $yearday[6]["mon"] . ',' . $yearday[6]["mday"] . '), y: ' . $ycal[6] . '},
			{x: new Date(' . $yearday[7]["year"] .  ',' . $yearday[7]["mon"] . ',' . $yearday[7]["mday"] . '), y: ' . $ycal[7] . '},
			{x: new Date(' . $yearday[8]["year"] .  ',' . $yearday[8]["mon"] . ',' . $yearday[8]["mday"] . '), y: ' . $ycal[8] . '},
			{x: new Date(' . $yearday[9]["year"] .  ',' . $yearday[9]["mon"] . ',' . $yearday[9]["mday"] . '), y: ' . $ycal[9] . '},
			{x: new Date(' . $yearday[10]["year"] .  ',' . $yearday[10]["mon"] . ',' . $yearday[10]["mday"] . '), y: ' . $ycal[10] . '},
			{x: new Date(' . $yearday[11]["year"] .  ',' . $yearday[11]["mon"] . ',' . $yearday[11]["mday"] . '), y: ' . $ycal[11] . '}'
			?>
		]
	}]
});

yCal.render();

}
</script>
</head>
<body>
<div class="center" id="yCal" style="height: 300px; width: 60%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>

