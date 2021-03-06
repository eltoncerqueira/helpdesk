<?php

  class statsModel {
    public function __construct()
    { }

    public function countAllTickets() {
      $database = new Database();
      $database->query("SELECT COUNT(*) AS countAllTickets
                        FROM calls");
      $result = $database->single();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countAllOpenTickets() {
      $database = new Database();
      $database->query("SELECT COUNT(*) AS countAllOpenTickets FROM calls
                        WHERE status !=2");
      $result = $database->single();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countTicketsByHelpdesk($helpdeskid) {
      $database = new Database();
      $database->query("SELECT COUNT(*) AS countTicketsByHelpdesk FROM calls
                        WHERE helpdesk IN (:helpdeskid)");
      $database->bind(":helpdeskid", $helpdeskid);
      $result = $database->single();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countTicketsByStatusCode($statuscode, $scope = null) {
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT COUNT(*) AS countTotal
                        FROM calls
                        WHERE status = :status
                        AND FIND_IN_SET(calls.helpdesk, :scope)");
      $database->bind(":status", $statuscode);
      $database->bind(":scope", $helpdesks);
      $result = $database->single();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countEngineerTotalsOutstatnding($scope = null) {
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT engineerName,
                        sum(CASE WHEN calls.status = 1 THEN 1 ELSE 0 END) AS open,
                        sum(CASE WHEN calls.status = 3 THEN 1 ELSE 0 END) AS onhold,
                        sum(CASE WHEN calls.status = 4 THEN 1 ELSE 0 END) AS escalated
                        FROM engineers
                        LEFT JOIN calls ON calls.assigned = engineers.idengineers
                        WHERE FIND_IN_SET(calls.helpdesk, :scope)
                        AND engineers.disabled = 0
                        AND calls.status !=2
                        GROUP BY engineerName
                        ORDER BY engineerName
      ");
      $database->bind(":scope", $helpdesks);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countTicketsByOwner($owner) {
      $database = new Database();
      $database->query("SELECT COUNT(*) AS countTicketsByOwner FROM calls
                        WHERE owner = :owner");
      $database->bind(":owner", $owner);
      $result = $database->single();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countDepartmentWorkrateByDay($helpdeskid) {
      $database = new Database();
      $database->query("SELECT engineerName,
                        sum(case when DATE(calls.closed) = DATE_SUB(CURDATE(),INTERVAL 0 DAY) THEN 1 ELSE 0 END) AS mon,
                        sum(case when DATE(calls.closed) = DATE_SUB(CURDATE(),INTERVAL 1 DAY) THEN 1 ELSE 0 END) AS tue,
                        sum(case when DATE(calls.closed) = DATE_SUB(CURDATE(),INTERVAL 2 DAY) THEN 1 ELSE 0 END) AS wed,
                        sum(case when DATE(calls.closed) = DATE_SUB(CURDATE(),INTERVAL 3 DAY) THEN 1 ELSE 0 END) AS thu,
                        sum(case when DATE(calls.closed) = DATE_SUB(CURDATE(),INTERVAL 4 DAY) THEN 1 ELSE 0 END) AS fri,
                        sum(case when DATE(calls.closed) = DATE_SUB(CURDATE(),INTERVAL 5 DAY) THEN 1 ELSE 0 END) AS sat,
                        sum(case when DATE(calls.closed) = DATE_SUB(CURDATE(),INTERVAL 6 DAY) THEN 1 ELSE 0 END) AS sun,
                        sum(case when calls.closed >= DATE_SUB(CURDATE(),INTERVAL 6 DAY) THEN 1 ELSE 0 END) AS total7
                        FROM engineers
                        LEFT JOIN calls ON calls.closeengineerid = engineers.idengineers
                        WHERE engineers.helpdesk IN (:helpdeskid) OR FIND_IN_SET(engineers.helpdesk, :helpdeskid)
                        AND engineers.disabled=0
                        GROUP BY engineerName
                        ORDER BY total7 DESC
                        ");
      $database->bind(":helpdeskid", $helpdeskid);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countEngineerTotalsThisMonth($scope = null) {
      // DEPRICATED USE countEngineerTotals() METHOD ON ENGINEERS MODEL
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT engineers.engineerName, count(calls.callid) AS Totals FROM calls
                        JOIN engineers ON calls.closeengineerid=engineers.idengineers
                        WHERE status = 2
                        AND Month(closed) = :month
                        AND Year(closed) = :year
                        AND FIND_IN_SET(calls.helpdesk, :scope)
                        GROUP BY calls.closeengineerid
                        ORDER BY Totals");
      $database->bind(':month', date("m"));
      $database->bind(':year', date("o"));
      $database->bind(':scope', $helpdesks);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countHelpdeskTotalsThisMonth($scope = null) {
      // DEPRICATED USE countHelpdeskTotals() METHOD ON HELPDESK MODEL
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT helpdesks.helpdesk_name, count(calls.callid) AS Totals FROM calls
                        JOIN helpdesks ON calls.helpdesk=helpdesks.id
                        WHERE status = 2
                        AND Month(closed) = :month
                        AND Year(closed) = :year
                        AND FIND_IN_SET(calls.helpdesk, :scope)
                        GROUP BY calls.helpdesk, Month(calls.closed)
                        ORDER BY Totals");
      $database->bind(':month', date("m"));
      $database->bind(':year', date("o"));
      $database->bind(':scope', $helpdesks);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countCategoryTotalsThisMonth($scope = null) {
      // DEPRICATED USE countCategoryTotals() METHOD ON CATEGORY MODEL
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT categories.categoryName, count(calls.callid) AS Totals FROM calls
                        JOIN categories ON calls.category=categories.id
                        WHERE status = 2
                        AND Month(closed) = :month
                        AND Year(closed) = :year
                        AND FIND_IN_SET(calls.helpdesk, :scope)
                        GROUP BY calls.category, Month(calls.closed)
                        ORDER BY Totals");
      $database->bind(':month', date("m"));
      $database->bind(':year', date("o"));
      $database->bind(':scope', $helpdesks);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      // else populate object with db results
      return $result;
    }

    public function countUrgencyTotalsThisMonth($scope = null) {
      // DEPRICATED USE countUrgencyTotals() METHOD ON TICKETS MODEL
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT calls.urgency, count(calls.callid) AS Totals
                        FROM calls
                        JOIN categories ON calls.category=categories.id
                        WHERE status = 2
                        AND Month(closed) = :month
                        AND Year(closed) = :year
                        AND FIND_IN_SET(calls.helpdesk, :scope)
                        GROUP BY calls.urgency, Month(calls.closed)
                        ORDER BY Totals");
      $database->bind(':month', date("m"));
      $database->bind(':year', date("o"));
      $database->bind(':scope', $helpdesks);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      // update array values with friendly name as they arent in the db!!!!
      foreach($result as $key => $value) {
        $result[$key]["urgency"] = urgency_friendlyname(array_values($value)[0]);
      }
      return $result;
    }

    public function countPlannedVsReactiveTotalsThisMonth($scope = null) {
      // DEPRICATED USE countPlannedVsReactiveTotals() METHOD on TICKETS MODEL
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT calls.pm, count(calls.callid) AS Totals FROM calls
                        WHERE status = 2
                        AND Month(closed) = :month
                        AND Year(closed) = :year
                        AND FIND_IN_SET(calls.helpdesk, :scope)
                        GROUP BY calls.pm
                        ORDER BY Totals");
      $database->bind(':month', date("m"));
      $database->bind(':year', date("o"));
      $database->bind(':scope', $helpdesks);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      // else populate object with db results
      // update array values with friendly name as they arent in the db!!!!
      foreach($result as $key => $value) {
        ($result[$key]["pm"] == 1 ? $result[$key]["pm"] = "Planned Tickets" : $result[$key]["pm"] = "Reactive Tickets");
      }
      return $result;
    }

    public function countTotalsThisYear($year) {
      $database = new Database();
      $database->query("SELECT Month(closed) AS MonthNum, count(callid) AS Totals
                        FROM calls
                        WHERE status = 2
                        AND Year(closed) = :year
                        GROUP BY Month(closed)
                        ORDER BY MonthNum, helpdesk");
      $database->bind(':year', $year);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countTotalsThisYearbyHelpdesk($year,$helpdesk) {
      $database = new Database();
      $database->query("SELECT MONTH(calls.closed) AS MonthNum, count(calls.callid) AS Totals
                        FROM calls
                        JOIN helpdesks ON calls.helpdesk=helpdesks.id
                        WHERE calls.status = 2
                        AND calls.helpdesk = :helpdesk
                        AND Year(calls.closed) = :year
                        GROUP BY Month(calls.closed)
                        ORDER BY MonthNum, calls.helpdesk");
      $database->bind(':helpdesk', $helpdesk);
      $database->bind(':year', $year);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countWorkRateTotalsThisMonth($scope = null) {
      // DEPRICATED USE countWorkRateTotals() METHOD on TICKETS MODEL
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT engineers.engineerName, helpdesks.helpdesk_name, sum(case when calls.closed >= DATE_SUB(CURDATE(),INTERVAL 6 DAY) THEN 1 ELSE 0 END) AS Last7,
                        sum(case when calls.closed >= DATE_SUB(CURDATE(),INTERVAL 1 DAY) THEN 1 ELSE 0 END) AS Last1,
                        sum(case when calls.closed >= DATE_SUB(CURDATE(),INTERVAL 30 DAY) THEN 1 ELSE 0 END) AS Last30
                        FROM engineers
                        JOIN calls ON calls.closeengineerid = engineers.idengineers
                        JOIN helpdesks ON engineers.helpdesk=helpdesks.id
                        WHERE engineers.disabled != 1
                        AND Month(closed) = :month
                        AND Year(closed) = :year
                        AND FIND_IN_SET(calls.helpdesk, :scope)
                        GROUP BY engineers.engineerName
                        ORDER BY Last30 DESC");
      $database->bind(':month', date("m"));
      $database->bind(':year', date("o"));
      $database->bind(':scope', $helpdesks);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countReasonForTicketsThisMonth($scope = null) {
      // DEPRICATED USE countReasonForTickets() METHOD ON TICKETS MODEL
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT call_reasons.reason_name, count(*) AS last7
                        FROM calls
                        INNER JOIN call_reasons ON calls.callreason = call_reasons.id
                        WHERE calls.status='2'
                        AND calls.closed >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                        AND FIND_IN_SET(calls.helpdesk, :scope)
                        GROUP BY call_reasons.reason_name");
      $database->bind(':scope', $helpdesks);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countAssignedTickets($scope = null) {
      // DEPRICATED USE countAssignedTickets() METHOD ON TICKETS MODEL
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT helpdesks.helpdesk_name, engineers.idengineers, engineers.engineerName, Count(assigned) AS HowManyAssigned, sum(case when status !=2 THEN 1 ELSE 0 END) AS OpenOnes
                        FROM calls
                        JOIN engineers ON calls.assigned=engineers.idengineers
                        JOIN helpdesks ON engineers.helpdesk=helpdesks.id
                        WHERE engineers.disabled != 1
                        AND FIND_IN_SET(calls.helpdesk, :scope)
                        GROUP BY calls.assigned
                        ORDER BY calls.helpdesk");
      $database->bind(':scope', $helpdesks);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countDayBreakdownTotalsLastMonth($scope = null) {
    // DEPRICATED USE countDayBreakdownTotals() METHOD ON TICKETS MODEL
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT engineers.engineerName,
                        helpdesks.helpdesk_name,
                        sum(case when hour(calls.closed) < 7 || hour(calls.lastupdate) < 7 THEN 1 ELSE 0 END) AS '0-7',
                        sum(case when hour(calls.closed) = 7 || hour(calls.lastupdate) = 7 THEN 1 ELSE 0 END) AS '7-8',
                        sum(case when hour(calls.closed) = 8 || hour(calls.lastupdate) = 8 THEN 1 ELSE 0 END) AS '8-9',
                        sum(case when hour(calls.closed) = 9 || hour(calls.lastupdate) = 9 THEN 1 ELSE 0 END) AS '9-10',
                        sum(case when hour(calls.closed) = 10 || hour(calls.lastupdate) = 10 THEN 1 ELSE 0 END) AS '10-11',
                        sum(case when hour(calls.closed) = 11 || hour(calls.lastupdate) = 11 THEN 1 ELSE 0 END) AS '11-12',
                        sum(case when hour(calls.closed) = 12 || hour(calls.lastupdate) = 12 THEN 1 ELSE 0 END) AS '12-13',
                        sum(case when hour(calls.closed) = 13 || hour(calls.lastupdate) = 13 THEN 1 ELSE 0 END) AS '13-14',
                        sum(case when hour(calls.closed) = 14 || hour(calls.lastupdate) = 14 THEN 1 ELSE 0 END) AS '14-15',
                        sum(case when hour(calls.closed) = 15 || hour(calls.lastupdate) = 15 THEN 1 ELSE 0 END) AS '15-16',
                        sum(case when hour(calls.closed) = 16 || hour(calls.lastupdate) = 16 THEN 1 ELSE 0 END) AS '16-17',
                        sum(case when hour(calls.closed) = 17 || hour(calls.lastupdate) = 17 THEN 1 ELSE 0 END) AS '17-18',
                        sum(case when hour(calls.closed) = 18 || hour(calls.lastupdate) = 18 THEN 1 ELSE 0 END) AS '18-19',
                        sum(case when hour(calls.closed) > 19 || hour(calls.lastupdate) > 19 THEN 1 ELSE 0 END) AS '19-24'
                        FROM engineers
                        JOIN calls ON calls.closeengineerid = engineers.idengineers
                        JOIN helpdesks ON engineers.helpdesk = helpdesks.id
                        WHERE engineers.disabled != 1
                        AND month(calls.closed) = :month
                        AND year(calls.closed) = :year
                        AND FIND_IN_SET(calls.helpdesk, :scope)
                        GROUP BY engineers.engineerName
                        ORDER BY helpdesks.id
                      ");
      $database->bind(':month', date("m", strtotime("first day of previous month")));
      $database->bind(':year', date("o"));
      $database->bind(':scope', $helpdesks);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countEngineerFeedbackTotals($scope = null) {
      // DEPRICATED USE countEngineerFeedbackTotals() METHOD ON TICKETS MODEL
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT engineers.engineerName, helpdesks.helpdesk_name, AVG(feedback.satisfaction) as FeedbackAVG, COUNT(calls.callid) as FeedbackCOUNT
                        FROM calls
                        JOIN feedback ON feedback.callid=calls.callid
                        JOIN engineers ON engineers.idengineers=calls.closeengineerid
                        JOIN helpdesks ON engineers.helpdesk = helpdesks.id
                        AND FIND_IN_SET(calls.helpdesk, :scope)
                        GROUP BY calls.closeengineerid");
      $database->bind(':scope', $helpdesks);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function avgHelpdeskFeedback() {
      $database = new Database();
      $database->query("SELECT AVG(feedback.satisfaction) as FeedbackAVG
                        FROM calls
                        JOIN feedback ON feedback.callid=calls.callid
                        JOIN engineers ON engineers.idengineers=calls.closeengineerid
                        JOIN helpdesks ON engineers.helpdesk = helpdesks.id
                        WHERE calls.status = 2
                        GROUP BY calls.status");
      $result = $database->single();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function avgHelpdeskFeedbackByHelpdesk($helpdeskid) {
      $database = new Database();
      $database->query("SELECT AVG(feedback.satisfaction) as FeedbackAVG
                        FROM calls
                        JOIN feedback ON feedback.callid=calls.callid
                        JOIN engineers ON engineers.idengineers=calls.closeengineerid
                        JOIN helpdesks ON engineers.helpdesk = helpdesks.id
                        WHERE calls.status = 2
                        AND calls.helpdesk = :helpdesk
                        GROUP BY calls.status");
      $database->bind(':helpdesk', $helpdeskid);
      $result = $database->single();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function getPoorFeedback($scope = null) {
      // DEPRICATED USE getPoorFeedback() METHOD ON FEEDBACK MODEL
      isset($scope) ? $helpdesks = $scope : $helpdesks = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"; // fudge for all helpdesks should be count of active helpdesks (//TODO FIX THIS)
      $database = new Database();
      $database->query("SELECT calls.callid, engineers.engineerName, calls.owner, feedback.details, feedback.satisfaction
                        FROM feedback
                        JOIN calls ON feedback.callid=calls.callid
                        JOIN engineers ON engineers.idengineers=calls.closeengineerid
                        WHERE satisfaction IN (1,2)
                        AND feedback.opened > DATE_SUB(CURDATE(),INTERVAL 1 MONTH)
                        AND FIND_IN_SET(calls.helpdesk, :scope)
                        ");
      $database->bind(':scope', $helpdesks);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countEngineerTotalsLastWeek($engineerId) {
      $database = new Database();
      $database->query("SELECT DATE_FORMAT(closed, '%a') AS DAY_OF_WEEK
                        FROM calls
                        WHERE closeengineerid = :engineerId
                        AND closed >= DATE_SUB(CURDATE(),INTERVAL 1 WEEK)");
      $database->bind(':engineerId', $engineerId);
      $result = $database->resultset();
      if ($database->rowCount() === 0) { return null;}
      $engineermon = $engineertue = $engineerwed = $engineerthu = $engineerfri = $engineersat = $engineersun = 0;

      foreach($result as $key => $value) {
        SWITCH ($value["DAY_OF_WEEK"]) {
          CASE "Mon":
            ++$engineermon;
            break;
          CASE "Tue":
            ++$engineertue;
            break;
          CASE "Wed":
            ++$engineerwed;
            break;
          CASE "Thu":
            ++$engineerthu;
            break;
          CASE "Fri":
            ++$engineerfri;
            break;
          CASE "Sat":
            ++$engineersat;
            break;
          CASE "Sun":
            ++$engineersun;
            break;
        }
      }
      $count = array();
      $count["Mon"] = $engineermon;
      $count["Tue"] = $engineertue;
      $count["Wed"] = $engineerwed;
      $count["Thu"] = $engineerthu;
      $count["Fri"] = $engineerfri;
      $count["Sat"] = $engineersat;
      $count["Sun"] = $engineersun;
      return $count;
    }

    public function countClosedByEngineerIdLastWeek($engineerId) {
      $database = new Database();
      $database->query("SELECT count(closeengineerid) AS engineerClose
                        FROM calls
                        WHERE closed >= DATE_SUB(CURDATE(),INTERVAL 1 WEEK)
                        AND closeengineerid = :engineerId ");
      $database->bind(':engineerId', $engineerId);
      $result = $database->single();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function countAllTicketsByEngineerIdLastWeek($engineerId) {
      $database = new Database();
      $database->query("SELECT count(callid) AS engineerAll
                        FROM calls
                        WHERE lastupdate >= DATE_SUB(CURDATE(),INTERVAL 1 WEEK)
                        AND assigned = :engineerId");
      $database->bind(':engineerId', $engineerId);
      $result = $database->single();
      if ($database->rowCount() === 0) { return null;}
      return $result;
    }

    public function avgCloseTimeInDays() {
      $database = new Database();
      $database->query("SELECT helpdesks.helpdesk_name, avg(datediff(calls.closed, calls.opened)) as avg_days
                        FROM calls
                        JOIN helpdesks ON calls.helpdesk = helpdesks.id
                        GROUP BY helpdesk");
      $results = $database->resultset();
      if ($database->rowCount() === 0) { return null; }
      return $results;
    }

    public function advCloseTimeByHelpdeskIdInDays($helpdesk) {
      $database = new Database();
      $database->query("SELECT avg(datediff(calls.closed, calls.opened)) as avg_days
                        FROM calls
                        JOIN helpdesks ON calls.helpdesk = helpdesks.id
                        WHERE calls.helpdesk = :helpdesk
                        GROUP BY calls.helpdesk");
      $database->bind(':helpdesk', $helpdesk);
      $results = $database->single();
      if ($database->rowCount() === 0) { return 0; }
      return $results;
    }

    public function countOutstandingTicketsByHelpdesk($helpdesk) {
      $database = new Database();
      $database->query("SELECT count(calls.callid) as outstanding
                        FROM calls
                        JOIN helpdesks ON calls.helpdesk = helpdesks.id
                        WHERE calls.status != 2
                        AND calls.helpdesk = :helpdesk
                        GROUP BY calls.helpdesk");
      $database->bind(':helpdesk', $helpdesk);
      $results = $database->single();
      if ($database->rowCount() ===0) { return 0; }
      return $results;
    }


  }
