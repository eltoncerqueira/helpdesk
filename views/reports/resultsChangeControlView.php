<?php require_once "views/partials/header.php"; ?>


  <div id="leftpage">
    <div id="stats">
      <p>
        <?php ($stats ? include "views/partials/graphs/managerStats.php" : print("no data")) ?>
      </p>
    </div>
    <div id="calllist">
        <?php include "views/partials/listManagerReports.php" ?>
    </div>
    </div>
  <div id="rightpage">
    <div id="call">
      <div id="ajax">
        <h1><?php echo $pagedata->title ?></h1>
        <p><?php echo $pagedata->details ?></p>
        <p>
          //TODO crud change controls 
        </p>
        <table id="changecontrol">
            <?php foreach($pagedata->reportResults as $key => $value) { ?>
              <tbody>
              <tr>
                <td class="hdtitle listheader" colspan="2"><?php echo $value["server"] ?></td>
              </tr>
              <tr>
                <td><?php echo date("d-m-Y @ H:i", strtotime($value["stamp"])) ?></td>
                <td><?php echo $value["engineerName"] ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo nl2br($value["changemade"]) ?></td>
              </tr>
              <tr>
                <td colspan="2" class="hdtitle"><?php echo $value["tags"] ?></td>
              </tr>
              </tbody>
            <?php } ?>
        </table>

      </div>
    </div>
  </div>


<?php require_once "views/partials/footer.php"; ?>
