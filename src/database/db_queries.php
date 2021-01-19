<?php
function getRouteConnections($id)
{
  $result = doSql("SELECT * FROM RouteConnections WHERE rc_route_id = $id");
  
  $allRouteConnections = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $routeConnection = new RouteConnection();
      $routeConnection->id = $row["rc_id"];
      $routeConnection->routeId = $row["rc_route_id"];
      $routeConnection->isOptional = $row["rc_is_optional"];
      $routeConnection->lp = $row["rc_lp"];
      $routeConnection->busStopId = $row["rc_bus_stop_id"];
      $routeConnection->busStop = getBusStop($routeConnection->busStopId);
      array_push($allRouteConnections, $routeConnection);
    }
  } else {
    echo "Zero rows";
  }
  
  return $allRouteConnections;
}

function getBusStop($id)
{
  $result = doSql("SELECT * FROM BusStop WHERE bs_id = $id");
  
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $busStop = new BusStop();
      $busStop->id = $row["bs_id"];
      $busStop->number = $row["bs_number"];
      $busStop->coords = $row["bs_coords"];
      $busStop->name = $row["bs_name"];
      $busStop->searchName = $row["bs_search_name"];
      $busStop->isCity = $row["bs_is_city"];
      return $busStop;
    }
  }
}

function getRoutes()
{
  $BUS_LINE_ID = 1;
  $BUS_ROUTE_ID = 1;
  
  $result = doSql("SELECT * FROM Route WHERE r_bus_line_id = $BUS_LINE_ID AND r_id = $BUS_ROUTE_ID");
  
  $allRoutes = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $route = new Route();
      $route->id = $row["r_id"];
      $route->busLineId = $row["r_bus_line_id"];
      $route->destinationName = $row["r_destination_name"];
      $route->connections = getRouteConnections($route->id);
      array_push($allRoutes, $route);
    }
  }
  return $allRoutes;
}

function getDestinations($destinations, $routeId)
{
  $allDestinations = [];
  foreach ($destinations as &$destination) {
    if($destination != "-") {
      $result = doSql("SELECT * FROM Destinations WHERE ds_symbol LIKE '$destination' AND ds_route_id = $routeId");
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $destination = new Destination();
          $destination->id = $row["ds_id"];
          $destination->routeId = $row["ds_route_id"];
          $destination->symbol = $row["ds_symbol"];
          $destination->boardName = $row["ds_direction_board_name"];
          $destination->scheduleName = $row["ds_shedule_name"];
          array_push($allDestinations, $destination);
        }
      }
    }
  }
  return $allDestinations;
}

function getDeparturesByDayType($dayType, $busStopId, $routeId)
{
  $sql = "SELECT * FROM Departure JOIN BusStop ON BusStop.bs_id = Departure.d_bus_stop_id JOIN Track ON Departure.d_track_id = Track.t_id JOIN BusLine ON BusLine.bl_id = Departure.d_bus_line_id JOIN Route ON Track.t_route_id = Route.r_id JOIN Destinations ON Departure.d_symbols = Destinations.ds_symbol AND Destinations.ds_route_id = Route.r_id WHERE Departure.d_bus_stop_id = $busStopId AND Track.t_day_id IN ($dayType) AND Track.t_route_id = $routeId";
  $result = doSql($sql);
  
  $allDepartures = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $deprature = new Departure();
      $deprature->id = $row["d_id"];
      $deprature->busStopLp = $row["d_bus_stop_lp"];
      $deprature->busStopId = $row["d_bus_stop_id"];
      $deprature->trackId = $row["d_track_id"];
      $deprature->busLineId = $row["d_bus_line_id"];
      $deprature->timeInSec = $row["d_time_in_sec"];
      $deprature->timeString = $row["d_time_string"];
      $deprature->symbols = $row["d_symbols"];
      $deprature->realTime = $row["d_time_in_sec"];
      
      if ($deprature->realTime < 3600) {
        $deprature->realTime = $deprature->realTime * 86400;
      }
      array_push($allDepartures, $deprature);
    }
  }
  return $allDepartures;
}

function doSql($sql)
{
  $conn = OpenDbConnection();
  $result = $conn->query($sql);
  CloseDbConnection($conn);
  return $result;
}

