<?php

class actionManagerReports {
  public function __construct()
  {
    //create routes for reports.
    $generatereport = new Route();
    $generatereport->add('/', 'actionManagerDefault');
    $generatereport->add('/escalated', 'actionReportEscalated');
    $generatereport->add('/unassigned', 'actionReportUnassigned');
    $generatereport->add('/assigned', 'actionReportAssigned');
    $generatereport->add('/open', 'actionReportOpen');
    $generatereport->add('/stagnate', 'actionReportStagnate');
    $generatereport->add('/7days', 'actionReport7days');
    $generatereport->add('/sentaway', 'actionReportSentaway');
    $generatereport->add('/onhold', 'actionReportOnhold');
    $generatereport->add('/closed', 'actionReportClosed');
    $generatereport->add('/all', 'actionReportAll');
    $generatereport->add('/invoice', 'actionReportInvoice');
    $generatereport->add('/search', 'actionReportSearch');
    $generatereport->add('/working-on', 'actionReportWorkingon');
    $generatereport->add('/jobsheet', 'actionReportJobsheet');
    $generatereport->add('/changecontrol', 'actionReportChangecontrol');
    $generatereport->add('/lockers', 'actionReportLockers');
    $generatereport->add('/scheduledtasks', 'actionReportScheduledtasks');
    $generatereport->add('/outofhours', 'actionReportOutofhours');
    $generatereport->add('/performanceobjectives', 'actionReportPerformanceobjectives');
    $generatereport->process(3);
  }
}