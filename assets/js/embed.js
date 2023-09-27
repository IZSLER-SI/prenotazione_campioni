prenotazione_campioni_holder=document.getElementById('prenotazione_campioni');
var sites_urls=document.getElementById('prenotazione_campioni').getAttribute('data-url');

prenotazione_campioni_holder.innerHTML='<object id="prenotazione_campioni_content" style="width:100%; height:101%;" type="text/html" data="'+sites_urls+'" onload="prenotazione_campionidivload()" ></object>';
var normal_height = 2000;

function prenotazione_campionidivload(){
	setInterval(function() {
		var new_page_height = jQuery('#prenotazione_campioni object').contents().find('.ct-main-wrapper').height()+50;
		if(new_page_height < normal_height){
			jQuery('#prenotazione_campioni').height(normal_height);
		}else{
			jQuery('#prenotazione_campioni').height(new_page_height);
		}
	}, 500);
	
	jQuery('#prenotazione_campioni object').contents().find('.scroll_top_complete').click(function(e){
		jQuery('html, body').animate({scrollTop: 0 }, 1000);
	});
	
	jQuery('#prenotazione_campioni object').contents().find('.ct-service-embed').click(function(e){
	  jQuery('html, body').stop().animate({'scrollTop': jQuery('#prenotazione_campioni object').contents().find('.ct-scroll-meth-unit').offset().top}, 800, 'swing', function () {});
	});
}