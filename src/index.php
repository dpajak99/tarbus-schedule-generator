<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
define('__ROOT__', dirname(dirname(__FILE__)));

require_once '../vendor/autoload.php';
require_once __ROOT__ .'/src/database/db_connection.php';
require_once __ROOT__ .'/src/database/db_queries.php';

require_once __ROOT__ .'/src/model/ModelDayDepartures.php';
require_once __ROOT__ .'/src/model/ScheduleDeparture.php';

require_once __ROOT__ .'/src/model/dbModels/RouteConnection.php';
require_once __ROOT__ .'/src/model/dbModels/BusStop.php';
require_once __ROOT__ .'/src/model/dbModels/Departure.php';
require_once __ROOT__ .'/src/model/dbModels/Route.php';
require_once __ROOT__ .'/src/model/dbModels/Destination.php';

require_once __ROOT__ .'/src/view/ScheduleView.php';
require_once __ROOT__ .'/src/view/AllDeparturesView.php';
require_once __ROOT__ .'/src/view/SingleDepartureView.php';
require_once __ROOT__ .'/src/view/AllDestinationsView.php';
require_once __ROOT__ .'/src/view/SingleDestinationView.php';



$mpdf = new \Mpdf\Mpdf();

$header = "<html><head><link href='style/style.css' rel='stylesheet'</head><body>";
try {
  $mpdf->WriteHTML($header);
} catch (\Mpdf\MpdfException $e) {
}

$allRoutes = getRoutes();
foreach ($allRoutes as &$route) {
  foreach ($route->connections as &$routeConnection) {
    $busStop = $routeConnection->busStop;
    try {
      $mpdf->WriteHTML(buildAllScheduleView($mpdf, $allRoutes, $busStop));
    } catch (\Mpdf\MpdfException $e) {
    }
    $mpdf->AddPage();
  }
}

try {
  $mpdf->WriteHTML("</body></html>");
} catch (\Mpdf\MpdfException $e) {
}
$mpdf->Output();


function writeBusStops($mpdf, $allRoutes)
{
  $result = "";
  foreach ($allRoutes as &$route) {
    foreach ($route->connections as &$routeConnection) {
      $busStop = $routeConnection->busStop;
      if ($routeConnection->isOptional == 0) {
        $result = $result . $busStop->name . "<br />";
      }
    }
  }
  return $result;
}

function buildAllScheduleView($mpdf, $allRoutes, $busStop)
{
  $BUS_ROUTE_ID = 1;
  
  $allBusStops = writeBusStops($mpdf, $allRoutes);
  
  $workDaysDepartures = ModelDayDepartures::getModelDayDepartures("1,3", $busStop->id, $BUS_ROUTE_ID);
  $freeDaysDepartures = ModelDayDepartures::getModelDayDepartures("2", $busStop->id, $BUS_ROUTE_ID);
  $holyDaysDepartures = ModelDayDepartures::getModelDayDepartures("2", $busStop->id, $BUS_ROUTE_ID);
  
  $scheduleDeparture = new ScheduleDeparture();
  $scheduleDeparture->workDaysItemsView = AllDeparturesView::buildAllDeparturesView($workDaysDepartures->departuresArray);
  $scheduleDeparture->freeDaysItemsView = AllDeparturesView::buildAllDeparturesView($freeDaysDepartures->departuresArray);
  $scheduleDeparture->holyDaysItemsView = AllDeparturesView::buildAllDeparturesView($holyDaysDepartures->departuresArray);
  
  $allDestinations = [];
  $allDestinations = joinArrays($allDestinations, $workDaysDepartures->destinationsArray);
  $allDestinations = joinArrays($allDestinations, $freeDaysDepartures->destinationsArray);
  $allDestinations = joinArrays($allDestinations, $holyDaysDepartures->destinationsArray);
  
  $allSortedDestinationsId = array_unique($allDestinations);
  $allSortedDestinationNames = getDestinations($allSortedDestinationsId, $BUS_ROUTE_ID);
  
  $scheduleView = new ScheduleView();
  $scheduleView->busStopObject = $busStop;
  $scheduleView->allBusStopsItemView = $allBusStops;
  $scheduleView->scheduleDepartures = $scheduleDeparture;
  $scheduleView->destinationBoxView = AllDestinationsView::buildAllDestinationsView($allSortedDestinationNames);
  
  return $scheduleView->getScheduleView();
}

function joinArrays( $array1, $array2 ) {
  foreach ($array2 as &$item) {
    array_push($array1, $item);
  }
  return $array1;
}
