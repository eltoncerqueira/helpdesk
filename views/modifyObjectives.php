<?php require_once "views/partials/header.php"; ?>


  <div id="leftpage">
    //TODO Left side
  </div>
  <div id="rightpage">
    <div id="call">
      <div id="ajax">
        <?php include "views/partials/modifyObjective.php"; ?>
        <?php if (!$_POST) { include "views/forms/modifyObjectiveForm.php"; } ?>
      </div>
    </div>
  </div>


<?php require_once "views/partials/footer.php"; ?>
