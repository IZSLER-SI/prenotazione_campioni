<?php  include(dirname(__FILE__).'/header.php');include(dirname(dirname(__FILE__)) . "/objects/class_services_methods_units.php");include(dirname(__FILE__).'/user_session_check.php');$con = new prenotazione_campioni_db();$conn = $con->connect();$objservice_m_unit = new prenotazione_campioni_services_methods_units();$objservice_m_unit->conn = $conn;?><script>    function goBack() {        window.history.back();    }</script><link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap-toggle.min.css" type="text/css" media="all"><script src="<?php echo BASE_URL; ?>/assets/js/bootstrap-toggle.min.js" type="text/javascript" ></script><div id="cta-clean-services-panel" class="panel tab-content">	<div class="panel-body">		<div class="ct-clean-service-details tab-content col-md-12 col-sm-12 col-lg-12 col-xs-12">            <ul class="breadcrumb">                <li><a href="services.php" class="myservicetitleformethod"></a></li>                <li><a href="service-manage-calculation-methods.php" class="mymethodtitleforunitbrcr"><?php echo $label_language_values['price_calculation_method'];?></a></li>                <li><a href="#" class=""><?php echo $label_language_values['units_of_methods'];?></a></li>            </ul>			<!-- right side common menu for service -->			<div class="ct-clean-service-top-header">				<span class="ct-clean-service-service-name pull-left mymethodtitleforunit"></span>								<div class="pull-right cta-unit-button-top">					<table>						<tbody>							<tr>                                <td>                                    <a href="#service-front-view" class="btn btn-info mydesign-setting-button"  data-toggle="modal"><?php echo $label_language_values['front_view_options'];?></a>                                    <!-- Modal HTML -->                                    <div id="service-front-view" class="modal fade">                                        <div class="modal-dialog modal-sm modal-md ">                                            <div class="modal-content">                                                <div class="modal-header">                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                                    <h4 class="modal-title"><?php echo $label_language_values['method_units_front_view'];?></h4>                                                    <h4 class="modal-titletester"></h4>                                                </div>                                                <div class="modal-body mymodalbody">                                                </div>                                                <div class="modal-footer cb">                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $label_language_values['close'];?></button>                                                </div>                                            </div>                                        </div>                                    </div>                                </td>								<td>									<button id="ct-add-new-price-unit" class="btn btn-success" value="add new service"><i class="fa fa-plus"></i><?php echo $label_language_values['add_unit'];?></button>								</td>							</tr>						</tbody>					</table>				</div>													</div>			<div id="hr"></div>			<div class="tab-pane active"><!-- services list -->				<div class="tab-content ct-clean-services-right-details">					<div class="tab-pane active col-lg-12 col-md-12 col-sm-12 col-xs-12">						<div id="accordion" class="panel-group">						<ul class="nav nav-tab nav-stacked myservice_method_unitload" id="sortable-services-unit" > <!-- sortable-services -->							</ul>						</div>						</div>				</div>			</div>					</div>				</div>		</div><?php  	include(dirname(__FILE__).'/footer.php');?><script type="text/javascript">    var ajax_url = '<?php echo AJAX_URL;?>';    var link_url = '<?php echo SITE_URL.'admin/';?>';</script>