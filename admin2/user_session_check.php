<?php  

if(isset($_SESSION['ct_login_user_id'])){
?>
	<script type="text/javascript">
		var loginObj={'site_url':'<?php echo SITE_URL;?>'};
		var login_url=loginObj.site_url;
		window.location=login_url+"admin/my-appointments.php";
	</script>
<?php 
}
?>