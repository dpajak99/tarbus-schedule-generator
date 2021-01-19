<?php


class SingleDepartureView
{
  static function buildSingleDepartureView($departure) {
    $symbol = $departure->symbols;
    if ($symbol == "-") {
      $symbol = "";
    }
    
    return <<<HTML
        <span>$departure->timeString</span><span>$symbol</span>
    HTML;
  }
}