<?php 

class RiskAssessment {
	function getRisks($p){
		$risk = new stdClass;
		$risk->risks = array();
		$risk->count = 0;
		/*
		 * Age
		 */ 
		$risk->risks['Q_AGE_UNDER18'] = false;
		// from Registration 
		if(isset($p->Q_AGE) && $p->Q_AGE < 18 ){
			$risk->risks['Q_AGE_UNDER18'] = true;
		} 
		// from ANC first
		if(isset($p->ancfirst->Q_AGE) && $p->ancfirst->Q_AGE < 18){
			$risk->risks['Q_AGE_UNDER18'] = true;
		}
		//from ANC Follow
		foreach($p->ancfollow as $x){
			if(isset($x->Q_AGE) && $x->Q_AGE < 18){
				$risk->risks['Q_AGE_UNDER18'] = true;
			}
		}
		//from ANC Transfer
		foreach($p->anctransfer as $x){
			if(isset($x->Q_AGE) && $x->Q_AGE < 18){
				$risk->risks['Q_AGE_UNDER18'] = true;
			}
		}
		
		$risk->risks['Q_AGE_OVER34'] = false;
		// from Registration
		if(isset($p->Q_AGE) && $p->Q_AGE > 34){
			$risk->risks['Q_AGE_OVER34'] = true;
		}
		// from ANC first
		if(isset($p->ancfirst->Q_AGE) && $p->ancfirst->Q_AGE > 34){
			$risk->risks['Q_AGE_OVER34'] = true;
		}
		//from ANC Follow
		foreach($p->ancfollow as $x){
			if(isset($x->Q_AGE) && $x->Q_AGE > 34){
				$risk->risks['Q_AGE_OVER34'] = true;
			}
		}
		//from ANC Transfer
		foreach($p->anctransfer as $x){
			if(isset($x->Q_AGE) && $x->Q_AGE > 34){
				$risk->risks['Q_AGE_OVER34'] = true;
			}
		}
		if($risk->risks['Q_AGE_OVER34'] == true || $risk->risks['Q_AGE_UNDER18'] == true){
			$risk->count++;
		}
		
		/*
		 * Birth Interval
		 */
		$risk->risks['Q_BIRTHINTERVAL'] = false;
		// from ANC First
		if(isset($p->ancfirst->Q_BIRTHINTERVAL) && ($p->ancfirst->Q_BIRTHINTERVAL == 'within1' || $p->ancfirst->Q_BIRTHINTERVAL == 'within2')){
			$risk->risks['Q_BIRTHINTERVAL'] = true;
		}
		// from Transfer
		foreach($p->anctransfer as $x){
			if(isset($x->Q_BIRTHINTERVAL) && ($x->Q_BIRTHINTERVAL == 'within1' || $x->Q_BIRTHINTERVAL == 'within2')){
				$risk->risks['Q_BIRTHINTERVAL'] = true;
			}
		}
		if($risk->risks['Q_BIRTHINTERVAL'] == true){
			$risk->count++;
		}
		
		/*
		 * Birth order/gravida
		 */
		$risk->risks['Q_GRAVIDA'] = false;
		// from ANC First
		if(isset($p->ancfirst->Q_GRAVIDA) && ($p->ancfirst->Q_GRAVIDA > 6)){
			$risk->risks['Q_GRAVIDA'] = true;
		}
		// from Transfer
		foreach($p->anctransfer as $x){
			if(isset($x->Q_GRAVIDA) && ($x->Q_GRAVIDA > 6)){
				$risk->risks['Q_GRAVIDA'] = true;
			}
		}
		if($risk->risks['Q_GRAVIDA'] == true){
			$risk->count++;
		}
		
		/*
		 * Current pregnancy complaints
		 */
		$risks = array ('Q_ABDOMINALPAIN', 'Q_BLEEDING', 'Q_FATIGUE','Q_FEVER','Q_HEADACHE');
		foreach ($risks AS $r){
			$risk->risks[$r] = false;
			// from ANC First
			if(isset($p->ancfirst->{$r}) && ($p->ancfirst->{$r} == 'yes')){
				$risk->risks[$r] = true;
			}
			// from ANC Follow
			foreach($p->ancfollow as $x){
				if(isset($x->{$r}) && ($x->{$r} == 'yes')){
					$risk->risks[$r] = true;
				}
			}
			if($risk->risks[$r] == true){
				$risk->count++;
			}
		}
		
		/*
		 * Obstetrics/Gynaecology factors
		 */ 
		$risks = array ('Q_STILLBIRTHS', 'Q_ABORTION');
		foreach ($risks AS $r){
			$risk->risks[$r] = false;
			// from ANC First
			if(isset($p->ancfirst->{$r}) && ($p->ancfirst->{$r} >= 1)){
				$risk->risks[$r] = true;
			}
			// from ANC Transfer
			foreach($p->anctransfer as $x){
				if(isset($x->{$r}) && ($x->{$r} >= 1)){
					$risk->risks[$r] = true;
				}
			}
			if($risk->risks[$r] == true){
				$risk->count++;
			}
		}
		
		$factor = 'Q_YOUNGESTCHILD';
		$risk->risks[$factor] = false;
		// from ANC First
		if(isset($p->ancfirst->{$factor}) && ($p->ancfirst->{$factor} == 'no')){
			$risk->risks[$factor] = true;
		}
		// from ANC Transfer
		foreach($p->anctransfer as $x){
			if(isset($x->{$factor}) && ($x->{$factor} == 'no')){
				$risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		
		$risks = array ('Q_PREECLAMPSIA', 
						'Q_BLEEDINGPREVPREG',
						'Q_CSECTION',
						'Q_VACUUMDELIVERY',
						'Q_NEWBORNDEATH',
						'Q_PROLONGEDLABOR',
						'Q_FISTULA',
						'Q_MALPOSITION',
						'Q_TWIN');
		foreach ($risks AS $r){
			$risk->risks[$r] = false;
			// from ANC First
			if(isset($p->ancfirst->{$r}) && ($p->ancfirst->{$r} == 'yes')){
				$risk->risks[$r] = true;
			}
			// from ANC Transfer
			foreach($p->anctransfer as $x){
				if(isset($x->{$r}) && ($x->{$r} == 'yes')){
					$risk->risks[$r] = true;
				}
			}
			if($risk->risks[$r] == true){
				$risk->count++;
			}
		}
		
		$factor = 'Q_BABYWEIGHT';
		$risk->risks[$factor] = false;
		// from ANC First
		if(isset($p->ancfirst->{$factor}) && ($p->ancfirst->{$factor} != 'neither')){
			$risk->risks[$factor] = true;
		}
		// from ANC Transfer
		foreach($p->anctransfer as $x){
			if(isset($x->{$factor}) && ($x->{$factor} != 'neither')){
				$risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		$factor = 'Q_PREPOSTTERM';
		$risk->risks[$factor] = false;
		// from ANC First
		if(isset($p->ancfirst->{$factor}) && ($p->ancfirst->{$factor} != 'neither')){
			$risk->risks[$factor] = true;
		}
		// from ANC Transfer
		foreach($p->anctransfer as $x){
			if(isset($x->{$factor}) && ($x->{$factor} != 'neither')){
				$risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		/*
		 *  Social factors
		 */ 
		$risks = array ('Q_SOCIALSUPPORT', 'Q_ECONOMICS', 'Q_TRANSPORTATION');
		foreach ($risks AS $r){
			$risk->risks[$r] = false;
			// from ANC First
			if(isset($p->ancfirst->{$r}) && ($p->ancfirst->{$r} == 'no')){
				$risk->risks[$r] = true;
			}
			// from ANC Transfer
			foreach($p->anctransfer as $x){
				if(isset($x->{$r}) && ($x->{$r} == 'no')){
					$risk->risks[$r] = true;
				}
			}
			if($risk->risks[$r] == true){
				$risk->count++;
			}
		}
		
		/*
		 * General medical factors
		 */ 
		$risks = array ('Q_DIABETES', 'Q_TUBERCULOSIS', 'Q_HYPERTENSION');
		foreach ($risks AS $r){
			$risk->risks[$r] = false;
			// from ANC First
			if(isset($p->ancfirst->{$r}) && ($p->ancfirst->{$r} == 'yes')){
				$risk->risks[$r] = true;
			}
			// from ANC Follow
			foreach($p->ancfollow as $x){
				if(isset($x->{$r}) && ($x->{$r} == 'yes')){
					$risk->risks[$r] = true;
				}
			}
			if($risk->risks[$r] == true){
				$risk->count++;
			}
		}
		
		$factor = 'Q_HIV';
		$risk->risks[$factor] = false;
		// from ANC First
		if(isset($p->ancfirst->{$factor}) && ($p->ancfirst->{$factor} == 'positive')){
			$risk->risks[$factor] = true;
		}
		// from ANC Follow
		foreach($p->ancfollow as $x){
			if(isset($x->{$factor}) && ($x->{$factor} == 'positive')){
				$risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		/*
		 * Physical exam factors
		 */
		$factor = 'Q_HEIGHT';
		$risk->risks[$factor] = false;
		// from ANC First
		if(isset($p->ancfirst->{$factor}) && ($p->ancfirst->{$factor} == 'below150')){
			$risk->risks[$factor] = true;
		}
		// from ANC Follow
		foreach($p->ancfollow as $x){
			if(isset($x->{$factor}) && ($x->{$factor} == 'below150')){
				$risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		$factor = "Q_BLOODPRESSURE";
		$risk->risks[$factor] = false;
		// from ANC First
		if(isset($p->ancfirst->Q_SYSTOLICBP) && isset($p->ancfirst->Q_DIASTOLICBP) 
											&& (($p->ancfirst->Q_SYSTOLICBP >= 140) 
												|| ($p->ancfirst->Q_SYSTOLICBP <= 90)
												|| ($p->ancfirst->Q_DIASTOLICBP >= 90)
												|| ($p->ancfirst->Q_DIASTOLICBP <= 60))){
			$risk->risks[$factor] = true;
		}
		// from ANC Follow
		foreach($p->ancfollow as $x){
			if(isset($x->Q_SYSTOLICBP) && isset($x->Q_DIASTOLICBP)
											&& (($x->Q_SYSTOLICBP >= 140) 
											|| ($x->Q_SYSTOLICBP <= 90)
											|| ($x->Q_DIASTOLICBP >= 90)
											|| ($x->Q_DIASTOLICBP <= 60))){
				$risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		$factor = 'Q_PALLORANEMIA';
		$risk->risks[$factor] = false;
		// from ANC First
		if(isset($p->ancfirst->{$factor}) && ($p->ancfirst->{$factor} == 'pallor')){
			$risk->risks[$factor] = true;
		}
		// from ANC Follow
		foreach($p->ancfollow as $x){
			if(isset($x->{$factor}) && ($x->{$factor} == 'pallor')){
				$risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		$factor = 'Q_CARDIACPULSE';
		$risk->risks[$factor] = false;
		// from ANC First
		if(isset($p->ancfirst->{$factor}) && (($p->ancfirst->{$factor} >= 100) || ($p->ancfirst->{$factor} <= 50))){
			$risk->risks[$factor] = true;
		}
		// from ANC Follow
		foreach($p->ancfollow as $x){
			if(isset($x->{$factor}) && (($x->{$factor} >= 100) || ($x->{$factor} <= 50))){
				$risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		$factor = 'Q_PRESENTATION';
		$risk->risks[$factor] = false;
		// from ANC First
		if(isset($p->ancfirst->{$factor}) && (($p->ancfirst->{$factor} == 'breech') || ($p->ancfirst->{$factor} == 'transverse'))){
			$risk->risks[$factor] = true;
		}
		// from ANC Follow
		foreach($p->ancfollow as $x){
			if(isset($x->{$factor}) && (($x->{$factor} == 'breech') || ($x->{$factor} == 'transverse'))){
				$risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		$factor = 'Q_FETALHEARTRATEAUDIBLE';
		$risk->risks[$factor] = false;
		// from ANC First
		if(isset($p->ancfirst->{$factor}) && ($p->ancfirst->{$factor} == 'notaudible')){
			$risk->risks[$factor] = true;
		}
		// from ANC Follow
		foreach($p->ancfollow as $x){
			if(isset($x->{$factor}) && ($x->{$factor} == 'notaudible')){
				$risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		/*
		 * Test results
		 * TODO - add to lang file
		 */
		$factor = 'Q_URINEPROTEIN';
		$risk->risks[$factor] = false;
		// from ANC Lab Test
		foreach($p->anclabtest as $x){
			/* TODO if(isset($x->{$factor}) && ($x->{$factor} == 'notaudible')){
				$risk->risks[$factor] = true;
			}*/
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		$factor = 'Q_URINEGLUCOSE';
		$risk->risks[$factor] = false;
		// from ANC Lab Test
		foreach($p->anclabtest as $x){
			/* TODO if(isset($x->{$factor}) && ($x->{$factor} == 'notaudible')){
			 $risk->risks[$factor] = true;
			}*/
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		$factor = 'Q_SYPHILIS';
		$risk->risks[$factor] = false;
		// from ANC Lab Test
		foreach($p->anclabtest as $x){
			if(isset($x->{$factor}) && ($x->{$factor} == 'positive')){
			 $risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		$factor = 'Q_HEMOGLOBINLEVEL';
		$risk->risks[$factor] = false;
		// from ANC Lab Test
		foreach($p->anclabtest as $x){
			if(isset($x->{$factor}) && ($x->{$factor} < 12)){
				$risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		$factor = 'Q_HEMATOCRITLEVEL';
		$risk->risks[$factor] = false;
		// from ANC Lab Test
		foreach($p->anclabtest as $x){
			if(isset($x->{$factor}) && ($x->{$factor} < 38)){
				$risk->risks[$factor] = true;
			}
		}
		if($risk->risks[$factor] == true){
			$risk->count++;
		}
		
		// work out the risk category
		$risk->category = 'none';
		
		// unavoidable risk
		//if no other risks and first order birth
		if($risk->count == 0){
			// from ANC First
			if(isset($p->ancfirst->Q_GRAVIDA) && ($p->ancfirst->Q_GRAVIDA == 1)){
				$risk->category = 'unavoidable';
			}
			// from Transfer
			foreach($p->anctransfer as $x){
				if(isset($x->Q_GRAVIDA) && ($x->Q_GRAVIDA == 1)){
					$risk->category = 'unavoidable';
				}
			}	
		}
		
		// single
		if ($risk->count == 1){
			$risk->category = 'single';
		}
		// multiple
		if ($risk->count > 1){
			$risk->category = 'multiple';
		}
		
		return $risk;
	}
}	
?>