<?php

class actionReportOutofhours {
  public function __construct()
  {
    //load content for left side of page
    $left = new leftpageController();
    //create new models for required data
    $helpdeskModel = new helpdeskModel();
    $outofhoursModel = new outofhoursModel();
    $pagedata = new stdClass();

    //Post Update Objective
      if ($_POST) {
        SWITCH ($_POST["button_value"]) {
          CASE "add":
            // reroute to add form
            header('Location: /outofhours/add');
            exit;
          break;
          CASE "update":
            //TODO should change control be updatable?
          break;
          CASE "modify":
            //TODO should change control be modifiable?
          break;
          CASE "delete":
            //TODO should engineers be able to delete change controls?
          break;
        }
      }

    //set report name
    $reportname = "Out of hours";
    //set report title
    $pagedata->title = $reportname . "";
    //populate report results for use in view
    $pagedata->reportResults = $outofhoursModel->getOutOfHours(1);
    //get helpdesk details
    $helpdeskdetails = $helpdeskModel->getFriendlyHelpdeskName($_SESSION['engineerHelpdesk']);
    //set page details
    $pagedata->details = sizeof($pagedata->reportResults)." ".$reportname." for ".$helpdeskdetails["helpdesk_name"]." helpdesk.";
    //render template using $pagedata object
    require_once "views/reports/resultsOutOfHoursView.php";
  }
}
