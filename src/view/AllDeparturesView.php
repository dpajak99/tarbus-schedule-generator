<?php


class AllDeparturesView
{
  static function buildAllDeparturesView($departures) {
    if( sizeof($departures) == 0 ) {
      return <<<HTML
        <span>NIE KURSUJE</span>
    HTML;
    } else {
      $htmlResult = "";
      foreach ($departures as &$departure ) {
        $htmlResult .= SingleDepartureView::buildSingleDepartureView($departure);
      }
     return $htmlResult;
    }
  }
}