<?php


class ScheduleView
{
  public $busStopObject;
  public $allBusStopsItemView;
  public $scheduleDepartures;
  public $destinationBoxView;
  
  
  function getScheduleView() {
    $busStop = $this->busStopObject;
    $scheduleDepartures = $this->scheduleDepartures;
    return <<<HTML
      <table style="width: 100%" border="1">
        <tr>
          <td>
            Firma Przewozowa "Michalus"<br />
            Michał Bodzioch<br />
            Dziekanowice 49<br />
            32-410 Dobczyce<br />
            NIP: 6811900120<br />
          </td>
          <td>$busStop->name</td>
        </tr>
        <tr>
          <td class="schedule-bus-stops">
            $this->allBusStopsItemView
          </td>
          <td>
            <table style="width: 100%">
              <tr>
                <td>
                  <span class="text-headers">DNI ROBOCZE</span>
                </td>
              </tr>
              <tr>
                <td>
                $scheduleDepartures->workDaysItemsView
                </td>
              </tr>
              <tr>
                <td>
                  <span class="text-headers">SOBOTY</span>
                </td>
              </tr>
              <tr>
                <td>
                $scheduleDepartures->freeDaysItemsView
                </td>
              </tr>
              <tr>
                <td>
                  <span class="text-headers">NIEDZIELE I ŚWIĘTA</span>
                </td>
              </tr>
              <tr>
                <td>
                $scheduleDepartures->holyDaysItemsView
                </td>
              </tr>
              <tr>
                <td>
                Oznaczenia: <br />
                $this->destinationBoxView
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    HTML;
  }
}