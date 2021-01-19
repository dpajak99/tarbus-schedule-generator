<?php


class ModelDayDepartures
{
  public $departuresArray;
  public $destinationsArray;
  
  public static function getModelDayDepartures($dayTypes, $busStopId, $busRouteId) {
    $modelDepartures = getDeparturesByDayType($dayTypes, $busStopId, $busRouteId);
    usort($modelDepartures, "ModelDayDepartures::sortDepartures");
    
    $destinationsShortcutsArray = array();
    foreach ($modelDepartures as &$departure) {
      $symbolSplit = str_split($departure->symbols);
      foreach ($symbolSplit as &$singleSymbol) {
        array_push($destinationsShortcutsArray, $singleSymbol);
      }
    }
    
    $modelDayDepartures = new ModelDayDepartures();
    $modelDayDepartures->departuresArray = $modelDepartures;
    $modelDayDepartures->destinationsArray = $destinationsShortcutsArray;
    
    return $modelDayDepartures;
  }
  
  public static function sortDepartures($a, $b)
  {
    return strcmp($a->realTime, $b->realTime);
  }
  
}