<?php
$opts=array('days'=>$days,'hpcode'=>$currentHPcode);
$submitted = $API->getProtocolsSubmitted($opts);
if(count($submitted)>0){
?>
<table class="taskman">
<tr>
		<th><?php echo getString("submitted.th.date")?></th>
		<th><?php echo getString("submitted.th.by")?></th>
		<th><?php echo getString("submitted.th.patientid")?></th>
		<th><?php echo getString("submitted.th.patient")?></th>
		<th><?php echo getString("submitted.th.protocol")?></th>
	</tr>
	<?php 

		foreach ($submitted->protocols as $s){
		
				$d= strtotime($s->datestamp);
				echo "<tr class='l' title='Click to view full details'";
				printf("onclick=\"document.location.href='%spatient.php?hpcode=%s&patientid=%s&protocol=%s';\">",
							$CONFIG->homeAddress,
							$s->patienthpcode,
							$s->Q_USERID,
							$s->protocol
							);
				echo "<td nowrap>".displayAsEthioDate($d)."<br/>". date('D d M Y',$d)."</td>";
				echo "<td nowrap>".$s->submittedname."</td>";
				echo "<td nowrap>".$s->patientlocation."/".$s->Q_USERID."</td>";
				echo "<td nowrap>";
				if(trim($s->patientname) == ""){
					printf("<span class='error'>%s</span>",getstring("warning.patient.notregistered"));
				} else {
					echo $s->patientname;
				}
				echo "</td>";
				echo "<td nowrap>".getstring($s->protocol)."</td>";
				echo "</tr>";
			
		}
			
	?>
</table>

<?php 
} else {
	printf("No protocols submitted in last $1%d days ",$days);
}
?>