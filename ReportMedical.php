<?php
// Get a connection for the database
require_once('../mysqli_connect.php');
//******************Encounter History************************
// create a query fro the databse
$query = 'select EncounterDate, Facility, Specialty, Clinitian, Reason, VisitType from EncounterHistory where AccountNum=3;';

$response = @mysqli_query($dbc, $query);

if(! $response){
	die('Could not get data: ' . mysql_error());
}

echo '<h2 align="center">Encounter History</h2>';

echo '<table align="center"
cellspacing="5" cellpadding="8">

<tr><td align="center"><b>Date</b></td>
<td align="center"><b>Facility</b></td>
<td align="center"><b>Specialty</b></td>
<td align="center"><b>Clinitian</b></td>
<td align="center"><b>Reason</b></td>
<td align="center"><b>VisitType</b></td>';

while($row = mysqli_fetch_array($response)) {
	echo '<tr><td align="center">'.
	$row['EncounterDate'] . '</td><td align="center">'.
	$row['Facility'] . '</td><td align="center">'.
	$row['Specialty'] . '</td><td align="center">'.
	$row['Clinitian'] . '</td><td align="center">'.
	$row['Reason'] . '</td><td align="center">'.
	$row['VisitType'] . '</td><td align="center">';

echo '</tr>';
}
echo '</table>';

//****************Diagnosis********************************
// create a query fro the databse
$query = 'select DiagnosisDate, DiagnosisType, DiagnosisStatus from Diagnosis where AccountNum=3;';

$response = @mysqli_query($dbc, $query);

if(! $response){
	die('Could not get data: ' . mysql_error());
}

echo '<h2 align="center">Encounter History</h2>';

echo '<table align="center"
cellspacing="5" cellpadding="8">

<tr><td align="center"><b>Date</b></td>
<td align="center"><b>Diagnosis</b></td>
<td align="center"><b>Status</b></td>';

while($row = mysqli_fetch_array($response)) {
	echo '<tr><td align="center">'.
	$row['DiagnosisDate'] . '</td><td align="center">'.
	$row['DiagnosisType'] . '</td><td align="center">'.
	$row['DiagnosisStatus'] . '</td><td align="center">';

echo '</tr>';
}
echo '</table>';

//**********************Medications****************************
// create a query fro the databse
$query = 'select DatePerscribed, MedicationName, LastFilled, Perscription from Medications where AccountNum=3;';

$response = @mysqli_query($dbc, $query);

if(! $response){
	die('Could not get data: ' . mysql_error());
}

echo '<h2 align="center">Medications</h2>';

echo '<table align="center"
cellspacing="5" cellpadding="8">

<tr><td align="center"><b>Date</b></td>
<td align="center"><b>Medication</b></td>
<td align="center"><b>Last Filled</b></td>
<td align="center"><b>Perscription</b></td>';

while($row = mysqli_fetch_array($response)) {
	echo '<tr><td align="center">'.
	$row['DatePerscribed'] . '</td><td align="center">'.
	$row['MedicationName'] . '</td><td align="center">'.
	$row['LastFilled'] . '</td><td align="center">'.
	$row['Perscription'] . '</td><td align="center">';

echo '</tr>';
}
echo '</table>';

//*********************Immunizations**********************************
// create a query fro the databse
$query = 'select ImmunizationDate, ImmunizationType, NumberRecieved from Immunizations where AccountNum=3;';

$response = @mysqli_query($dbc, $query);

if(! $response){
	die('Could not get data: ' . mysql_error());
}

echo '<h2 align="center">Immunizations</h2>';

echo '<table align="center"
cellspacing="5" cellpadding="8">

<tr><td align="center"><b>Date</b></td>
<td align="center"><b>Type</b></td>
<td align="center"><b>Number Recieved</b></td>';

while($row = mysqli_fetch_array($response)) {
	echo '<tr><td align="center">'.
	$row['ImmunizationDate'] . '</td><td align="center">'.
	$row['ImmunizationType'] . '</td><td align="center">'.
	$row['NumberRecieved'] . '</td><td align="center">';

echo '</tr>';
}
echo '</table>';
//******************Allergies****************************************
// create a query fro the databse
$query = 'select AllergyDate, AllergyType,AllergyStatus from Allergies where AccountNum=3;';

$response = @mysqli_query($dbc, $query);

if(! $response){
	die('Could not get data: ' . mysql_error());
}

echo '<h2 align="center">Allergies</h2>';

echo '<table align="center"
cellspacing="5" cellpadding="8">

<tr><td align="center"><b>Date</b></td>
<td align="center"><b>Allergy</b></td>
<td align="center"><b>Status</b></td>';

while($row = mysqli_fetch_array($response)) {
	echo '<tr><td align="center">'.
	$row['AllergyDate'] . '</td><td align="center">'.
	$row['AllergyType'] . '</td><td align="center">'.
	$row['AllergyStatus'] . '</td><td align="center">';

echo '</tr>';
}
echo '</table>';

//*********************Blood Sugar*****************
$query = 
'
select BSMeasurementDate, BloodSugarMeasure
from BloodSugar
where BSMeasurementDate=(select Max(BSMeasurementDate) from BloodSugar) and AccountNum=3
';

$response = @mysqli_query($dbc, $query);

if(! $response)
{
	die('Could not get data: ' . mysqli_error());
}



echo '<h2 align="center">Blood Sugar</h2>';

echo '<table align="center"
cellspacing="5" cellpadding="8">

<tr><td align="center"><b>Date</b></td>
<td align="center"><b>Measure</b></td>';

$row = mysqli_fetch_array($response);
$date = strtotime($row['BSMeasurementDate']);
$date = getDate($date)['year'] . '-' . getDate($date)['mon'] . '-' . getDate($date)['mday'];
echo '<tr><td align="center">' . 
$date . '</td><td align="center">'.
$row['BloodSugarMeasure'] . '</td><td align="center">';
echo '</tr>';
echo '</table>';
//*********************Blood Pressure**************
$query =
'
select BPMeasurementDate, Systolic, Diastolic
from BloodPressure
where BPMeasurementDate=(select Max(BPMeasurementDate) from BloodPressure) and AccountNum=3;
';

$response = @mysqli_query($dbc, $query);

if(! $response)
{
	die('Could not get data: ' . mysqli_error());
}

echo '<h2 align="center">Blood Pressure</h2>';

echo '<table align="center"
cellspacing="5" cellpadding="8">

<tr><td align="center"><b>Date</b></td>
<td align="center"><b>Systolic</b></td>
<td align="center"><b>Diastolic</b></td>';

$row = mysqli_fetch_array($response);
$date = strtotime($row['BPMeasurementDate']);
$date = getDate($date)['year'] . '-' . getDate($date)['mon'] . '-' . getDate($date)['mday'];
echo '<tr><td align="center">' . 
$date . '</td><td align="center">' .
$row['Systolic'] . '</td><td align="center">' .
$row['Diastolic'] . '</td><td align="center">';
echo '</tr>';
echo '</table>';
//*********************Choleterol******************
$query =
'
select CholMeasurementDate, CholMeasure 
from Cholesterol 
where CholMeasurementDate=(select Max(cholMeasurementDate) from Cholesterol) and AccountNum=3;
';

$response = @mysqli_query($dbc, $query);

if(! $response)
{
	die('Could not get data: ' . mysqli_error());
}

echo '<h2 align="center">Cholesterol</h2>';

echo '<table align="center"
cellspacing="5" cellpadding="8">

<tr><td align="center"><b>Date</b></td>
<td align="center"><b>Measure</b></td>';

$row = mysqli_fetch_array($response);
$date = strtotime($row['CholMeasurementDate']);
$date = getDate($date)['year'] . '-' . getDate($date)['mon'] . '-' . getDate($date)['mday'];
echo '<tr><td align="center">' . 
$date . '</td><td align="center">'.
$row['CholMeasure'] . '</td><td align="center">';
echo '</tr>';
echo '</table>';

mysqli_close($dbc);
?>
