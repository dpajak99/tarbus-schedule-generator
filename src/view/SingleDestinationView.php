<?php


class SingleDestinationView
{
  static function buildSingleDestinationView($destination) {
    return <<<HTML
        <span>$destination->scheduleName</span><br />
    HTML;
  }
}