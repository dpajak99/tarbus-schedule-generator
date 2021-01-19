<?php


class AllDestinationsView
{
  static function buildAllDestinationsView($destinations) {
    $htmlResult = "";
      foreach ($destinations as &$destination ) {
        $htmlResult .= SingleDestinationView::buildSingleDestinationView($destination);
      }
     return $htmlResult;
  }
}