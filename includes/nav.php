<div id="non-mobile-menu">
<a href="#" onclick="update_div('#ajax','/form/add_ticket.php');update_div('#stats','/user_welcome.php');update_div('#calllist','reports/list_your_tickets.php');">Add Ticket</a> /
<?php if ($_SESSION['engineerLevel'] === "1" or $_SESSION['engineerLevel'] === "2" or $_SESSION['superuser'] === "1") { ?><a href="#" onclick="update_div('#ajax','reports/view_your_oldest_ticket.php');update_div('#stats','reports/graph_my_performance.php');update_div('#calllist','reports/list_engineers_tickets.php');">Engineer View</a><br/><?php }; ?>
<?php if ($_SESSION['engineerLevel'] === "2" or $_SESSION['superuser'] === "1") { ?><a href="#" onclick="update_div('#ajax','reports/manager_default_view.php');update_div('#stats','reports/graph_department_overview.php');update_div('#calllist','reports/list_manager_reports.php');">Manager View</a><br/><?php }; ?>
<?php if ($_SESSION['engineerLevel'] === "2" or $_SESSION['superuser'] === "1") { ?><a href="#" onclick="update_div('#ajax','reports/reports_default_view.php');update_div('#stats','reports/graph_reports_overview.php');update_div('#calllist','reports/list_reports_view_reports.php');">Reports View</a><br/><?php }; ?>
<?php if ($_SESSION['superuser'] === "1") { ?><a href="#" onclick="update_div('#ajax','reports/admin_default_view.php');update_div('#stats','reports/graph_admin_overview.php');update_div('#calllist','reports/list_reports_admin_reports.php');">Admin View</a><br/><?php }; ?>
<a href="/login/logout.php" class="logout">Log out</a><br/>
</div>
<a href="#sidr" id="mobile-menu"><img src="/public/images/svg/ICONS-hamburger.svg" width="24" height="auto" /></a>