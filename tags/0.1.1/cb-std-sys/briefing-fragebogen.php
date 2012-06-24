<?php
/**
 * Template Name: Briefing Fragebogen
 *
 *
 * @package WordPress
 * @subpackage 
 * @since 
 */

// Remove instantiation
add_filter( 'show_admin_bar', '__return_false' );
 

    $is_dev = true;  
    $bf_version = '0.5';

    wp_enqueue_style( 'bf_google_api_fonts', 'http://fonts.googleapis.com/css?family=Arvo:700|PT+Sans+Narrow|Lekton:400italic&v2', false, $bf_version );
    wp_deregister_script('jquery');
    
if ( $is_dev ) {

    wp_enqueue_style( 'bf_uniform_aristo', WP_THEME_URL. '/briefing/css/uniform.aristo.css', false, $bf_version );
    wp_enqueue_style( 'bf_ui_hot_sneaks', WP_THEME_URL. '/briefing/css/reformed-form-hot-sneaks/jquery-ui-1.8.7.custom.css', array('bf_uniform_aristo'), $bf_version );
    wp_enqueue_style( 'bf_base_css', WP_THEME_URL. '/briefing/css/fragebogen.css', array('bf_uniform_aristo', 'bf_ui_hot_sneaks'), $bf_version );

    wp_enqueue_script( 'jquery', WP_THEME_URL. '/briefing/js/libs/jquery-1.6.2.min.js', false, '1.6.2', true );
    wp_enqueue_script( 'jqueryui', WP_THEME_URL. '/briefing/js/libs/jquery-ui-1.8.14.min.js', array('jquery'), '1.8.14', true );     
    wp_enqueue_script( 'jquery-uniform', WP_THEME_URL. '/briefing/js/reformed/jquery.uniform.min.js', array('jquery', 'jqueryui' ), $bf_version, true );      
    wp_enqueue_script( 'jquery-validate', WP_THEME_URL. '/briefing/js/reformed/jquery.validate.min.js', array('jquery', 'jqueryui', 'jquery-uniform' ), $bf_version, true );
    wp_enqueue_script( 'jquery-validate-msg-de', WP_THEME_URL. '/briefing/js/libs/messages_de.min.js', array('jquery', 'jqueryui', 'jquery-uniform', 'jquery-validate' ), $bf_version, true );
    wp_enqueue_script( 'jquery-autoresize', WP_THEME_URL. '/briefing/js/libs/autoresize.jquery.min.js', array('jquery', 'jqueryui', 'jquery-uniform', 'jquery-validate' ), $bf_version, true );
    wp_enqueue_script( 'jquery-reformed', WP_THEME_URL. '/briefing/js/reformed/jquery.ui.reformed.min.js', array('jquery', 'jqueryui', 'jquery-uniform' ), $bf_version, true );      
    wp_enqueue_script( 'jquery-swfobject', WP_THEME_URL. '/briefing/freemind/jquery.swfobject.1-1-1.min.js', array('jquery', 'jqueryui', 'jquery-uniform' ), '1.1.1', true );
    wp_enqueue_script( 'bf_base_js', WP_THEME_URL. '/briefing/js/briefing-fragebogen.js', array('jquery', 'jqueryui', 'jquery-uniform', 'jquery-validate', 'jquery-autoresize', 'jquery-reformed', 'jquery-swfobject' ), $bf_version, true );

} else {

    wp_enqueue_style( 'bf_base_css', WP_THEME_URL. '/briefing/css/fragebogen.combined.css', false, $bf_version );
    
    wp_enqueue_script( 'bf_base_js', WP_THEME_URL. '/briefing/js/briefing-fragebogen.combined.js', false, $bf_version, true );
    
}
    $bf_params = array(
      'ajaxurl'      => admin_url('admin-ajax.php'),
      'path'         => WP_THEME_URL."/briefing/",
      'post_ID'      => $post->ID ,
      'slug'         => $_SERVER['REQUEST_URI']     
    );
    wp_localize_script( 'bf_base_js', 'bf', $bf_params );

get_header();
/*
if ( is_user_logged_in() ) {
  get_login_form();
}
*/
?>



      <div id="loader">Einen Moment Geduld noch bitte!</div>
      <div class="reformed-form" id="fragebogen_container">
      	<form action="#" id="Briefing_Fragebogen" name="Briefing_Fragebogen" method="post">

      <!------------------------------------------------------      Überblick   ----------------------------------->
          <fieldset>
      			<legend>Überblick</legend>
      			
            <dl class="w_75">
      				<dt>
      					<label for="Arbeitstitel">Arbeitstitel</label>
      				</dt>
      				<dd><input type="text" id="Arbeitstitel" class="required" name="Arbeitstitel"></dd>
      			</dl>
      
              			
            <dl class="w_25 last">
      				<dt>
      					<label for="Datum">Datum</label>
      				</dt>
      				<dd><input type="text" name="Datum" class="datepicker required" id="Datum"></dd>
      			</dl>
      			
            <dl class="w_100">
      				<dt>
      					<label for="Kurzbeschreibung">Kurzbeschreibung</label>
      				</dt>
      				<dd>
                <textarea minlength="150" name="Kurzbeschreibung" class="required" id="Kurzbeschreibung"></textarea>
              </dd>
      			</dl>
      			
      		</fieldset>

      	
      <!------------------------------------------------------      Status Quo   ------------------------------------>	
      		<fieldset>
      			<legend>Status Quo</legend>		
      
        		<dl class="url additional-inputs w_50">
        			<dt>
        				<label for="aktuelle_website">Gibt es eine aktuelle Website und über welche Adresse(n) ist sie erreichbar?</label></dt>
        			<dd>
                <input type="text" id="aktuelle_website" class="url" name="aktuelle_website" placeholder="http://"></dd>
        		</dl>
        		
        		<dl class="additional-inputs w_100">
        			<dt>
        				<label for="besonderheiten_der_aktuellen_URL">Was finden Sie an der aktuellen Webseite besonders gut?</label></dt>
        			<dd>
                <input type="text" id="besonderheiten_der_aktuellen_URL" name="besonderheiten_der_aktuellen_URL"></dd>
        		</dl>    
        		
        		<dl class="w_100 stoper">
        			<dt>
        				<label for="top-3-frustrationen-1">Was sind die Top-3-Frustrationen Ihrer aktuellen Webseite?</label></dt>
        			<dd>
                <input type="text" id="top-3-frustrationen-1" name="top-3-frustrationen-1" placeholder="1."></dd>
        			<dd>
                <input type="text" id="top-3-frustrationen-2" name="top-3-frustrationen-2" placeholder="2."></dd>
        	    			<dd>
                <input type="text" id="top-3-frustrationen-3" name="top-3-frustrationen-3" placeholder="3."></dd>
          	</dl>  
            
        		
        		<dl class="w_50">
        			<dt>
        				<label for="visitors">Wieviele Besucher verzeichnen Sie pro Tag, Woche oder Monat?</label></dt>
        			<dd>
                <input type="text" id="visitors" name="visitors"></dd>
        		</dl>    
        		
        		<dl class="w_50 last">
        			<dt>
        				<label for="first_public_date_">Jahr der Erstveröffentlichung der Seite</label></dt>
        			<dd>
                <input type="text" id="first_public_date_" name="first_public_date_"></dd>
        		</dl>  
                  
        		<dl class="w_50">
        			<dt>
        				<label for="provider">Wer ist der Hostingprovider?</label></dt>
        			<dd>
                <input type="text" id="provider" name="provider"></dd>
        		</dl>    
      
        		<dl class="w_50 last">
        			<dt>
        				<label for="hosting_pack">Und wie heißt das aktuelle Hostingpaket?</label></dt>
        			<dd>
                <input type="text" id="hosting_pack" name="hosting_pack"></dd>
        		</dl>    
                                                                	
      		</fieldset>		
	
	
      <!------------------------------------------------------      Ziele & Zielgruppen   ------------------------------------>	 	
      		<fieldset>
      			<legend>Ziele & Zielgruppen</legend>		
      
            <dl class="w_100">
      				<dt>
      					<label for="ziele">Welches messbare Ziel verfolgen Sie mit Ihrem Onlineauftritt?</label>
      				</dt>
      				<dd>
                <textarea name="ziele" class="required" id="ziele"></textarea>
              </dd>
      			</dl>
      
            <dl class="w_100">
      				<dt>
      					<label for="vision">Stellen Sie sich Ihre Webseite in einem Jahr vor. Wie endet folgender Satz?</label>
      					<p class="description">Die Webseite ist erfolgreich, weil &hellip;</p>
      				</dt>
      				<dd>
                <textarea name="vision" class="required" id="vision"></textarea>
              </dd>
      			</dl>
      
        		<dl class="url additional-inputs w_50">
        			<dt>
        				<label for="Konkurrenten">Wer sind die wichtigsten Konkurrenten?</label></dt>
        			<dd>
                <input type="text" id="Konkurrenten" name="Konkurrenten" class="required"></dd>
        		</dl>
      
        		
        		<dl class="additional-inputs w_100">
        			<dt>
        				<label for="usp">Was sind Ihre Alleinstellungsmerkmale gegenüber Ihren Mitbewerbern?</label></dt>
        			<dd>
                <input type="text" id="usp" name="usp" class="required"></dd>
        		</dl>   
        		
        		<dl class="additional-inputs w_100 stoper">
        			<dt>
        				<label for="besonderheiten_der_konkurrenz">Was sind die Besonderheiten der Webauftritte Ihrer Konkurrenz?</label></dt>
        			<dd>
                <input type="text" id="besonderheiten_der_konkurrenz" name="besonderheiten_der_konkurrenz" class="required"></dd>
        		</dl>   
      
      
            <dl class="w_100">
      				<dt>
      					<label for="call2action">Was soll ein Nutzer Ihrer Webseite auf jeden Fall unternehmen?</label>
      				</dt>
      				<dd>
                <input type="text" name="call2action" class="required" id="call2action" placeholder="z.B. Informationen recherchieren, Kontakt aufnehmen, Anmeldung durchführen, Bestellung aufgeben, &hellip;">
              </dd>
      			</dl>
      			
        		<dl class="w_50">
        			<dt>
        				<label>An wen richtet sich die neue Webpräsenz vorrangig?</label></dt>
        			<dd class="radio required">
        			   <ul>
                		<li><label for="zg_b2b"><input type="radio" id="zg_b2b" value="zg_b2b" name="zg[]" class="required">Geschäftskunden und Organisationen</label></li>
                		<li><label for="zg_b2c"><input type="radio" id="zg_b2c" value="zg_b2c" name="zg[]" class="required">Privatpersonen</label></li>
            		</ul>
             </dd>
             
        		</dl>   
          		
        		<dl class="w_50 inline last">
        			<dt>
        				<label>Wie alt sind diese Personen in der Regel?</label></dt>
        			<dd class="radio required">
        				<ul>
        					  <li><label for="age_0–15"><input type="radio" id="age_0–15" name="age[]" class="required" value="age_0–15">0–15</label></li>
                  	<li><label for="age_16-30"><input type="radio" id="age_16-30" name="age[]" class="required" value="age_16-30">16–30</label></li>
                  	<li><label for="age_31-45"><input type="radio" id="age_31-45" name="age[]" class="required" value="age_31-45">31–45</label></li>
                  	<li><label for="age_45-60"><input type="radio" id="age_45-60" name="age[]" class="required" value="age_45-60">45–60</label></li>
                  	<li><label for="age_60+"><input type="radio" id="age_60+" name="age[]" class="required" value="age_60+">60+</label></li>
        				</ul>          	
              </dd>	 
        		</dl>    
      
        		
        		<dl class="w_50">
        			<dt>
        				<label for="zg_background">Welcher Branche bzw. Berufsgruppe und sozialer Herkunft?</label></dt>
        			<dd>
                <input type="text" id="zg_background" name="zg_background" class="required"></dd>
        		</dl>  
          		
            <dl class="w_100">
      				<dt>
      					<label for="typical_user">Beschreiben Sie einen typischen Nutzer hinsichtlich Online-Erfahrung und möglicher Einschränkungen.</label>
      				</dt>
      				<dd>
                <textarea name="typical_user" class="required" id="typical_user"></textarea>
              </dd>
      			</dl>
      			
      		</fieldset>	    

      <!------------------------------------------------------      Inhalte & Redaktion   ------------------------------------>  
      		<fieldset>
      			<legend>Inhalte & Redaktion</legend>		
      
            <dl class="w_100">
      				<dt>
      					<label for="title">Nennen Sie den Titel der neuen Webseite!</label>
      				</dt>
      				<dd>
                <input type="text" name="title" class="required" id="title">
              </dd>
      			</dl>
      
            <dl class="w_100">
      				<dt>
      					<label for="claim">Wie lautet der Untertitel bzw. Slogan des neuen Onlineauftritts?</label>
      				</dt>
      				<dd>
                <input type="text" name="claim" class="required" id="claim">
              </dd>
      			</dl>		
          		
            <dl class="url additional-inputs w_50 stoper">
        			<dt>
        				<label for="new_domains">Sollen neue Domains für die Seite angemeldet werden?</label></dt>
        			<dd>
                <input type="text" id="new_domains" class="url" name="new_domains" placeholder="http://"></dd>
        		</dl>
      
        		<dl class="w_50">
        			<dt>
        				<label>Benötigen Sie zusätzlich zum öffentlichen Auftritt private und/oder passwortgeschützte Bereiche?</label></dt>
        			<dd class="checker required">
        			   <ul>
                		<li><label for="public_areas_pw"><input type="checkbox" id="public_areas_pw" value="public_areas_pw" name="public_areas[]" class="required">Ja, private</label></li>
                		<li><label for="public_areas_pr"><input type="checkbox" id="public_areas_pr" value="public_areas_pr" name="public_areas[]" class="required">Ja, passwortgeschützte</label></li>
                		<li><label for="public_areas_no"><input type="checkbox" id="public_areas_no" value="public_areas_no" name="public_areas[]" class="required">Nein, keines von beidem</label></li>
            		</ul>
             </dd>
        		</dl>   
      
        		<dl class="w_50 last inline">
        			<dt>
        				<label>Wünschen Sie eine Vorlage für ein aktuelles rechtsgültiges Impressum?</label></dt>
        			<dd class="radio required">
        			   <ul>
                		<li><label for="imprint_yes"><input type="radio" id="imprint_yes" value="imprint_yes" name="imprint[]" class="required">Ja</label></li>
                		<li><label for="imprint_no"><input type="radio" id="imprint_no" value="imprint_no" name="imprint[]" class="required">Nein</label></li>
            		</ul>
             </dd>
        		</dl>   
      
            <dl class="w_100 stoper">
      				<dt>
      					<label for="existing_contents">In welcher Form liegen bestehende Inhalte vor?</label>
      				</dt>
      				<dd>
                <input type="text" name="existing_contents" class="required" id="existing_contents" placeholder="z.B. als digitale Dokumente, in einer bestehenden Datenbank, handschriftlich, &hellip;">
              </dd>
      			</dl>	
      
            <dl class="multiple-inputs w_50">
        			<dt>
        				<label>Wer ist verantwortlich für die Redaktion Ihrer Inhalte?</label></dt>
        			<dd>
                <label for="content_responsibles_start">zur Erstveröffentlichung</label>
                <input type="text" id="content_responsibles_start" class="required" name="content_responsibles_start"></dd>
        			<dd class="last">
        			  <label for="content_responsibles_later">langfristig</label>
                <input type="text" id="content_responsibles_later" class="required" name="content_responsibles_later"></dd>
        		</dl>      
      
            <dl class="w_50">
        			<dt>
        				<label for="c_main_author">Wer ist der Hauptautor?</label></dt>
        			<dd>
                <input type="text" id="c_main_author" name="c_main_author" class="required"></dd>
        		</dl>   
      
            <dl class="w_50 last">
        			<dt>
        				<label for="c_main_email">Welche zentrale Emailadresse soll genutzt werden?</label></dt>
        			<dd>
                <input type="text" id="c_main_email" name="c_main_email" class="email required"></dd>
        		</dl>    
      
        		<dl class="w_50 inline">
        			<dt>
        				<label>Wünschen Sie eine Schulung zur selbstständigen Pflege Ihrer Inhalte mit dem Redaktionssystem?</label></dt>
        			<dd class="radio required">
        			   <ul>
                		<li><label for="coaching_yes"><input type="radio" id="coaching_yes" value="coaching_yes" name="coaching[]" class="required">Ja, für <input type="text" id="coaching_yes_p" name="coaching_yes_p" class="w_25"> Personen</label></li>
                		<li><label for="coaching_no"><input type="radio" id="coaching_no" value="coaching_no" name="coaching[]" class="required">Nein</label></li>
            		</ul>
             </dd>
        		</dl>         
      
        		<dl class="w_50 last inline">
        			<dt>
        				<label>Wünschen Sie integrierte Video-Dokumentationen zu den Funktionen Ihres Redaktionsbereiches?</label></dt>
        			<dd class="radio required">
        			   <ul>
                		<li><label for="videodoc_yes"><input type="radio" id="videodoc_yes" value="videodoc_yes" name="videodoc[]" class="required">Ja</label></li>
                		<li><label for="videodoc_no"><input type="radio" id="videodoc_no" value="videodoc_no" name="videodoc[]" class="required">Nein</label></li>
            		</ul>
             </dd>
        		</dl>  
                                 		
      		</fieldset>	    
     
      <!------------------------------------------------------      Struktur der Inhalte   ----------------------------------->
      <?php 
        $mm_img   = get_post_custom_values( 'briefing_fragebogen_mm_img' ); 
        $mm_file  = get_post_custom_values( 'briefing_fragebogen_mm_file' );
        
        if ( $mm_img &&  $mm_file) {
      ?>

          <fieldset id="freemind">
      			<legend>Struktur der Inhalte</legend>
            <div id="freemind-container">
                <img src="<?php echo $mm_img[0]; ?>" alt="Navigationsplan der Webseite">
            </div>
            <p><a href="<?php echo $mm_file[0]; ?>" class="mm-file">Navigationsplan herunterladen</a> und mit 
                <a href="http://freemind.sourceforge.net/wiki/index.php/Download#Download">Freemind</a> bearbeiten.</p>		
      		
      		</fieldset>	    
      		
      <?php } ?>
                    
 

      <!------------------------------------------------------      Bestandteile   ------------------------------------>  
       		<fieldset>
      			<legend>Bestandteile</legend>		
      
        		<dl class="w_50 inline">
        			<dt>
        				<label>Benötigen Sie eine Suchfunktion?</label></dt>
        			<dd class="radio required">
        			   <ul>
                		<li><label for="search_yes"><input type="radio" id="search_yes" value="search_yes" name="search[]" class="required">Ja</label></li>
                		<li><label for="search_no"><input type="radio" id="search_no" value="search_no" name="search[]" class="required">Nein</label></li>
            		</ul>
             </dd>
        		</dl> 
      
        		<dl class="w_100 inline">
        			<dt>
        				<label>Wünschen Sie eine Besucher-Statistik?</label>
                <p class="description">Nutzen Sie ggf. schon einen vorhandenen Google-Analytics Tracking Code?</p></dt>
        			<dd class="radio required">
        			   <ul>
                		<li class="w_75"><label for="ga_tracking_yes"><input type="radio" id="ga_tracking_yes" value="ga_tracking_yes" name="ga_tracking[]" class="required">Ja, mit folgendem <input type="text" id="ga_tracking_c" name="ga_tracking_c" class="w_25"> Google-Analytics Tracking Code</label></li>
                		<li><label for="ga_tracking_no"><input type="radio" id="ga_tracking_no" value="ga_tracking_no" name="ga_tracking[]" class="required">Nein</label></li>
            		</ul>
             </dd>
        		</dl>       
      
        		<dl class="w_100 inline">
        			<dt>
        				<label>Ist die Verwendung von Schlagworten, sogenannten Tags, interessant für Sie und Ihre Zielgruppe?</label>
                <p class="description">Wünschen Sie eine Tagcloud (Stichwort-Wolke) zur Navigation anhand dieser Schlagworte?</p></dt>
        			<dd class="radio required">
        			   <ul>
                		<li><label for="tags_yes"><input type="radio" id="tags_yes" value="tags_yes" name="tags[]" class="required">Ja</label></li>
                		<li><label for="tags_cloud"><input type="radio" id="tags_cloud" value="tags_cloud" name="tags[]" class="required">Ja, mit Tagcloud</label></li>
                		<li><label for="tags_no"><input type="radio" id="tags_no" value="tags_no" name="tags[]" class="required">Nein</label></li>
            		</ul>
             </dd>
        		</dl>       		
      
        		<dl class="w_100 inline">
        			<dt>
        				<label>Möchten Sie Ihren Nutzern die Möglichkeit zur Kommentierung bestimmter Inhalte geben?</label></dt>
        			<dd class="radio required">
        			   <ul>
                		<li><label for="comments_yes"><input type="radio" id="comments_yes" value="comments_yes" name="comments[]" class="required">Ja</label></li>
                		<li><label for="comments_no"><input type="radio" id="comments_no" value="comments_no" name="comments[]" class="required">Nein</label></li>
            		</ul>
             </dd>
        		</dl> 
      
        		<dl class="w_100 inline">
        			<dt>
        				<label>Benötigen Sie die Möglichkeit zur Einrichtung von Link- und Empfehlungslisten?</label></dt>
        			<dd class="radio required">
        			   <ul>
                		<li><label for="blogroll_yes"><input type="radio" id="blogroll_yes" value="blogroll_yes" name="blogroll[]" class="required">Ja</label></li>
                		<li><label for="blogroll_no"><input type="radio" id="blogroll_no" value="blogroll_no" name="blogroll[]" class="required">Nein</label></li>
            		</ul>
             </dd>
        		</dl>   
      
        		<dl class="w_50 inline">
        			<dt>
        				<label>Beabsichtigen Sie Ihre Webseite mehrsprachig aufzubauen?</label></dt>
        			<dd class="radio required">
        			   <ul>
                		<li><label for="langs_yes"><input type="radio" id="langs_yes" value="langs_yes" name="langs[]" class="required">Ja</label></li>
                		<li><label for="langs_no"><input type="radio" id="langs_no" value="langs_no" name="langs[]" class="required">Nein</label></li>
            		</ul>
        		</dl>   
            
            <dl id="add_langs" class="additional-inputs w_50 last">
        			<dt>
        				<label for="new_langs">Welche Sprachen?</label></dt>
        			<dd>
                <input type="text" id="new_langs" name="new_langs"></dd>
        		</dl>
      
        		<dl class="w_100 inline">
        			<dt>
        				<label>Kann die Verwendung digitaler Karten die Bedeutung Ihrer Inhalte unterstützen?</label></dt>
        			<dd class="radio required">
        			   <ul>
                		<li><label for="maps_yes"><input type="radio" id="maps_yes" value="maps_yes" name="maps[]" class="required">Ja</label></li>
                		<li><label for="maps_no"><input type="radio" id="maps_no" value="maps_no" name="maps[]" class="required">Nein</label></li>
            		</ul>
             </dd>
        		</dl> 
      
        		<dl class="w_100">
        			<dt>
        				<label>Sind Bilder-Galerien für Ihren Auftritt von Bedeutung?</label></dt>
        			<dd class="checker required">
        			   <ul>
                		<li><label for="gallery_global"><input type="checkbox" id="gallery_global" value="gallery_global" name="gallery[]" class="required">Ja, als globale Galerie innerhalb der Navigation</label></li>
                		<li><label for="gallery_single"><input type="checkbox" id="gallery_single" value="gallery_single" name="gallery[]" class="required">Ja, als seitenspezifische Galerien</label></li>
                		<li><label for="gallery_no"><input type="checkbox" id="gallery_no" value="gallery_no" name="gallery[]" class="required">Nein, keines von beidem</label></li>
            		</ul>
             </dd>
        		</dl>   		
      
        		<dl class="w_100">
        			<dt>
        				<label>Ist die Einbettung von Audio- und Video-Dateien relevant für Ihre Onlinepräsenz?</label></dt>
        			<dd class="checker required">
        			   <ul>
                		<li><label for="media_audio"><input type="checkbox" id="media_audio" value="media_audio" name="media[]" class="required">Ja, Audio-Dateien</label></li>
                		<li><label for="media_video"><input type="checkbox" id="media_video" value="media_video" name="media[]" class="required">Ja, Video-Dateien</label></li>
                		<li><label for="media_no"><input type="checkbox" id="media_no" value="media_no" name="media[]" class="required">Nein, keines von beidem</label></li>
            		</ul>
             </dd>
        		</dl>     		
      
        		<dl class="w_100">
        			<dt>
        				<label>Planen Sie, Ihrer Zielgruppe einen Newsletter-Service anzubieten?</label></dt>
        			<dd class="checker required">
        			   <ul>
                		<li><label for="newsletter_design"><input type="checkbox" id="newsletter_design" value="newsletter_design" name="newsletter[]" class="required">Ja, als gestaltetete Emails im Seitenlayout</label></li>
                		<li><label for="newsletter_plain"><input type="checkbox" id="newsletter_plain" value="newsletter_plain" name="newsletter[]" class="required">Ja, als Klartext-Emails</label></li>
                		<li><label for="newsletter_no"><input type="checkbox" id="newsletter_no" value="newsletter_no" name="newsletter[]" class="required">Nein, keines von beidem</label></li>
            		</ul>
             </dd>
        		</dl>     		
        		
        		
            		
      		</fieldset>	    

      <!------------------------------------------------------      Formulare   ------------------------------------>  
       		<fieldset class="forms">
      			<legend>Formulare</legend>		
            <div class="formsetup stoper">    
              			
                <dl class="w_75">
          				<dt>
          					<label for="f_form_title">Um welches Formular geht es?</label>
          				</dt>
          				<dd><input type="text" id="f_form_title" name="f_form_title" placeholder="z.B. Kontaktformular, Bestellformular, &hellip;"></dd>
          			</dl>
     
                <dl class="w_25 last">
          				<dt>
          					<label for="f_add_form"></label>
          				</dt>
          				<dd>
      			         <button class="formAdd">Formular hinzufügen</button></dd>
          			</dl>
          			
            </div>
            
            <div class="names stoper">            
            
                <dl class="w_50">
                    <input type="checkbox" id="f_salutation_a" value="f_salutation_a" name="f_salutation_a" class="activate-field">
                    <input type="text" id="f_salutation" name="f_salutation" placeholder="Anrede" disabled="disabled">
                    <div class="duty"><label for="f_salutation_duty"><input type="checkbox" id="f_salutation_duty" value="f_salutation_duty" name="f_salutation_duty">Pflichtfeld</label></div>
          			</dl>         
            
                <dl class="w_50 last">
                    <input type="checkbox" id="f_title_a" value="f_title_a" name="f_title_a" class="activate-field">
                    <input type="text" id="f_title" name="f_title" placeholder="Titel" disabled="disabled">
                    <div class="duty"><label for="f_title_duty"><input type="checkbox" id="f_title_duty" value="f_title_duty" name="f_title_duty">Pflichtfeld</label></div>
          			</dl>  
            
                <dl class="w_50">
                    <input type="checkbox" id="f_surname_a" value="f_surname_a" name="f_surname_a" class="activate-field">
                    <input type="text" id="f_surname" name="f_surname" placeholder="Vorname" disabled="disabled">
                    <div class="duty"><label for="f_surname_duty"><input type="checkbox" id="f_surname_duty" value="f_surname_duty" name="f_surname_duty">Pflichtfeld</label></div>
          			</dl>         
            
                <dl class="w_50 last">
                    <input type="checkbox" id="f_name_a" value="f_name_a" name="f_name_a" class="activate-field">
                    <input type="text" id="f_name" name="f_name" placeholder="Nachname" disabled="disabled">
                    <div class="duty"><label for="f_name_duty"><input type="checkbox" id="f_name_duty" value="f_name_duty" name="f_name_duty">Pflichtfeld</label></div>
          			</dl>  

                <dl class="w_50">
                    <input type="checkbox" id="f_firm_a" value="f_firm_a" name="f_firm_a" class="activate-field">
                    <input type="text" id="f_firm" name="f_firm" placeholder="Firma" disabled="disabled">
                    <div class="duty"><label for="f_firm_duty"><input type="checkbox" id="f_firm_duty" value="f_firm_duty" name="f_firm_duty">Pflichtfeld</label></div>
          			</dl>  
          			
            </div>

            <div class="address stoper">            
            
                <dl class="w_50">
                    <input type="checkbox" id="f_street_a" value="f_street_a" name="f_street_a" class="activate-field">
                    <input type="text" id="f_street" name="f_street" placeholder="Straße" disabled="disabled">
                    <div class="duty"><label for="f_street_duty"><input type="checkbox" id="f_street_duty" value="f_street_duty" name="f_street_duty">Pflichtfeld</label></div>
          			</dl>         
            
                <dl class="w_50 last">
                    <input type="checkbox" id="f_number_a" value="f_number_a" name="f_number_a" class="activate-field">
                    <input type="text" id="f_number" name="f_number" placeholder="Hausnummer" disabled="disabled">
                    <div class="duty"><label for="f_number_duty"><input type="checkbox" id="f_number_duty" value="f_number_duty" name="f_number_duty">Pflichtfeld</label></div>
          			</dl>  
            
                <dl class="w_50">
                    <input type="checkbox" id="f_postcode_a" value="f_postcode_a" name="f_postcode_a" class="activate-field">
                    <input type="text" id="f_postcode" name="f_postcode" placeholder="Postleitzahl" disabled="disabled">
                    <div class="duty"><label for="f_postcode_duty"><input type="checkbox" id="f_postcode_duty" value="f_postcode_duty" name="f_postcode_duty">Pflichtfeld</label></div>
          			</dl>         
            
                <dl class="w_50 last">
                    <input type="checkbox" id="f_city_a" value="f_city_a" name="f_city_a" class="activate-field">
                    <input type="text" id="f_city" name="f_city" placeholder="Ort" disabled="disabled">
                    <div class="duty"><label for="f_city_duty"><input type="checkbox" id="f_city_duty" value="f_city_duty" name="f_city_duty">Pflichtfeld</label></div>
          			</dl>  
          			
            </div>

            <div class="phones stoper">            
            
                <dl class="w_50">
                    <input type="checkbox" id="f_phone_a" value="f_phone_a" name="f_phone_a" class="activate-field">
                    <input type="text" id="f_phone" name="f_phone" placeholder="Festnetz-Nummer" disabled="disabled">
                    <div class="duty"><label for="f_phone_duty"><input type="checkbox" id="f_phone_duty" value="f_phone_duty" name="f_phone_duty">Pflichtfeld</label></div>
          			</dl>         
            
                <dl class="w_50 last">
                    <input type="checkbox" id="f_fax_a" value="f_fax_a" name="f_fax_a" class="activate-field">
                    <input type="text" id="f_fax" name="f_fax" placeholder="Fax-Nummer" disabled="disabled">
                    <div class="duty"><label for="f_fax_duty"><input type="checkbox" id="f_fax_duty" value="f_fax_duty" name="f_fax_duty">Pflichtfeld</label></div>
          			</dl>  
            
                <dl class="w_50">
                    <input type="checkbox" id="f_cell_a" value="f_cell_a" name="f_cell_a" class="activate-field">
                    <input type="text" id="f_cell" name="f_cell" placeholder="Mobil-Nummer" disabled="disabled">
                    <div class="duty"><label for="f_cell_duty"><input type="checkbox" id="f_cell_duty" value="f_cell_duty" name="f_cell_duty">Pflichtfeld</label></div>
          			</dl>         
            
                <dl class="w_50">
                    <input type="checkbox" id="f_recall_a" value="f_recall_a" name="f_recall_a" class="activate-field">
                    <input type="text" id="f_recall" name="f_recall" placeholder="Rückrufwunsch" disabled="disabled">
          			</dl>  

                <dl class="w_50 last">
                    <input type="checkbox" id="f_recalltime_a" value="f_recalltime_a" name="f_recalltime_a" class="activate-field">
                    <input type="text" id="f_recalltime" name="f_recalltime" placeholder="Rückruf Uhrzeit" disabled="disabled">
          			</dl>            			
            </div>

            <div class="web stoper">            
            
                <dl class="w_50">
                    <input type="checkbox" id="f_email_a" value="f_email_a" name="f_email_a" class="activate-field">
                    <input type="text" id="f_email" name="f_email" placeholder="Emailadresse" disabled="disabled">
                    <div class="duty"><label for="f_email_duty"><input type="checkbox" id="f_email_duty" value="f_email_duty" name="f_email_duty">Pflichtfeld</label></div>
          			</dl>         
            
                <dl class="w_50 last">
                    <input type="checkbox" id="f_website_a" value="f_website_a" name="f_website_a" class="activate-field">
                    <input type="text" id="f_website" name="f_website" placeholder="Webseite" disabled="disabled">
                    <div class="duty"><label for="f_website_duty"><input type="checkbox" id="f_website_duty" value="f_website_duty" name="f_website_duty">Pflichtfeld</label></div>
          			</dl>  
                      			
            </div>            

            <div class="subject stoper">            
            
                <dl class="w_50">
                    <input type="checkbox" id="f_subject_predefined_a" value="f_subject_predefined_a" name="f_subject_predefined_a" class="activate-field">
                    <input type="text" id="f_subject_predefined" name="f_subject_predefined" placeholder="vordefinierter Betreff" disabled="disabled">
          			</dl>         
            
                <dl class="w_50 last">
                    <input type="checkbox" id="f_subject_own_a" value="f_subject_own_a" name="f_subject_own_a" class="activate-field">
                    <input type="text" id="f_subject_own" name="f_subject_own" placeholder="eigener Betreff" disabled="disabled">
          			</dl>  
      
            		<dl class="additional-inputs w_50">
                    <input type="checkbox" id="f_subject_own_a" value="f_subject_own_a" name="f_subject_own_a" class="activate-field">
            			<dd>
                    <input type="text" id="f_subject_choose" name="f_subject_choose"  placeholder="Betreff auswählen" disabled="disabled"></dd>
            		</dl>
                                  			
            </div>  
            
            <div class="misc stoper">      
            
                <dl class="w_50">
                    <input type="checkbox" id="f_msg_a" value="f_msg_a" name="f_msg_a" class="activate-field">
                    <input type="text" id="f_msg" name="f_msg" placeholder="Nachricht" disabled="disabled">
                    <div class="duty"><label for="f_msg_duty"><input type="checkbox" id="f_msg_duty" value="f_msg_duty" name="f_msg_duty">Pflichtfeld</label></div>
          			</dl>         
            
                <dl class="w_50 last">
                    <input type="checkbox" id="f_asset_a" value="f_asset_a" name="f_asset_a" class="activate-field">
                    <input type="text" id="f_asset" name="f_asset" placeholder="Anhang hochladen" disabled="disabled">
                    <div class="duty"><label for="f_asset_duty"><input type="checkbox" id="f_asset_duty" value="f_asset_duty" name="f_asset_duty">Pflichtfeld</label></div>
          			</dl>              

                <dl class="w_50">
                    <input type="checkbox" id="f_mailcopy_a" value="f_mailcopy_a" name="f_mailcopy_a" class="activate-field">
                    <input type="text" id="f_mailcopy" name="f_mailcopy" placeholder="Kopie an Verfasser" disabled="disabled">
          			</dl> 

                <dl class="w_50 additional-inputs">
                    <input type="checkbox" id="f_mailcopy_a" value="f_mailcopy_a" name="f_mailcopy_a" class="activate-field">
                    <input type="text" id="f_mailcopy" name="f_mailcopy" placeholder="weitere Felder" disabled="disabled">
                    <div class="duty"><label for="f_mailcopy_duty"><input type="checkbox" id="f_mailcopy_duty" value="f_mailcopy_duty" name="f_mailcopy_duty">Pflichtfeld</label></div>
          			</dl>                             
            </div>   
                           
      		</fieldset>	    
      	
      <!------------------------------------------------------      Design   ------------------------------------>  
       		<fieldset>
      			<legend>Design</legend>		

        		<dl class="w_100 inline">
        			<dt>
        				<label>Besteht bereits ein Entwurf für ein Webdesign oder gibt es eine Idee?</label></dt>
        			<dd class="radio required">
        			   <ul>
                		<li><label for="designready_yes"><input type="radio" id="designready_yes" value="designready_yes" name="designready[]" class="required">Ja</label></li>
                		<li><label for="designready_no"><input type="radio" id="designready_no" value="designready_no" name="designready[]" class="required">Nein</label></li>
            		</ul>
             </dd>
        		</dl> 

        		<dl class="w_100 inline">
        			<dt>
        				<label>Gibt es bestehende Corporate Identity oder -Design Richtlinien?</label></dt>
        			<dd class="radio required">
        			   <ul>
                		<li><label for="ci_yes"><input type="radio" id="ci_yes" value="ci_yes" name="ci[]" class="required">Ja</label></li>
                		<li><label for="ci_no"><input type="radio" id="ci_no" value="ci_no" name="ci[]" class="required">Nein</label></li>
            		</ul>
             </dd>
        		</dl> 

      
            <dl id="d_fonts_fields" class="w_100">
        			<dt>
        				<label for="d_fonts">Machen Sie bitte einige Angaben über zu verwendende Schriften.</label></dt>
        			<dd>
                <input type="text" id="d_fonts" name="d_fonts"></dd>
        		</dl>              
      
            <dl id="d_colorscheme_fields" class="w_100">
        			<dt>
        				<label for="d_colorscheme">Und beschreiben Sie eine Farbpalette, die Sie für Ihren Webauftritt bevorzugen.</label></dt>
        			<dd>
                <input type="text" id="d_colorscheme" name="d_colorscheme"></dd>
        		</dl>     
      
            <dl class="w_100">
        			<dt>
        				<label for="d_describing_words">Finden Sie einige beschreibende Adjektive für den ersten Eindruck Ihrer Webseite!</label></dt>
        			<dd>
                <input type="text" id="d_describing_words" class="required" name="d_describing_words" placeholder="z.B. urban, verspielt, ausgeglichen, seriös, unkonventionell"></dd>
        		</dl>  

            <dl class="url additional-inputs w_50">
        			<dt>
        				<label for="d_nice_sites">Nennen Sie die Adressen einiger Seiten, die Ihnen besonders gut gefallen.</label></dt>
        			<dd>
                <input type="text" id="d_nice_sites" class="url" name="d_nice_sites" placeholder="http://"></dd>
        		</dl>

        		<dl class="w_100">
        			<dt>
        				<label>Wünschen Sie eine Möglichkeit, um Ihren Webauftritt seitenspezifisch anzupassen?</label></dt>
        			<dd class="checker required">
        			   <ul>
                		<li><label for="d_individ_bg_img"><input type="checkbox" id="d_individ_bg_img" value="d_individ_bg_img" name="d_individ[]" class="required">Ja, anpassbare Hintergrundbilder</label></li>
                		<li><label for="d_individ_bg_clr"><input type="checkbox" id="d_individ_bg_clr" value="d_individ_bg_clr" name="d_individ[]" class="required">Ja, anpassbare Hintergrundfarben</label></li>
                		<li><label for="d_individ_header"><input type="checkbox" id="d_individ_header" value="d_individ_header" name="d_individ[]" class="required">Ja, anpassbare Themen- oder Kopfbilder</label></li>
                		<li><label for="d_individ_no"><input type="checkbox" id="d_individ_no" value="d_individ_no" name="d_individ[]" class="required">Nein, nichts davon</label></li>
            		</ul>
             </dd>
        		</dl>  
                                          		
      		</fieldset>	    


      <!------------------------------------------------------      Sicherheit & Backups   ------------------------------------>	
       		<fieldset>
      			<legend>Sicherheit & Backups</legend>		

        		<dl id="bu_fields" class="w_100">
        			<dt>
        				<label>Gerne kümmere ich mich um die regelmäßige Sicherung & Archivierung der Daten Ihres Onlineauftritts.</label>
                <p class="description">Für welche Bereiche darf ich die Backup-Verantwortung für Sie übernehmen?</p></dt>
        			<dd class="checker">
        			   <ul>
                		<li><label for="bu_core"><input type="checkbox" id="bu_core" value="bu_core" name="bu[]" class="required">Systemdateien Ihres Redaktionssystems</label></li>
                		<li><label for="bu_files"><input type="checkbox" id="bu_files" value="bu_files" name="bu[]" class="required">Redaktions- und Nutzer-Uploads (z.B. Dokumente, Audio- und Videodateien, etc.)</label></li>
                		<li><label for="bu_db"><input type="checkbox" id="bu_db" value="bu_db" name="bu[]" class="required">Datenbank</label></li>
            		</ul>
             </dd>
        		</dl>  

        		<dl id="bu_f_fields" class="w_100 inline">
        			<dt>
        				<label>In welchem Rhythmus sollen Sicherungen angelegt werden?</label></dt>
        			<dd class="radio">
        			   <ul>
                		<li><label for="bu_f_daily"><input type="radio" id="bu_f_daily" value="bu_f_daily" name="bu_f[]">täglich</label></li>
                		<li><label for="bu_f_weekly"><input type="radio" id="bu_f_weekly" value="bu_f_weekly" name="bu_f[]">wöchentlich</label></li>
                		<li><label for="bu_f_fortnightly"><input type="radio" id="bu_f_fortnightly" value="bu_f_fortnightly" name="bu_f[]">2-wöchentlich</label></li>
                		<li><label for="bu_f_monthly"><input type="radio" id="bu_f_monthly" value="bu_f_monthly" name="bu_f[]">monatlich</label></li>
            		</ul>
             </dd>
        		</dl> 
        		
      		</fieldset>	              	

      	
      <!------------------------------------------------------      Realisierung   ------------------------------------> 
       		<fieldset>
      			<legend>Realisierung</legend>		

            <dl class="w_25">
      				<dt>
      					<label for="start_date">Wann ist der Starttermin?</label>
      				</dt>
      				<dd><input type="text" name="start_date" class="datepicker dateDE required" id="start_date"></dd>
      			</dl>

            <dl class="w_75 last">
      				<dt>
      					<label for="surrounding_dates">Welche weiteren relevanten Termine kommen vor und nach dem Starttermin in Frage?</label>
      				</dt>
      				<dd><input type="text" name="surrounding_dates" id="surrounding_dates" placeholder="z.B. Messen, Premieren, Veröffentlichungen, Pressekonferenzen etc."></dd>
      			</dl>      			

        		<dl class="additional-inputs w_100">
        			<dt>
        				<label for="launch_phases">Ist eine Unterteilung in Phasen denkbar oder sinnvoll?</label></dt>
        			<dd>
                <input type="text" id="launch_phases_day" name="launch_phases_day" placeholder="Stichtag" class="datepicker w_25">
                <input type="text" id="launch_phases" name="launch_phases" placeholder="Meilenstein" class="w_75 last"></dd>
        		</dl> 

            <dl class="w_25">
      				<dt>
      					<label for="budget">Beziffern Sie Ihr geplantes Budget für dieses Projekt!</label>
      				</dt>
      				<dd><input type="text" name="budget" id="budget" class="required"></dd>
      			</dl>
                  		
      		</fieldset>	
      	 
      <!------------------------------------------------------      Ansprechpartner   ------------------------------------>  
       		<fieldset>
      			<legend>Ansprechpartner</legend>		

        		<dl class="w_50">
        			<dt>
        				<label>Projektbetreuung</label>
                <p class="description">inhaltliche Abstimmung, Angebot & Rechnung</p></dt>
              <dd><input type="text" name="a_main_name" id="a_main_name" class="required" placeholder="Name"></dd>
              <dd><input type="text" name="a_main_phone" id="a_main_phone" class="required" placeholder="Telefon"></dd>
              <dd><input type="text" name="a_main_email" id="a_main_email" class="required email" placeholder="Email"></dd>
        		</dl>  

        		<dl class="w_50 last">
        			<dt>
        				<label>Redaktion</label>
                <p class="description">Lieferung der Inhalte, Übersetzung</p></dt>
              <dd><input type="text" name="a_text_name" id="a_text_name" placeholder="Name"></dd>
              <dd><input type="text" name="a_text_phone" id="a_text_phone" placeholder="Telefon"></dd>
              <dd><input type="text" name="a_text_email" id="a_text_email" class="email" placeholder="Email"></dd>
        		</dl>  

        		<dl class="w_50">
        			<dt>
        				<label>Design</label>
                <p class="description">Layout, Grafiken</p></dt>
              <dd><input type="text" name="a_design_name" id="a_design_name" placeholder="Name"></dd>
              <dd><input type="text" name="a_design_phone" id="a_design_phone" placeholder="Telefon"></dd>
              <dd><input type="text" name="a_design_email" id="a_design_email" class="email" placeholder="Email"></dd>
        		</dl> 
            
        		<dl class="w_50 last">
        			<dt>
        				<label>Technik</label>
                <p class="description">Server, Emailadressen, Backups</p></dt>
              <dd><input type="text" name="a_tec_name" id="a_tec_name" placeholder="Name"></dd>
              <dd><input type="text" name="a_tec_phone" id="a_tec_phone" placeholder="Telefon"></dd>
              <dd><input type="text" name="a_tec_email" id="a_tec_email" class="email" placeholder="Email"></dd>
        		</dl>                               		
      		</fieldset>	    
      		
          </form>
      </div>


      <!--   // unused   
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
      <script>window.jQuery || document.write("<script src='js/libs/jquery-1.6.2.min.js'>\x3C/script>")</script>

      <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js"></script>
      <script>window.jQuery || document.write("<script src='js/libs/jquery-ui-1.8.14.min.js'>\x3C/script>")</script>

      <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/localization/messages_de.js"></script>
      <script>window.jQuery || document.write("<script src='js/libs/messages_de.min.js'>\x3C/script>")</script>
      --> 
      
<?php get_footer(); ?>