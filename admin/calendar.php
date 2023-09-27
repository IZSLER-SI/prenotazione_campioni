<?php
ini_set("error_log", __DIR__ . DIRECTORY_SEPARATOR . "error_calendar.log"); // LOG FILE
include(dirname(__FILE__) . '/header.php');
include(dirname(__FILE__) . '/user_session_check.php');
$setting = new prenotazione_campioni_setting();
$setting->conn = $conn;
$gettimeformat = $setting->get_option('ct_time_format');/*CHECK FOR VC AND PARKING STATUS*/
$global_vc_status = $setting->get_option('ct_vc_status');
$global_p_status = $setting->get_option('ct_p_status');/*CHECK FOR VC AND PARKING STATUS END*/
?>
<div id="ct-calendar-all">
	<style>
		.container_margin {
			margin-top: 40px;
			margin-left: 20px;
			margin-right: 20px;
		}
		.modal-details{
			width: 80%;
   margin: 0 auto;
			background: white;
		}
	</style>
	<br>
	<br>
	<script>
let dropdown = $('#select_laboratorio');
const calendar = $('#calendar');
dropdown.empty();
dropdown.prop('selectedIndex', 0);
const url = "../front/get_laboratorio.php?id=<?php
$id_user = null;
if(isset($_SESSION['ct_adminid'])){
				$id_user = $_SESSION['ct_adminid'];
}
if(isset($_SESSION['ct_laboratorioid'])){
				$id_user = $_SESSION['ct_laboratorioid'];
}
if(isset($_SESSION['ct_accettazioneid'])){
				$id_user = $_SESSION['ct_accettazioneid'];
}
				echo $id_user; 
?>";
$.getJSON(url, function(data) {
    console.log(data);
				if (data.length > 1) {
								dropdown.append($('<option></option>').attr('value', 0).text('Tutti i laboratori'));
				}
				$.each(data, function(key, entry) {
								dropdown.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
				})
				changeLab()
});

function changeLab() {
				var lab = dropdown.val()

}
setInterval(function() {
				calendar.fullCalendar('refetchEvents');
				console.log('refetchEvents');
}, 20000);
	</script>


	<div id="calendar" class="ct-booking-calendar"></div>
	<!--    DONT DELETE THIS THIS IS FOR USE-->

	<div id="table-event-details" class="modal fade table-event-details" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-lg vertical-align-center">
				<div class="modal-details">
				<div class="modal-header">
						<button type="button" id="info_modal_close" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body mb-20">
					</div>
				</div>
			</div>
	</div>
	</div>
	
	<div id="booking-details-calendar" class="modal fade booking-details-calendar" tabindex="-1" role="dialog" aria-hidden="true">
		<!-- modal pop up start -->
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-lg vertical-align-center">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" id="info_modal_close" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title"><?php echo 'Dettagli prenotazione'; ?></h4>
					</div>
					<div class="modal-body mb-20">
						<ul class="list-unstyled ct-cal-booking-details">
							<li style="width: 100%;">
								<label style="width: 30%; margin-right: 0;"><?php echo $label_language_values['booking_status']; ?></label>
								<div class="ct-booking-status"></div>
							</li>
							<li class="ct-second-child">
								<i class="fa fa-calendar pull-left mt-2"></i><span class="starttime pull-left"></span> &nbsp;<i class="fa fa-clock-o ml-10 mt-2 pull-left"></i><span class="start_time"></span>
							</li>
							<li>
								<label>Tipo prenotazione</label>
								<span class="prenotazione-html span-scroll"></span>
							</li>
							<li>
								<label>Laboratorio</label>
								<span class="laboratorio-html span-scroll"></span>
							</li>
							<li>
								<label>Struttura</label>
								<span class="struttura-html span-scroll"></span>
							</li>
							<li>
								<label>Matrice</label>
								<span class="matrice-html span-scroll"></span>
							</li>
							<li>
								<label>Finalita</label>
								<span class="finalita-html span-scroll"></span>
							</li>
							<li>
								<label>Campione</label>
								<span class="campione-html span-scroll"></span>
							</li>
							<li>
								<label>Numero campioni</label>
								<span class="ncampione-html span-scroll"></span>
							</li>
							<li>
								<label>Specie</label>
								<span class="specie-html span-scroll"></span>
							</li>
							<li>
								<label>Allevamento/caseificio dei campioni</label>
								<span class="all_case-html span-scroll" style='max-height: 300px;overflow: scroll;'></span>
							</li>
							<li>
								<label>Prove</label>
								<span class="prove-html span-scroll" style='max-height: 300px;overflow: scroll;'></span>
							</li>
							<li>
								<label>Convocazione del perito</label>
								<span class="perito-html span-scroll"></span>
							</li>
							<li>
								<label>Unica istanza</label>
								<span class="istanza-html span-scroll"></span>
							</li>
							<li>
								<label>Prenotazione manuale</label>
								<span class="manual-html span-scroll"></span>
							</li>
							<li class="li_of_duration <?php if ($setting->get_option('ct_show_time_duration') == 'N') {
															echo "force_hidden";
														} ?>">
								<label><?php echo $label_language_values['duration']; ?></label>
								<span class="duration span-scroll"></span>
							</li>
							<li>
								<h6 class="ct-customer-details-hr"><?php echo 'Informazioni utente'; ?></h6>
							</li>
							<li>
								<label><?php echo $label_language_values['name']; ?></label>
								<span class="client_name span-scroll"></span>
							</li>
							<li>
								<label><?php echo $label_language_values['email']; ?></label>
								<span class="client_email span-scroll"></span>
							</li>
							<li>
								<label><?php echo $label_language_values['phone']; ?></label>
								<span class="client_phone span-scroll"></span>
							</li>
							<li>
								<label><?php echo $label_language_values['company_address']; ?></label>
								<span class="client_address span-scroll"></span>
							</li>
							<?php if ($global_vc_status == 'Y') {	?>
								<li class="pop_vc_status">
									<label><?php echo $label_language_values['vaccum_cleaner']; ?></label>
									<span class="client_vc_status span-scroll"></span>
								</li>
							<?php    }	?>
							<?php if ($global_p_status == 'Y') { ?>
								<li class="pop_p_status">
									<label><?php echo $label_language_values['parking']; ?></label>
									<span class="client_parking span-scroll"></span>
								</li>
							<?php    } ?>
							<li class="li_of_notes">
								<label><?php echo $label_language_values['notes']; ?></label>
								<span class="notes span-scroll"></span>
							</li>
							<li class="li_of_reason">
								<label><?php echo $label_language_values['reason']; ?></label>
								<span class="reason span-scroll"></span>
							</li>
							<?php if ($setting->get_option("ct_company_willwe_getin_status") == "Y") { ?>
								<li>
									<label><?php echo $label_language_values['contact_status']; ?></label>
									<span class="contact_status span-scroll"></span>
								</li>
							<?php    } ?>
						</ul>
					</div>
					<div class="modal-footer">
						<div class="col-xs-12 np ct-footer-popup-btn text-center">
							<div class="fln-mrat-dib">
								<span class="col-xs-4 np ct-w-32 mycompleteclass">
									<a id="ct-complete-appointment" class="btn btn-link ct-small-btn confirm_book ct-complete-appointment-cal" data-id="" title="<?php echo $label_language_values['complete_appointment']; ?>"><i class="fa fa-thumbs-up fa-2x"></i><br /><?php echo $label_language_values['complete']; ?></a>
								</span>
								<span class="col-xs-4 np ct-w-32 myconfirmclass">
									<a id="ct-confirm-appointment" class="btn btn-link ct-small-btn confirm_book ct-confirm-appointment-cal" data-id="" title="<?php echo 'Conferma'; ?>"><i class="fa fa-check fa-2x"></i><br /><?php echo 'Conferma'; ?></a>
								</span>
								<span class="col-xs-4 np ct-w-32 myconfirmclass">
									<a id="ct-reschedual-appointment" class="btn btn-link ct-small-btn rescedual_book ct-reschedual-appointment-cal" data-id="" title="<?php echo 'Sposta appuntamento'; ?>"><i class="fa fa-pencil-square-o fa-2x"></i><br /><?php echo 'Sposta appuntamento'; ?></a>
								</span>
								<span class="col-xs-4 np ct-w-32 myrejectclass">
									<a id="ct-reject-appointment-cal-popup" data-id="" class="btn btn-link ct-small-btn book_rejct" data-bkid="" rel="popover" data-placement='top' title="<?php echo 'Rifiuta'; ?>?"><i class="fa fa-thumbs-o-down fa-2x"></i><br /><?php echo 'Rifiuta'; ?></a>
									<div id="popover-reject-appointment-cal-popup" class="reject_book" style="display: none;">
										<div class="arrow"></div>
										<table class="form-horizontal" cellspacing="0">
											<tbody>
												<tr>
													<td><textarea class="form-control reject_rea_appt" id="reason_reject" name="" placeholder="<?php echo $label_language_values['appointment_reject_reason']; ?>" required="required"></textarea></td>
												</tr>
												<tr>
													<td>
														<button id="reject_appt" data-gc_event="" data-pid="" data-gc_staff_event="" value="Delete" class="btn btn-danger btn-sm reject_bookings" data-id="" type="submit"><?php echo $label_language_values['reject']; ?></button>
														<button id="ct-close-reject-appointment-cal-popup" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo $label_language_values['cancel']; ?></button>
													</td>
												</tr>
											</tbody>
										</table>
									</div><!-- end pop up -->
								</span>
								<span class="col-xs-4 np ct-w-32">
									<a id="ct-delete-appointment-cal-popup" class="ct-delete-appointment-cal-popup pull-left btn btn-link ct-small-btn book_cancel" data-id="" data-bkid="" rel="popover" data-placement='top' title="<?php echo $label_language_values['delete_this_appointment']; ?>?"><i class="fa fa-trash-o fa-2x"></i><br /> <?php echo $label_language_values['delete']; ?></a>
								</span>
								<div id="popover-delete-appointment-cal-popup" class="popup_display_cancel" style="display: none;">
									<div class="arrow"></div>
									<table class="form-horizontal" cellspacing="0">
										<tbody>
											<tr>
												<td>
													<button id="delete_appt" value="Delete" data-id="" data-gc_event="" data-pid="" data-gc_staff_event="" class="btn btn-danger btn-sm delete_bookings delete_bookings_dash" type="submit"><?php echo $label_language_values['delete']; ?></button>
													<button id="ct-close-del-appointment-cal-popup" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo $label_language_values['cancel']; ?></button>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- end pop up -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><!-- end details of booking -->
	<!-- file upload preview -->
	<div class="ct-new-customer-image-popup-view">
		<div id="ct-image-upload-popup" class="modal fade" tabindex="-1" role="dialog">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog modal-md vertical-align-center">
					<div class="modal-content">
						<div class="modal-header">
							<div class="col-md-12 col-xs-12">
								<button type="submit" class="btn btn-success"><?php echo $label_language_values['crop_and_save']; ?></button>
								<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $label_language_values['cancel']; ?></button>
							</div>
						</div>
						<div class="modal-body">
							<img id="ct-preview-img" />
						</div>
						<div class="modal-footer">
							<div class="col-md-12 np">
								<div class="col-md-4 col-xs-12">
									<label class="pull-left"><?php echo $label_language_values['file_size']; ?></label> <input type="text" class="form-control" id="filesize" name="filesize" />
								</div>
								<div class="col-md-4 col-xs-12">
									<label class="pull-left">H</label> <input type="text" class="form-control" id="h" name="h" />
								</div>
								<div class="col-md-4 col-xs-12">
									<label class="pull-left">W</label> <input type="text" class="form-control" id="w" name="w" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--reshedual appo-->
	<div class="modal fade" id="myModal_reschedual" role="dialog"></div>
	<div id="add-new-booking" class="modal fade ct-manual-booking-modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" id="info_modal_close" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
					<h4 class="modal-title"><?php echo $label_language_values['Add_Manual_booking']; ?></h4>
				</div>
				<div class="modal-body">
					<?php
					//include_once(dirname(dirname(__FILE__)).'/manual_booking.php');
					?>
				</div>
				<div class="modal-footer cb">
					<button type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $label_language_values['cancel']; ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include(dirname(__FILE__) . '/footer.php');
?>
<script>
	var ajax_url = '<?php echo AJAX_URL; ?>';
	var base_url = '<?php echo BASE_URL; ?>';
	var calObj = {
		'ajax_url': '<?php echo AJAX_URL; ?>'
	};
	var times = {
		'time_format_values': '<?php echo $gettimeformat; ?>'
	};
	var site_ur = {
		'site_url': '<?php echo SITE_URL; ?>'
	};
</script>