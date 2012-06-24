<?php


function get_cbstdsys_options(){

$options = get_option('cbstdsys_options');

		$cbstdsys_defaults = array(

				'content' => array(
				    'label'   =>  __('Content','cb-std-sys'),
				    'fields'   =>  array(

									'c_first_pub'	=> array(
											'label'		=> __('Year of first publishing','cb-std-sys'),
											'desc'		=> __('Used for copyright-Note in metas and footer','cb-std-sys'),
											'type'		=> 'text',
											'value' 	=> $options['c_first_pub'],
											'validate'=> 'numeric',
											'validate_msg'  => false,
											'class'   => 'regular-text',
											'status'  => 'disabled'
									),

						),

							
				),

		);
		return $cbstdsys_defaults;
}


		/**
		 *  generate form-fields for use on
		 *  * settings-pages
		 *  * tinymce-interface
		 *  * widget-controls
		 *
		 *  @since  0.0.5
		 */
		function render_form_fields ( $opt_groups, $prefix, $name_attr = false  ) {

        #$first_row = null;
				#$rowgroup_counter = false;
        $output = '';
        
				foreach ( $opt_groups as $group => $opt_fields ) {

        		$output.= '<fieldset class="'.$group.'"><legend>'. $opt_fields['label'] .'</legend><table class="form-table">';

		    foreach ( $opt_fields['fields'] as $id => $field ) {

								$formfield = '';

								// set name attribute
								$name	=	( $name_attr ) ? $name_attr.'['.$id.']' : $id ;

								// set id and css class
								$css_id = $prefix.$usage.$family.'_'.$id;

								//
								$class = $prefix.$usage.$family.' '.$prefix.$usage.$id.' '.$prefix.$family.$id;
								$class = ( isset( $field['class'] ) ) ? $class.' '.$field['class'] : $class;
								$status = ( isset( $field['status'] ) ) ? ' '.$field['status'] : '';

								// pre-define if to use scope="rowgroup" or tr without th
								#$rowgroup_counter = ( $rowgroup_counter >= 0 ) ? $rowgroup_counter : false ;


/*
								if ( $field['type'] == 'rowgroup' ) {

										//
						        $output .= '<tr class="'.$css_id.' '.$prefix.$id.$status.'"><th scope="rowgroup" rowspan="'.$field['rows'].'">'.$field['label'].'</th>';
				            $rowgroup_counter = $field['rows'];
				            $first_row  = true;
								} else {

										//  go on and do not add another tr
										if ( isset( $first_row ) ) {
		                    unset( $first_row );
		                //
										} elseif ( $rowgroup_counter === null || $rowgroup_counter === false ) {
				 								$output .= '<tr class="'.$css_id.' '.$prefix.$id.$status.'"><th><label for="'.$css_id.'">'.$field['label'].'</label></th>';
										//
										} else {
				 								$output .= '<tr class="'.$css_id.' '.$prefix.$id.$status.'">';
								    }
*/
				 						$output .= '<tr class="'.$css_id.' '.$prefix.$id.$status.'"><th><label for="'.$css_id.'">'.$field['label'].'</label></th>';
										#$output .= '<tr class="'.$css_id.' '.$prefix.$id.$status.'">';
						        $output .= '<td class="'.$field['type'].'">';

						                switch( $field['type'] ) {


																case 'text':
																	$formfield .= '<input type="text" name="'.$name.'" id="'.$css_id.'" value="'.$field['value'].'" class="'.$class.'"/>';
																break;


																case 'checkbox':
																	$formfield .= '<input type="checkbox" name="'.$name.'" id="'.$css_id.'" value="1" '. checked( $field['value'], 1, false ) .' class="'.$class.'"/>';
																break;


																case 'select':
																	$formfield .= '<select name="'.$name.'" id="'.$css_id.'" class="'.$class.'">';
																	foreach ($field['options'] as $k => $v ) {
																		$formfield .= '<option'. selected( $field['value'], $k, false ) .' value="'.$k.'">'.$v.'</option>';
																	}
																	$formfield .= '</select>';
																break;



																case 'radio':
																	foreach ($field['options'] as $k => $v ) {
																		$formfield .=  '<input type="radio" name="'.$name.'" id="'.$css_id.'-'.$k.'" value="'.$k.'" '. checked( $field['value'], $k, false ) .' class="'.$id.'-'.$k.'" />';
																		$formfield .=  '<label for="'.$css_id.'-'.$k.'" class="'.$id.' '.$id.'-'.$k.'">'.$v.'</label>';
																	}
																break;


																case 'upload':
																	$formfield .= '<input type="text" name="'.$name.'" id="'.$css_id.'" value="'.$field['value'].'" class="'.$class.'"/>';
																	$formfield .= '<input id="'.$css_id.'-upload" class="wp-cmm_call-wp-mediamanagement" type="button" value="'.__( 'Upload' ).'">';
																break;

/*
																case 'multipleselect':
																	$formfield .= '<select name="'.$name.'[]" id="'.$css_id.'" class="'.$class.'" multiple>';
																	foreach ($field['options'] as $value => $label ) {
																	 	$selected = ( in_array( $value, $field['value'] ) ) ? ' selected' : '';
																		$formfield .= '<option'. $selected .' value="'.$value.'">'.$label.'</option>';
																	}
																	$formfield .= '</select>';
																break;
*/

																case 'terms_checklist':
																	$formfield .= '<ul id="'.$css_id.'" class="categorychecklist form-no-clear '.$class.'" >';
																	foreach ($field['options'] as $value => $label ) {
																	 		$checked = ( in_array( $value, $field['value'] ) ) ? ' checked' : '';
																			$formfield .= '<li><label for="'.$css_id.'-'.$value.'"><input'.$checked.' type="checkbox" name="'.$name.'['.$value.']" id="'.$css_id.'-'.$value.'" value="1" />'.$label.'</label></li>';
#																			$formfield .= '<li><label for="'.$css_id.'-'.$value.'"><input'.$checked.' type="checkbox" name="'.$name.'" id="'.$css_id.'-'.$value.'" value="'.$value.'" />'.$label.'</label></li>';
																	}
																	$formfield .= '</ul>';
																break;

						                } //end switch

/*										//
										if ( $rowgroup_counter !== false && $rowgroup_counter !== null && $field['label']  ) {
				 								$output .= '<label for="'.$css_id.'">'.$formfield.$field['label'].'</label>';
								    } else {
												$output .= $formfield;
										}
*/
												$output .= $formfield;
				 					#	$output .= '<label for="'.$css_id.'">'.$formfield.$field['label'].'</label>';
										//
										if ( $field['desc'] ){
												$output .= '<span class="howto">'.$field['desc'].'</span>';
										}
						        $output .= "</td></tr>\n\n";
				        #}

								#$rowgroup_counter--;


		    } // end foreach  $opt_fields
		    
		    $output.= '</table></fieldset>';
		    
		    } // end foreach  $opt_groups
		    
				echo $output;
		}

// show options page, but only for carsten
if ( in_array($current_user->ID, cbstdsys_opts('a_admin_user_IDs') ) )
    add_action('admin_menu', 'cbstdsys_add_options_page');


// Init plugin options to white list our options
function cbstdsys_init(){
	register_setting( 'cbstdsys_plugin_options', 'cbstdsys_options', 'cbstdsys_validate_options' );
}


// Add menu page
function cbstdsys_add_options_page() {
	$icon = WP_THEME_URL.'/images/admin-icon.png';
	add_menu_page(  'cb-std-sys '.__('Settings').', Version '.CB_STD_SYS_VERSION, 'cb-std-sys '.__('Settings'), 'administrator', 'cbstdsys', 'cbstdsys_render_form');
	add_options_page(__('All Settings','cb-std-sys'), __('All Settings','cb-std-sys'), 'administrator', 'options.php');
}

// Render the Plugin options form
function cbstdsys_render_form() {
	?>
	<div class="wrap">

		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><span style="font-family:Consolas,Monaco,Courier,monospace">cb-std-sys</span>-<?php _e('Settings'); ?>, Version <?php echo CB_STD_SYS_VERSION; ?></h2>

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('cbstdsys_plugin_options'); ?>

<!--
        <fieldset>
          <legend><?php _e('Content','cb-std-sys'); ?></legend>

    			<table class="form-table">
-->
<?php render_form_fields( get_cbstdsys_options(), 'cbstdsys', 'cbstdsys_options' ); ?>
<!--
           </table>

        </fieldset>
-->
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>

		</form>

	</div>
	<?php
}


?>