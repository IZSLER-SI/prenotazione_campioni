<?php  

class prenotazione_campioni_booking{
	public $booking_date_time;
	public $booking_date_time_end;
	public $method_id;
	public $method_unit_id;
	public $method_unit_qty;
	public $method_unit_qty_rate;
	public $addons_service_id;
	public $addons_service_qty;
	public $addons_service_rate; 
	public $booking_id;
	public $location_id;
	public $order_id;
	public $client_id;
	public $provider_id;
	public $service_id;
	public $booking_price;
	public $booking_start_datetime;
	public $booking_end_datetime;
	public $booking_status;
	public $reject_reason;
	public $cancel_reason;
	public $reminder_status;
	public $lastmodify;
	public $read_status;
	public $user_id;
	public $startdate;
	public $enddate;
	public $order_date;
	public $start_date;
	public $staff_id;
	public $end_date;
	public $id;
	public $conn;
	public $offset;
	public $limit;
	public $id_finalita;
	public $id_prove;
	public $matrice;
	public $id_struttura;
	public $tipo_prenotazione;
	public $id_campione;
	public $id_specie;
	public $n_campione;
	public $text;
	public $id_categoria_matrice;
	public $id_laboratorio;
	public $table_name="ct_bookings";
	public $tablename1="ct_services";
	public $tablename2="ct_order_client_info";
	public $tablename3="ct_users";
	public $tablename4="ct_payments";
	public $tablename5="ct_booking_addons";
	public $table_staff_status="ct_staff_status";
	public $convocazione_perito;
	public $unica_istanza;
	public $all_case;
	
	/*
	* Function for add Booking
	*
	*/
	public function add_booking(){
		$this->service_id															= !empty($this->service_id) ? "'$this->service_id'"  : "NULL";
		$this->order_id																	= !empty($this->order_id) ? "'$this->order_id'"  : "NULL";
		$this->client_id                = !empty($this->client_id) ? "'$this->client_id'"  : "NULL";  
		$this->order_date               = !empty($this->order_date) ? "'$this->order_date'"  : "NULL";   
		$this->booking_date_time        = !empty($this->booking_date_time) ? "'$this->booking_date_time'"  : "NULL";          
		$this->booking_status           = !empty($this->booking_status) ? "'$this->booking_status'"  : "NULL";       
		$this->lastmodify               = !empty($this->lastmodify) ? "'$this->lastmodify'"  : "NULL";   
		$this->read_status              = !empty($this->read_status) ? "'$this->read_status'"  : "NULL";    
		$this->tipo_prenotazione        = !empty($this->tipo_prenotazione) ? "'$this->tipo_prenotazione'"  : "NULL";          
		$this->id_finalita               = !empty($this->id_finalita) ? "'$this->id_finalita'"  : "NULL";    
		$this->id_struttura             = !empty($this->id_struttura) ? "'$this->id_struttura'"  : "NULL";     
		$this->matrice                  = !empty($this->matrice) ? mysqli_real_escape_string($this->conn,$this->matrice)  : "NULL";
		$this->id_campione              = !empty($this->id_campione) ? "'$this->id_campione'"  : "NULL";    
		$this->n_campione               = !empty($this->n_campione) ? "'$this->n_campione'"  : "NULL";   
		$this->id_specie                = !empty($this->id_specie) ? "'$this->id_specie'"  : "NULL";  
		$this->booking_date_time_end    = !empty($this->booking_date_time_end) ? "'$this->booking_date_time_end'"  : "NULL";              
		$this->text                   		= !empty($this->text) ? mysqli_real_escape_string($this->conn,$this->text)  : "NULL";
		$this->id_laboratorio            = !empty($this->id_laboratorio) ? "'$this->id_laboratorio'"  : "NULL";
		$this->id_categoria_matrice					= !empty($this->id_categoria_matrice) ? "'$this->id_categoria_matrice'"  : "NULL";  
		$this->all_case																	= !empty($this->all_case) ? "'$this->all_case'"  : "NULL";   
		$this->convocazione_perito						= !empty($this->convocazione_perito) ? "'$this->convocazione_perito'"  : "NULL";   	  
		$this->unica_istanza												= !empty($this->unica_istanza) ? "'$this->unica_istanza'"  : "NULL";   	  
		$query = "
		insert into " . $this->table_name . " (
			id,
			order_id,
			client_id,
			order_date,
			booking_date_time,
			service_id,
			method_id,
			method_unit_id,
			method_unit_qty,
			method_unit_qty_rate,
			booking_status,
			reject_reason,reminder_status,
			lastmodify,
			read_status,
			staff_ids,
			gc_event_id,
			gc_staff_event_id,
			tipo_prenotazione,
			id_finalita,
			id_struttura,
			matrice,
			id_campione,
			n_campione,
			id_specie,
			booking_date_time_end,
			text,
			id_laboratorio,
			id_categoria_matrice,
			all_case,
			convocazione_perito,
			unica_istanza
			)values(
				NULL,
				" . $this->order_id . ",
				" . $this->client_id . ",
				" . $this->order_date . ",
				" . $this->booking_date_time . ",
				" . $this->service_id . ",
				null,
				null,
				null,
				null,
				" . $this->booking_status . ",
				null,
				'0',
				" . $this->lastmodify . ",
				" . $this->read_status . ",
				null,
				null,
				null,
				" . $this->tipo_prenotazione . ",
				" . $this->id_finalita . ",
				" . $this->id_struttura . ",
				'" . $this->matrice . "',
				" . $this->id_campione . ",
				" . $this->n_campione . ",
				" . $this->id_specie . ",
				" . $this->booking_date_time_end . ",
				'" . $this->text . "',
				" . $this->id_laboratorio . ",
				" . $this->id_categoria_matrice . ",
				" . $this->all_case . ",
				" . $this->convocazione_perito . ",
				" . $this->unica_istanza . "
			)";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_insert_id($this->conn);
		return $value;
	}
	/**/
	public function add_addons_booking(){
		$query="insert into `".$this->tablename5."` (`id`,`order_id`,`service_id`,`addons_service_id`,`addons_service_qty`,`addons_service_rate`) values(NULL,'".$this->order_id."','".$this->service_id."','".$this->addons_service_id."','".$this->addons_service_qty."','".$this->addons_service_rate."')";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_insert_id($this->conn);	
		return $value;
	}
	/*
	* Function for Update Booking
	*
	*/
	public function update(){
		$query="update `".$this->table_name."` set `order_id`='".$this->order_id."',`business_id`='".$this->business_id."',`client_id`='".$this->client_id."',`service_id`='".$this->service_id."',`provider_id`='".$this->provider_id."',`booking_price`='".$this->booking_price."',`booking_datetime`='".$this->booking_datetime."',`booking_endtime`='".$this->booking_endtime."',`booking_status`='".$this->booking_status."',`reject_reason`='".$this->reject_reason."',`cancel_reason`='".$this->cancel_reason."',`reminder`='".$this->reminder."',`lastmodify`='".$this->lastmodify."' where `id`='".$this->booking_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/*
	* Function for Read All Booking
	*
	*/
	public function readall(){
		$query="select * from `".$this->table_name."`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function getallbookings($start_date = "",$end_date = ""){
		$date_check = "";
		if($start_date != "" && $end_date != ""){
			$sdate = $start_date." 00:00:00";
			$edate = $end_date." 23:59:59";
			$date_check = " where `b`.`booking_date_time` between '".$sdate."' and '".$edate."'";
		}
		$query = "SELECT `b`.`order_id`, `b`.`booking_status`, `b`.`client_id`, `b`.`booking_date_time`, `s`.`color`, `s`.`title` FROM `ct_bookings` as `b`,`ct_services` as `s`
		".$date_check." GROUP BY `b`.`order_id`, `b`.`booking_status`, `b`.`client_id`, `b`.`booking_date_time`, `s`.`color`, `s`.`title` ORDER BY `b`.`order_id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}

	public function getallprenotazioni(){
		$query = 'select ct_bookings.order_id,
		izler_finalita.descrizione,
		izler_strutture.descrizione,
		matrice,
		izler_specie.descrizione,
		ct_bookings.tipo_prenotazione,
		ct_bookings.n_campione,
		izler_campione.descrizione,
		group_concat(izler_prove.descrizione separator '|')
		from ct_bookings
					left join izler_finalita on (ct_bookings.id_finalita = izler_finalita.id)
					left join izler_strutture on (ct_bookings.id_struttura = izler_strutture.id)
					left join izler_specie on (ct_bookings.id_specie = izler_specie.id)
					left join izler_campione on (ct_bookings.id_campione = izler_campione.id)
					left join izler_prove_bookings on (ct_bookings.order_id = izler_prove_bookings.id_ordine)
					left join izler_prove on (izler_prove_bookings.id_prova = izler_prove.id)
		group by ct_bookings.order_id';
			$result=mysqli_query($this->conn,$query);
			return $result;
		}
	/*
	* Function for Read One Booking
	*
	*/
	public function readone(){
		$query="select * from `".$this->table_name."` where `id`='".$this->booking_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value;
	}
	public function readone_order_date_time(){
		$query="select `booking_date_time` from `".$this->table_name."` where `order_id`='".$this->booking_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value["booking_date_time"];
	}
	public function get_staff_readone($staff_id){
		$query="select `staff_ids` from `".$this->table_name."` where `id`='".$staff_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value;
  }
	/*Function to Get Last order id from booking table used in front end for add cart item in booking table*/
	public function last_booking_id(){
		$query="select max(`order_id`) from `".$this->table_name."`";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value[0];
	}
	public function confirm_booking(){
		$query="update `".$this->table_name."` set `booking_status`='".$this->booking_status."' where `id`='".$this->booking_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function confirm_booking_api(){
		$query="update `".$this->table_name."` set `booking_status`='".$this->booking_status."',`lastmodify`='".$this->lastmodify."' where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function update_reject_status(){
		$query="update `".$this->table_name."` set `booking_status`='R',`read_status`='U',`lastmodify`='".$this->lastmodify."',`reject_reason`='".$this->reject_reason."' where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function insert_override_campioni($values){
		$query="select id from izler_override_campioni where date = '$values->date' and id_laboratorio = $values->lab";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$id=mysqli_fetch_row($result)[0];
			$query = "UPDATE `izler_override_campioni` SET `n_campioni`=$values->n_campioni WHERE `id`=$id";
			$result=mysqli_query($this->conn,$query);
		}else{
			$query = "INSERT INTO izler_override_campioni (`date`,`n_campioni`,`id_laboratorio`) VALUES ('".$values->date."',".$values->n_campioni.",".$values->lab.")";
			$result=mysqli_query($this->conn,$query);
		}
	}
	public function insert_override_campioni_conoscitivi($values){
		$query="select id from izler_override_campioni_conoscitivo where date = '$values->date' and id_laboratorio = $values->lab";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$id=mysqli_fetch_row($result)[0];
			$query = "UPDATE `izler_override_campioni_conoscitivo` SET `n_campioni`=$values->n_campioni WHERE `id`=$id";
			$result=mysqli_query($this->conn,$query);
		}else{
			$query = "INSERT INTO izler_override_campioni_conoscitivo (`date`,`n_campioni`,`id_laboratorio`) VALUES ('".$values->date."',".$values->n_campioni.",".$values->lab.")";
			$result=mysqli_query($this->conn,$query);
		}
	}
  public function insert_override_campioni_chimici($values){
    $query="select id from izler_override_campioni_chimici where date = '$values->date' and id_laboratorio = $values->lab";
    $result=mysqli_query($this->conn,$query);
    if(mysqli_num_rows($result)>0){
      $id=mysqli_fetch_row($result)[0];
      $query = "UPDATE `izler_override_campioni_chimici` SET `n_campioni`=$values->n_campioni WHERE `id`=$id";
      $result=mysqli_query($this->conn,$query);
    }else{
      $query = "INSERT INTO izler_override_campioni_chimici (`date`,`n_campioni`,`id_laboratorio`) VALUES ('".$values->date."',".$values->n_campioni.",".$values->lab.")";
      $result=mysqli_query($this->conn,$query);
    }
  }
	/* Used in booking_ajax */
	public function count_order_id_bookings(){
		$query="select count(`order_id`) as `ordercount` from `".$this->table_name."` where `id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	/* used in booking_ajax */
	public function delete_booking(){
		$query="delete from `".$this->table_name."` where `id`='".$this->booking_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* used for delete appointments in booking_ajax */
	public function delete_appointments(){
		$query="delete `ct_bookings`.*,`ct_payments`.*,`ct_order_client_info`.* from `ct_bookings` INNER JOIN `ct_payments`,`ct_order_client_info` where `ct_bookings`.`order_id`=`ct_payments`.`order_id` and `ct_bookings`.`order_id`=`ct_order_client_info`.`order_id` and `ct_bookings`.`order_id`='".$this->order_id."' ";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* thi smethod is used in export page to list all bookings */
	public function get_all_bookings($lab_id) {
		$query = "
		select group_concat(distinct izler_laboratori.descrizione separator '|') as laboratorio,ct_bookings.*,
		izler_finalita.descrizione as finalita,
		izler_strutture.descrizione as struttura,
		matrice,
		izler_specie.descrizione as specie,
		izler_campione.descrizione as campione,       
		group_concat(distinct izler_prove.descrizione separator '<br>') as prove,
							ct_users.*
		from ct_bookings
					left join izler_finalita on (ct_bookings.id_finalita = izler_finalita.id)
					left join izler_strutture on (ct_bookings.id_struttura = izler_strutture.id)
					left join izler_specie on (ct_bookings.id_specie = izler_specie.id)
					left join izler_campione on (ct_bookings.id_campione = izler_campione.id)
					left join izler_prove_bookings on (ct_bookings.order_id = izler_prove_bookings.id_ordine)
					left join izler_prove on (izler_prove_bookings.id_prova = izler_prove.id)
					left join ct_users on (ct_bookings.client_id = ct_users.id)
					left join izler_laboratori on (
						ct_bookings.id_laboratorio = izler_laboratori.id
					) where ct_bookings.record_attivo = 1
		        ";
		if ($lab_id != 0) {
			$query .= " and id_laboratorio= " . $lab_id;
		}
		$query .= " group by ct_bookings.order_id
		";

		$query .= "
		union 
		select group_concat(distinct izler_laboratori.descrizione separator '|') as laboratorio,ct_bookings.*,
		izler_finalita.descrizione as finalita,
		izler_strutture.descrizione as struttura,
		matrice,
		izler_specie.descrizione as specie,
		izler_campione.descrizione as campione,
		group_concat(distinct izler_prove.descrizione separator '<br>') as prove,
		ct_users.*
		from ct_bookings
					left join izler_finalita on (ct_bookings.id_finalita = izler_finalita.id)
					left join izler_strutture on (ct_bookings.id_struttura = izler_strutture.id)
					left join izler_specie on (ct_bookings.id_specie = izler_specie.id)
					left join izler_campione on (ct_bookings.id_campione = izler_campione.id)
					left join izler_prove_bookings on (ct_bookings.order_id = izler_prove_bookings.id_ordine)
					left join izler_prove on (izler_prove_bookings.id_prova = izler_prove.id)
					left join ct_users on (ct_bookings.client_id = ct_users.id)
		   join izler_laboratori_strutture on (
						ct_bookings.id_struttura = izler_laboratori_strutture.id_izler_struttura
		    and izler_laboratori_strutture.record_attivo = 1
					)
				 left join izler_laboratori on (
						ct_bookings.id_laboratorio = izler_laboratori.id
					)where ct_bookings.record_attivo = 1
					";
		if ($lab_id != 0) {
			$query .= " and izler_laboratori_strutture.id_izler_laboratori= " . $lab_id;
		}
		$query .= " group by ct_bookings.order_id
		";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* get all bookings details from the order_id */
  public function get_detailsby_order_id($orderid){
		$query = "select `b`.`booking_status`, `b`.`client_id`,`b`.`reject_reason`,`b`.`staff_ids`,`b`.`gc_event_id`,`b`.`gc_staff_event_id`,`b`.`booking_date_time`,`s`.`title` as `service_title`,`p`.`net_amount`,`sm`.`method_title`,`oci`.`client_name`,`oci`.`client_email`,`oci`.`client_personal_info`,`p`.`payment_method`,`p`.`frequently_discount`,`oci`.`client_phone`, `oci`.`recurring_id` from `ct_bookings` as `b`,`ct_services` as `s`,`ct_payments` as `p`,`ct_services_method` as `sm`,`ct_order_client_info` as `oci` where `b`.`service_id` = `s`.`id` and `b`.`order_id` = `p`.`order_id` and `b`.`method_id` = `sm`.`id` and `b`.`order_id` = '".$orderid."' and `b`.`order_id` = `oci`.`order_id` GROUP BY `b`.`id`, `b`.`order_id`, `b`.`client_id`, `b`.`order_date`, `b`.`booking_date_time`, `b`.`service_id`, `b`.`method_id`, `b`.`method_unit_id`, `b`.`method_unit_qty`, `b`.`method_unit_qty_rate`, `b`.`booking_status`, `b`.`reject_reason`, `b`.`reminder_status`, `b`.`lastmodify`, `b`.`read_status`, `b`.`staff_ids`, `b`.`gc_event_id`, `b`.`gc_staff_event_id`,`s`.`id`, `s`.`title`, `s`.`description`, `s`.`color`, `s`.`image`, `s`.`status`, `s`.`position`,`p`.`id`, `p`.`order_id`, `p`.`payment_method`, `p`.`transaction_id`, `p`.`amount`, `p`.`discount`, `p`.`taxes`, `p`.`partial_amount`, `p`.`payment_date`, `p`.`net_amount`, `p`.`lastmodify`, `p`.`frequently_discount`, `p`.`frequently_discount_amount`, `sm`.`id`, `sm`.`service_id`, `sm`.`method_title`, `sm`.`status`, `sm`.`position`, `oci`.`id`, `oci`.`order_id`, `oci`.`client_name`, `oci`.`client_email`, `oci`.`client_phone`, `oci`.`client_personal_info`, `oci`.`recurring_id`";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
  }
  /* CODE FOR DISPLAY DETAIL IN POPUP */
  public function get_booking_details_appt($orderid){
		$query = "select                 				
		group_concat(distinct izler_laboratori.descrizione separator '|') as laboratorio,ct_bookings.*,
		izler_finalita.descrizione as finalita,
		izler_strutture.descrizione as struttura,
		matrice,
		izler_specie.descrizione as specie,
		izler_campione.descrizione as campione,
		group_concat(distinct izler_prove.descrizione separator '|') as prove,
		all_case,
							ct_users.*
		from ct_bookings
					left join izler_finalita on (ct_bookings.id_finalita = izler_finalita.id)
					left join izler_strutture on (ct_bookings.id_struttura = izler_strutture.id)
					left join izler_specie on (ct_bookings.id_specie = izler_specie.id)
					left join izler_campione on (ct_bookings.id_campione = izler_campione.id)
					left join izler_prove_bookings on (ct_bookings.order_id = izler_prove_bookings.id_ordine)
					left join izler_prove on (izler_prove_bookings.id_prova = izler_prove.id)
					left join ct_users on (ct_bookings.client_id = ct_users.id)
																										left join izler_mapping on (
																														izler_strutture.id = izler_mapping.id_izler_struttura and
																														izler_finalita.id = izler_mapping.id_izler_finalita and
																														izler_prove_bookings.id_prova = izler_mapping.id_izler_prove
																										)
																										left join izler_laboratori on izler_mapping.id_izler_laboratorio = izler_laboratori.id
							where ct_bookings.order_id = " . $orderid . "
				group by ct_bookings.order_id";
			$result=mysqli_query($this->conn,$query);
			$value=mysqli_fetch_assoc($result);
			return $value;
  }
	/* CODE FOR DISPLAY DETAIL IN POPUP API Function */
	public function get_booking_details_appt_api($orderid)    {
		$query = "select `b`.`booking_status`,`b`.`booking_date_time`,`p`.`net_amount`,`oci`.`client_name`,`oci`.`client_email`,`oci`.`client_personal_info`,`p`.`payment_method`,`oci`.`client_phone`,`s`.`title` as `service_title`,`b`.`gc_event_id` ,`b`.`gc_staff_event_id` ,`b`.`staff_ids` from `ct_bookings` as `b`,`ct_payments` as `p`,`ct_order_client_info` as `oci`,`ct_services` as `s`where `b`.`order_id` = `p`.`order_id`and `b`.`order_id` = '" . $orderid . "' and `b`.`order_id` = `oci`.`order_id` and `b`.`service_id` = `s`.`id` GROUP BY `b`.`booking_status`,`b`.`booking_date_time`,`p`.`net_amount`,`oci`.`client_name`,`oci`.`client_email`,`oci`.`client_personal_info`,`p`.`payment_method`,`oci`.`client_phone`,`s`.`title`,`b`.`gc_event_id` ,`b`.`gc_staff_event_id` ,`b`.`staff_ids`";
		$result = mysqli_query($this->conn, $query);
		$value = mysqli_fetch_array($result);
		return $value;   
	}
	/* CODE FOR DISPLAY DETIAL IN POPUP  END */
	public function getdatabyorder_id($orderid){
			$query = "select * from `ct_bookings` where `order_id` = '".$orderid."'";
			$result=mysqli_query($this->conn,$query);
			return $result;
	}
	/* get all methods and units of the bookings */
	public function get_methods_ofbookings($orderid){
		$query = "select `b`.`method_unit_qty` as `qtys`,`sm`.*,`smu`.* from `ct_bookings` as `b`,`ct_services_method` as `sm`,`ct_service_methods_units` as `smu` where `b`.`method_id` = `sm`.`id` and `b`.`method_unit_id` = `smu`.`id` and `b`.`order_id` ='".$orderid."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function get_izsler($orderid){
		$query = "select `b`.`matrice`,`b`.`id_finalita`,`b`.`id_prove`,`b`.`method_unit_qty` as `qtys`,`sm`.*,`smu`.* from `ct_bookings` as `b`,`ct_services_method` as `sm`,`ct_service_methods_units` as `smu` where `b`.`method_id` = `sm`.`id` and `b`.`method_unit_id` = `smu`.`id` and `b`.`order_id` ='".$orderid."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value;
	}
	/* get all addons services of bookings */
	public function get_addons_ofbookings($orderid){
		$query = "select `ba`.*,`sa`.* from `ct_bookings` as `b`,`ct_booking_addons` as `ba`,`ct_services_addon` as `sa` where `b`.`order_id` = `ba`.`order_id` and `ba`.`addons_service_id` = `sa`.`id` and `b`.`order_id` = '".$orderid."' GROUP BY `sa`.`id`, `sa`.`service_id`, `sa`.`addon_service_name`, `sa`.`base_price`, `sa`.`maxqty`, `sa`.`image`, `sa`.`multipleqty`, `sa`.`status`, `sa`.`position`, `sa`.`predefine_image`, `sa`.`predefine_image_title`, `ba`.`id`, `ba`.`order_id`, `ba`.`service_id`, `ba`.`addons_service_id`, `ba`.`addons_service_qty`, `ba`.`addons_service_rate`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/*Use function for Invoice Purpose*/
	public function get_details_for_invoice_client(){
		$query="select `b`.`order_id` as `invoice_number`,`b`.`booking_date_time` as `start_time`,`b`.`order_date` as `invoice_date`,`b`.`service_id` as `sid`,`b`.`client_id` as `cid` from `ct_bookings` as `b`,`ct_payments` as `p`,`ct_order_client_info` as `oc` where `b`.`order_id`='".$this->order_id."' and `b`.`order_id`=`p`.`order_id` and `b`.`order_id`=`oc`.`order_id` ";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value;
	}
	/* Get Client Info from user table */	
	public function get_client_info(){
		$query="select * from `".$this->tablename3."` where `id`='".$this->client_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value;
	}
	/* Booking readall */
	public function readall_bookings(){
		$query="select * from `".$this->table_name."` where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function email_reminder(){
		$query="select * from `".$this->table_name."` where (`reminder_status`='0' OR `reminder_status`='') and `booking_status`='C' GROUP BY `id`, `order_id`, `client_id`, `order_date`, `booking_date_time`, `service_id`, `method_id`, `method_unit_id`, `method_unit_qty`, `method_unit_qty_rate`, `booking_status`, `reject_reason`, `reminder_status`, `lastmodify`, `read_status`, `staff_ids`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function update_reminder_booking($id){
		$query="update `".$this->table_name."` set `reminder_status`='1' where `id`='".$id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
  public function getalldetail_for_reminder($orderid){
		$query="select `s`.`title`,`b`.`booking_date_time`,`oci`.`client_name`,`oci`.`client_email` from `ct_bookings` as `b`,`ct_services` as `s`,`ct_order_client_info` as `oci` where `b`.`order_id` = '".$orderid."' and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `oci`.`order_id` GROUP BY `b`.`id`, `b`.`order_id`, `b`.`client_id`, `b`.`order_date`, `b`.`booking_date_time`, `b`.`service_id`, `b`.`method_id`, `b`.`method_unit_id`, `b`.`method_unit_qty`, `b`.`method_unit_qty_rate`, `b`.`booking_status`, `b`.`reject_reason`, `b`.`reminder_status`, `b`.`lastmodify`, `b`.`read_status`, `b`.`staff_ids`, `b`.`gc_event_id`, `b`.`gc_staff_event_id`,`s`.`id`, `s`.`title`, `s`.`description`, `s`.`color`, `s`.`image`, `s`.`status`, `s`.`position`, `oci`.`id`, `oci`.`order_id`, `oci`.`client_name`, `oci`.`client_email`, `oci`.`client_phone`, `oci`.`client_personal_info`";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value;
  }

		public function get_num_events($date){
			$query="select count(id) as num from ct_bookings	where order_date = '$date'";
			$result=mysqli_query($this->conn,$query);
			$value=mysqli_fetch_row($result)[0];
			return $value;
		}
	public function get_intervall($date){
		$lab_user = $_SESSION['ct_laboratorioid'];
		$query = "
			select day_start_time as start,day_end_time as end from ct_week_days_available 
			join ct_admin_info on ct_week_days_available.provider_id = ct_admin_info.lab
			where ct_admin_info.id = " . $lab_user . " and weekday_id = WEEKDAY('$date')+1";
		$result = mysqli_query($this->conn, $query);
		$value = mysqli_fetch_assoc($result);
		return $value;
	}
		public function getTimeSlot($interval, $start_time, $end_time){
			$start = new DateTime($start_time);
			$end = new DateTime($end_time);
			$startTime = $start->format('H:i');
			$endTime = $end->format('H:i');
			$i = 0;
			$time = [];
			while (strtotime($startTime) <= strtotime($endTime)) {
				$start = $startTime;
				$end = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
				$startTime = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
				$i++;
				if (strtotime($startTime) <= strtotime($endTime)) {
					$time[$i]['slot_start_time'] = $start;
					$time[$i]['slot_end_time'] = $end;
				}
			}
			return $time;
		}
		public function getSlotsOccupati($date,$lab,$time_int = ''){
			$array = [];
			$query 	= "select * from ct_bookings where record_attivo = 1 and tipo_prenotazione != 'autocontrollo' and id_laboratorio = ".$lab." and date(booking_date_time) = '$date'";
			$result = mysqli_query($this->conn,$query);
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					$array[] = [
						'slot_start_time'	=>	date("H:i",strtotime($row['booking_date_time'])),
						'slot_end_time'			=>	!empty($time_int) ? date("H:i",strtotime('+'.$time_int.' minutes',strtotime($row['booking_date_time']))) : date("H:i",strtotime($row['booking_date_time']))
					];
				}
			}
			return $array;  
		}
	public function getNCampioni($date){
		$query="select n_campioni from izler_override_campioni where date = '$date'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		if($result->num_rows != 0){
			return $value['n_campioni'];
		}else{
			return 0;
		}
	}

	public function getNCampioniConoscitivo($date){
		$query="select n_campioni from izler_override_campioni_conoscitivo where date = '$date'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		if($result->num_rows != 0){
			return $value['n_campioni'];
		}else{
			return 0;
		}
	}
  public function getNCampioniChimici($date){
    $query="select n_campioni from izler_override_campioni_chimici where date = '$date'";
    $result=mysqli_query($this->conn,$query);
    $value=mysqli_fetch_array($result);
    if($result->num_rows != 0){
      return $value['n_campioni'];
    }else{
      return 0;
    }
  }
	public function check_for_service_addons_availabilities($sid){
		$query="select count(`a`.`id`) as `count_of_addons` from `ct_services_addon` as `a` where `a`.`service_id` = '$sid'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['count_of_addons'];
  }
	public function check_for_service_units_availabilities($sid){
		$query="select count(`id`) as `count_of_method` from `ct_services_method` where `service_id` = '$sid'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['count_of_method'];
  }
	public function save_staff_to_booking($sid){
		$query="update `".$this->table_name."` set `staff_ids`='".$sid."' where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
  }
	public function fetch_staff_of_booking(){
		$query = "SELECT `staff_ids` FROM `ct_bookings` where `order_id` = '".$this->order_id."' GROUP BY `id`, `order_id`, `client_id`, `order_date`, `booking_date_time`, `service_id`, `method_id`, `method_unit_id`, `method_unit_qty`, `method_unit_qty_rate`, `booking_status`, `reject_reason`, `reminder_status`, `lastmodify`, `read_status`, `staff_ids`, `gc_event_id`, `gc_staff_event_id`";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	function getWeeks($date, $rollover){
		$cut = substr($date, 0, 8);
		$daylen = 86400;
		$timestamp = strtotime($date);
		$first = strtotime($cut . "00");
		$elapsed = ($timestamp - $first) / $daylen;
		$weeks = 1;
		for ($i = 1; $i <= $elapsed; $i++){
				$dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
				$daytimestamp = strtotime($dayfind);
				$day = strtolower(date("l", $daytimestamp));
				if($day == strtolower($rollover))  $weeks ++;
		}
		return $weeks;
	}
	function get_staff_detail_for_email($sid){
		$query="select * from `ct_admin_info` where `id` = '".$sid."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	function get_staff_ids_from_bookings($oid){
		$query="select * from `ct_bookings` where `order_id` = '".$oid."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['staff_ids'];
	}
	function booked_staff_status(){
		$query = "select GROUP_CONCAT(`staff_ids`) as `sc` from `".$this->table_name."` where `booking_date_time` = '".$this->booking_date_time."' and `staff_ids` != ''";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	/* Update GC Event ID */
	function update_gc_event_id($last_id,$gc_event_id) {
		$update_gc_event_query = "update ".$this->table_name." set gc_event_id = '".$gc_event_id."' where order_id = '".$last_id."'";
		$res = mysqli_query($this->conn,$update_gc_event_query);
		return $res;
	}
	function update_gc_staffid_event_id($last_id,$gc_event_id) {
		$update_gc_event_query = "update ".$this->table_name." set gc_staff_event_id = '".$gc_event_id."' where order_id = '".$last_id."'";
		$res = mysqli_query($this->conn,$update_gc_event_query);
		return $res;
	}
 	public function readall_bookings_oid(){
		$query="select * from `".$this->table_name."` where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	public function read_net_amt(){
		$query="select * from ct_payments where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	public function check_for_booking_date_time($booking_date_time,$staff_id){
		$query="select * from ct_bookings where `booking_date_time`='".$booking_date_time."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			return false;
		}else{
			if($staff_id != ''){
				$exploded_staffs = explode(',',$staff_id);
				$i=1;
				foreach($exploded_staffs as $staff){
					$qry="select * from ct_week_days_available where `provider_id`='".$staff."' limit 1";
					$res=mysqli_query($this->conn,$qry);
					if(sizeof((array)$exploded_staffs) == $i){
						if(mysqli_num_rows($res)>0){
							$val = mysqli_fetch_assoc($res);
							if($val['provider_schedule_type'] == 'monthly'){
								$date = date('Y-m-d', strtotime($booking_date_time));
								$date_day = date('l', strtotime($booking_date_time));
								$week_id = $this->getWeeks($date, $date_day);
								$weekday_id = date('N', strtotime($booking_date_time));
								
								$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
								$r=mysqli_query($this->conn,$q);
								if(mysqli_num_rows($r)>0){
									return false;
								}else{
									return true;
								}
							}else{
								$date = date('Y-m-d', strtotime($booking_date_time));
								$week_id = '1';
								$weekday_id = date('N', strtotime($booking_date_time));
								
								$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
								$r=mysqli_query($this->conn,$q);
								if(mysqli_num_rows($r)>0){
									return false;
								}else{
									return true;
								}
							}
						}else{
							$qq="select * from ct_week_days_available where `provider_id`='1' limit 1";
							$rr=mysqli_query($this->conn,$qq);
							if(mysqli_num_rows($rr)>0){
								$val = mysqli_fetch_assoc($rr);
								if($val['provider_schedule_type'] == 'monthly'){
									$date = date('Y-m-d', strtotime($booking_date_time));
									$date_day = date('l', strtotime($booking_date_time));
									$week_id = $this->getWeeks($date, $date_day);
									$weekday_id = date('N', strtotime($booking_date_time));
									
									$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
									$r=mysqli_query($this->conn,$q);
									if(mysqli_num_rows($r)>0){
										return false;
									}else{
										return true;
									}
								}else{
									$date = date('Y-m-d', strtotime($booking_date_time));
									$week_id = '1';
									$weekday_id = date('N', strtotime($booking_date_time));
									
									$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
									$r=mysqli_query($this->conn,$q);
									if(mysqli_num_rows($r)>0){
										return false;
									}else{
										return true;
									}
								}
							}else{
								return false;
							}
						}
					}elseif(mysqli_num_rows($res)>0){
						$val = mysqli_fetch_assoc($res);
						if($val['provider_schedule_type'] == 'monthly'){
							$date = date('Y-m-d', strtotime($booking_date_time));
							$date_day = date('l', strtotime($booking_date_time));
							$week_id = $this->getWeeks($date, $date_day);
							$weekday_id = date('N', strtotime($booking_date_time));
							
							$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
							$r=mysqli_query($this->conn,$q);
							if(mysqli_num_rows($r)>0){
								return false;
							}else{
								return true;
							}
						}else{
							$date = date('Y-m-d', strtotime($booking_date_time));
							$week_id = '1';
							$weekday_id = date('N', strtotime($booking_date_time));
							
							$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
							$r=mysqli_query($this->conn,$q);
							if(mysqli_num_rows($r)>0){
								return false;
							}else{
								return true;
							}
						}
					}else{
						$i++;
						continue;
					}
					$i++;
				}
			}else{
				$qq="select * from ct_week_days_available where `provider_id`='1' limit 1";
				$rr=mysqli_query($this->conn,$qq);
				if(mysqli_num_rows($rr)>0){
					$val = mysqli_fetch_assoc($rr);
					if($val['provider_schedule_type'] == 'monthly'){
						$date = date('Y-m-d', strtotime($booking_date_time));
						$date_day = date('l', strtotime($booking_date_time));
						$week_id = $this->getWeeks($date, $date_day);
						$weekday_id = date('N', strtotime($booking_date_time));
						
						$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
						$r=mysqli_query($this->conn,$q);
						if(mysqli_num_rows($r)>0){
							return false;
						}else{
							return true;
						}
					}else{
						$date = date('Y-m-d', strtotime($booking_date_time));
						$week_id = '1';
						$weekday_id = date('N', strtotime($booking_date_time));
						
						$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
						$r=mysqli_query($this->conn,$q);
						if(mysqli_num_rows($r)>0){
							return false;
						}else{
							return true;
						}
					}
				}else{
					return false;
				}
			}
		}
	}
	public function staff_status_select_staff_id(){
		$query="select `id` from `".$this->table_staff_status."` where `staff_id`='".$this->staff_id."' and  `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		$value = mysqli_fetch_assoc($result);
		return $value['id'];
  }
  public function readone_bookings_details_by_order_id_s_id(){
		$query="select status from `".$this->table_staff_status."` where `order_id`='".$this->order_id."' and `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value = mysqli_fetch_array($result);
		return $value['status'];
	}
	public function readone_bookings_details_by_order_id(){
		$query="select * from `".$this->table_name."` where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	public function update_staff_status(){
		$query="update `".$this->table_staff_status."` set `status`='".$this->status."' where  `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
  }
	public function readone_bookings_sid_staff(){
		$query="select * from `".$this->table_staff_status."` where `id`='".$this->id."' and `status`='".$this->status."' order by order_id DESC limit 1";
	  $result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		/* $id=$value['order_id']; */
		return $value;
	}
	
	public function update_staff_id_bookings_details_by_order_id(){
		$query="update `".$this->table_name."` set `staff_ids`='".$this->staff_id."' where `order_id`='".$this->booking_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;	
	}
	public function staff_status_insert(){
		$query = "INSERT INTO `".$this->table_staff_status."`(`id`,`staff_id`,`order_id`,`status`) VALUES(null,'".$this->staff_id."','".$this->order_id."','D')";
		$result=mysqli_query($this->conn,$query);
		return mysqli_insert_id($this->conn);
	}
	public function staff_status_read_one_by_or_id(){
		$query="SELECT * FROM `".$this->table_staff_status."` WHERE `order_id`='".$this->order_id."' ORDER BY `id` DESC";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function get_all_past_bookings(){
		$query = "SELECT `order_id` FROM `ct_bookings` WHERE `booking_date_time`<='".$this->booking_start_datetime."' GROUP BY `order_id` ORDER BY `order_id` ";
		$result=mysqli_query($this->conn,$query);
		return $result;
  }
	public function get_all_upcoming_bookings(){
	$query = "SELECT `order_id` FROM `ct_bookings` WHERE `booking_date_time`>='".$this->booking_start_datetime."' GROUP BY `order_id` ORDER BY `order_id` ";
	$result=mysqli_query($this->conn,$query);
	return $result;
	}
	/* API Function */
	public function get_all_past_bookings_api(){
		$query  = "SELECT `order_id`,`client_id`,`staff_ids` FROM `ct_bookings` WHERE `booking_date_time`<'" . $this->booking_start_datetime . "' GROUP BY `order_id` ORDER BY `booking_date_time` DESC limit ".$this->limit." offset ".$this->offset;
		$result = mysqli_query($this->conn, $query);
		return $result;
	}
	/* API Function */
	public function get_all_upcoming_bookings_api(){
		$query  = "SELECT `order_id`,`client_id`,`staff_ids` FROM `ct_bookings` WHERE `booking_date_time`>='" . $this->booking_start_datetime . "' GROUP BY `order_id` ORDER BY `booking_date_time` limit ".$this->limit." offset ".$this->offset;
		$result = mysqli_query($this->conn, $query);
		return $result;
	}
	public function complete_booking(){
		$query = "UPDATE `".$this->table_name."` SET `booking_status`='".$this->booking_status."',`lastmodify`='".$this->lastmodify."' WHERE `order_id`='".$this->order_id."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function clone_booking_order_detail(){
		$query = "select * from `".$this->table_name."` where `order_id`='".$this->order_id."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function clone_addon_order_detail(){
		$query = "select * from `".$this->tablename5."` where `order_id`='".$this->order_id."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function clone_order_client_detail(){
		$query = "select * from `".$this->tablename2."` where `order_id`='".$this->order_id."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function clone_payment_order_detail(){
		$query = "select * from `".$this->tablename4."` where `order_id`='".$this->order_id."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function check_booking_by_gc_id($gc_event_id){
		$query = "select `order_id`,`booking_date_time`,`gc_event_id`,`gc_staff_event_id` from `".$this->table_name."` where `gc_event_id`='".$gc_event_id."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function get_all_gc_from_db(){
		$query = "select `order_id`,`staff_ids`,`gc_event_id`,`gc_staff_event_id` from `".$this->table_name."` GROUP BY `order_id`,`staff_ids`,`gc_event_id`,`gc_staff_event_id`";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	/* GET APPOINTMENTS for Admin API*/
	public function get_all_bookings_api(){
		$query="select `p`.`order_id`, `b`.`booking_date_time`, `b`.`booking_status`, `b`.`reject_reason`,`s`.`title`,`p`.`net_amount` as `total_payment`,`b`.`gc_event_id`,`b`.`gc_staff_event_id`,`b`.`staff_ids` from `ct_bookings` as `b`,`ct_payments` as `p`,`ct_services` as `s`,`ct_users` as `u` where `b`.`client_id` = `u`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `p`.`order_id` GROUP BY `p`.`order_id`, `b`.`booking_date_time`, `b`.`booking_status`, `b`.`reject_reason`,`s`.`title`,`p`.`net_amount`,`b`.`gc_event_id`,`b`.`gc_staff_event_id`,`b`.`staff_ids` order by `b`.`order_id` desc limit ".$this->limit." offset ".$this->offset;
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	
	public function get_booking_count_of_staff(){
		$query = "SELECT `staff_ids` FROM ".$this->table_name." GROUP BY `order_id`";
		$result = mysqli_query($this->conn,$query);
		$count_booking = 0;
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				$staff_id_array = explode(",",$row["staff_ids"]);
				if(in_array($this->staff_id,$staff_id_array)){
					$count_booking++;
				}
			}
		}
		return $count_booking;
	}
}
