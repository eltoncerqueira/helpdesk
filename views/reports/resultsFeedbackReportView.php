<?php require_once "views/partials/header.php"; ?>


  <div id="leftpage">
    <?php require_once "views/partials/leftside/reports.php" ?>
  </div>

  <div id="rightpage">
    <div id="call">
      <div id="ajax">
        <h1><?php echo $pagedata->title ?></h1>
        <p><?php echo $pagedata->details ?></p>
        <table id="tablesorter" class="tablesorter">
          <thead>
            <tr>
              <th>Engineer Name</th>
              <th>Helpdesk</th>
              <th>Feedback Average</th>
              <th>Feedback Count</th>
              <th>View All</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($pagedata->reportResults)) { foreach($pagedata->reportResults as $key => $value) { ?>
            <tr>
              <td><?php echo $value["engineerName"] ?></td>
              <td><?php echo $value["helpdesk_name"] ?></td>
              <td><?php for ($i = 0; $i < round($value["FeedbackAVG"]); $i++) { echo "<img src='/public/images/ICONS-star.svg' alt='star' height='24' width='auto' />"; } ?></td>
              <td><?php echo $value["FeedbackCOUNT"] ?></td>
              <td><a href="/report/feedback/<?php echo $value["closeengineerid"] ?>" alt="view feedback"><img src="/public/images/ICONS-view.svg" width="24" height="25" alt="view feedback" /></a></td>
            </tr>
            <?php } } ?>
          </tbody>
        </table>
        <script>
        // activate table sorting jquery library
        $(document).ready(function() { $("#tablesorter").tablesorter(); });
        </script>
        <p></p>
        <h3>Poor Feedback Ticket Details</h3>
        <p>
          List of tickets that have feedback values equal or lower than 2 stars, with link to ticket details for context.
        </p>
        <table>
          <thead>
            <tr>
              <th>Engineer Name</th>
              <th>Ticket Owner (user)</th>
              <th>Feedback Details</th>
              <th>Feedback</th>
              <th>View</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($pagedata->poorFeedback)) { foreach($pagedata->poorFeedback as $key => $value) { ?>
            <tr>
             <td><?php echo $value["engineerName"] ?></td>
              <td><?php echo $value["owner"] ?></td>
              <td><?php echo $value["details"] ?></td>
              <td><?php for ($i = 0; $i < round($value["satisfaction"]); $i++) { echo "<img src='/public/images/ICONS-star.svg' alt='star' height='24' width='auto' />"; } ?></td>
              <td><a href="/ticket/view/<?php echo $value["callid"] ?>" alt="view ticket"><img src="/public/images/ICONS-view.svg" width="24" height="25" class="icon" alt="view ticket" /></a></td>
            </tr>
            <?php } } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>


<?php require_once "views/partials/footer.php"; ?>
