<div id="submittedsummary" class="summary">
<h2><?php echo getString("submitted.chart.bylocation.title")?></h2>
<h3>
	<?php 
		$daysarr = array(7,14,31);
		foreach ($daysarr as $r){
			if ($days == $r){
				echo " <span style='color:green'>".getstring('dashboard.submitted.nodays',array($r))."</span>";
			} else {
				echo " <a href='?days=".$r."'>".getstring('dashboard.submitted.nodays',array($r))."</a>";
			}
		}
	?>
</h3>
<?php 
	$options = Array('height'=>300,'width'=>450,'class'=>'graph');
	include('includes/submitted/bylocation.php');
?>
</div>