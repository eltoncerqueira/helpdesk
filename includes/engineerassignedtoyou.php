<div id="ajaxforms">
	<table>
	<tbody>
	<?php 
		//run select query
		$result = mysqli_query($db, "SELECT * FROM calls WHERE assigned='". $_SESSION['engineerId']  ."' AND status='1'");
		if (mysqli_num_rows($result) == 0) { echo "<p>Woop! you have closed all your calls!</p><p>Why not boost your stats and help someone else out in the department with the calls on the right.</p>";};
		while($calls = mysqli_fetch_array($result))  {
		?>
		<tr>
		<td>#<?=$calls['callid'];?></td>
		<td><?=date("d/m/y", strtotime($calls['opened']));?></td>
		<td class="view_td"><?=substr(strip_tags($calls['details']), 0, 120);?>...</td>
		<td>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="assignedtoyou">
				<input type="hidden" id="id" name="id" value="<?=$calls['callid'];?>" />
				<button name="submit" value="submit" type="submit" >View</button>
			</form>
		</td>
		
		
		</tr>
	<? } ?>
	</tbody>
	</table>
</div>
	
		
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>	
	<script src="javascript/jquery.js" type="text/javascript"></script>
	<script type="text/javascript">
    // Ajax form submit
    $('.assignedtoyou').submit(function(e) {
        // Post the form data to viewcall
        $.post('viewcallpost.php', $(this).serialize(), function(resp) {
            // return response data into div
            $('#ajax').html(resp);
        });
        // Cancel the actual form post so the page doesn't refresh
        e.preventDefault();
        return false;
    });     
    </script>