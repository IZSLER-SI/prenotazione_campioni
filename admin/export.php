<?php  

include(dirname(__FILE__).'/header.php');
include(dirname(__FILE__).'/user_session_check.php');
	include (dirname(dirname(__FILE__)).'/objects/class_users.php');
	include (dirname(dirname(__FILE__)).'/objects/class_services.php');
	include (dirname(dirname(__FILE__)).'/objects/class_services_addon.php');
	include (dirname(dirname(__FILE__)).'/objects/class_services_methods.php');
	include (dirname(dirname(__FILE__)).'/objects/class_booking.php');
	
	$con = new prenotazione_campioni_db();
	$conn = $con->connect();
	$users = new prenotazione_campioni_users();
	$users->conn = $conn;
	$objservice = new prenotazione_campioni_services();
	$objservice->conn = $conn;
	$serviceaddon = new prenotazione_campioni_services_addon();
	$serviceaddon->conn = $conn;
	$servicemethod = new prenotazione_campioni_services_methods();
	$servicemethod->conn = $conn;
	$booking = new prenotazione_campioni_booking();
	$booking->conn = $conn;
?>
<div id="cta-export-details" class="panel tab-content">
	<div class="panel panel-default">
		<div class="panel-heading">
		</div>
		<div class="panel-body">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#booking-info-export"><?php echo 'Esportazione prenotazioni';?></a></li>
			</ul>
			
			<div class="tab-content">
				<!-- booking infomation export -->
				<div id="booking-info-export" class="tab-pane fade in active">
					<h3><?php echo 'Esportazione prenotazioni';?></h3>
					<div id="accordion" class="panel-group">
						<form id="" name="" class="" method="post">
							<hr id="hr" />
							<div class="table-responsive">
								<table id="booking-info-table" class="table table-striped table-bordered dt-responsive nowrap">
									<thead>
										<tr>	
											<th><?php echo 'Sede di consegna';?></th>
											<th><?php echo 'Laboratorio';?></th>
											<th><?php echo 'Tipo prenotazione';?></th>
           <th data-sort='YYYYMMDDhhmmss'><?php echo 'Data prenotazione';?></th>
											<th>Codice prenotazione</th>
											<th><?php echo 'Finalità';?></th>
											<th><?php echo 'Campioni';?></th>
           <th><?php echo 'Numero Campioni';?></th>
											<th><?php echo 'Matrice';?></th>
											<th><?php echo 'Prove';?></th>
											<th><?php echo 'Dettagli prenotazione manuale';?></th>
											<th><?php echo 'Cliente';?></th>
											<th><?php echo 'Telefono';?></th>
											<!-- <th><?php //echo 'Indirizzo';?></th>
											<th><?php //echo 'Stato';?></th> -->
										</tr>
									</thead>
									<tbody>
									<?php
                                    $id_lab = $objservice->get_lab_user();

									$display_booking = $booking->get_all_bookings($id_lab);
									$i = 1;
									while($row2=mysqli_fetch_array($display_booking)){
										$objservice->id=$row2['service_id'];
										$display_ser=$objservice->readone();
										
										$users->id=$row2['client_id'];										
										if($row2['booking_status']=='A'){
											$booking_stats='Attivo';
										}elseif($row2['booking_status']=='C'){
											$booking_stats='Confermato';
										}elseif($row2['booking_status']=='R'){
											$booking_stats='Rifiutato';
										}elseif($row2['booking_status']=='RS'){
											$booking_stats='Spostato';
										}elseif($row2['booking_status']=='CC'){
											$booking_stats='Rimosso dal cliente';
										}elseif($row2['booking_status']=='CS'){
											$booking_stats=$label_language_values['cancelled_by_service_provider'];
										}elseif($row2['booking_status']=='CO'){
											$booking_stats='Completato';
										}else{
											$row2['booking_status']=='MN';
											$booking_stats='Occupato';
										}
										$date= new DateTime($row2['booking_date_time']);

                                    ?>
										<tr>	
											<td><?php echo $row2['struttura']; ?></td>
											<td><?php echo $row2['laboratorio']; ?></td>
											<td><?php echo $row2['tipo_prenotazione']; ?></td>
          	<td><?php echo $date->format('d/m/Y H:i:s');  ?></td>
											<td><?php echo $row2['order_id'];?></td>
											<td><?php echo $row2['finalita']; ?></td>
											<td><?php echo $row2['campione']; ?></td>
          	<td><?php echo $row2['n_campione']; ?></td>
											<td><?php echo $row2['matrice']; ?></td>
											<td><?php echo $row2['prove']; ?></td>
											<td><?php echo $row2['text']; ?></td>
											<td>
											<?php 
											$client_name = $row2['first_name'].' '.$row2['last_name'];
											echo $client_name;
											?>
											</td>
											<td><?php echo $row2['phone']; ?></td>
									<?php 
									$i++;}
									?>
									</tbody>
								</table>
								<div id="booking-addOns" class="modal fade booking-details-modal">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title"><?php echo $label_language_values['addons_bookings'];?></h4>
										</div>
										<div class="modal-body">
										<div class="table-responsive">
											<table id="table-booking-addons" class="display table table-striped table-bordered" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th>#</th>
														<th style="width: 48px !important;"><?php echo $label_language_values['serviceaddons_name'];?></th>
														<th style="width: 73px !important;"><?php echo $label_language_values['service_rate'];?></th>
														<th style="width: 39px !important;"><?php echo $label_language_values['service_quantity'];?></th>
													</tr>
												</thead>
												<tbody id="display_booking_addons">
												</tbody>
											</table>
										</div>
										
										</div>
									</div>
								</div>
							</div>
							<div id="booking-methods" class="modal fade booking-details-modal">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title"><?php echo $label_language_values['methods_booking'];?></h4>
										</div>
										<div class="modal-body">
										<div class="table-responsive">
											<table id="table-booking-method" class="display table table-striped table-bordered" cellspacing="0" width="100%">
													<thead>
														<tr>
															<tr>
															<th style="width: 9px !important;">#</th>
															<th style="width: 48px !important;"><?php echo $label_language_values['method_title'];?></th>
															<th style="width: 73px !important;"><?php echo $label_language_values['method_unit_title'];?></th>
															<th style="width: 48px !important;"><?php echo $label_language_values['method_unit_quantity'];?></th>
															<th style="width: 48px !important;"><?php echo $label_language_values['method_unit_quantity_rate'];?></th>
														</tr>
														</tr>
													</thead>
													<tbody id="display_booking_method">
													</tbody>
											</table>
										</div>
										
										</div>
									</div>
								</div>
							</div>
							</div>	
						</form>	
					</div>
				</div>
				<!-- service provicer information export -->
				<!-- services  infomation export -->
			</div>
		</div>
	</div>	
</div>	
 
		
<?php  
	include(dirname(__FILE__).'/footer.php');
?>