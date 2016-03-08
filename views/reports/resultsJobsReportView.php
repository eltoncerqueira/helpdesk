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

        <table id="yourcalls">
          <tbody>
            <?php
            $engineergroup = 0;
            foreach($pagedata->reportResults as $key => $value) {
                // write engineerName each time engineer changes
                while($engineergroup != $value["idengineers"]) {
                  echo "<tr><td class=\"hdtitle listheader titlepadding\" colspan=\"6\"><h3>".$value["engineerName"]."</h3></td></tr>";
                  $engineergroup = $value["idengineers"];
                }?>
            <tr>
              <td class="hdtitle listheader" colspan="6"><a href="/ticket/view/<?php echo $value["callid"] ?>" alt="view ticket"><?php echo $value["title"] ?></a></td>
            </tr>
            <tr>
              <td><span class="status<?php echo $value["status"] ?>"><?php echo $value["statusCode"] ?></span></td>
              <td>#<?php echo $value["callid"] ?></td>
              <td><?php echo date("d/m/Y", strtotime($value["opened"])) ?> - <?php echo $value["daysold"] ?> days</td>
              <td><img src="/public/images/<?php echo $value["iconlocation"] ?>" width="19" height="20" alt="<?php echo $value["locationName"] ?>" title="<?php echo $value["locationName"] ?>"/></td>
              <td><?php echo $value["engineerName"] ?></td>
              <td><a href="/ticket/view/<?php echo $value["callid"] ?>" alt="view ticket"><img src="/public/images/ICONS-view.svg" width="24" height="25" class="icon" alt="view ticket" /></a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>

      </div>
    </div>
  </div>


<?php require_once "views/partials/footer.php"; ?>
