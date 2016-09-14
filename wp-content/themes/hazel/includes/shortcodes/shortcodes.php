<?php
if (!function_exists('register_button')){
	function register_button( $buttons ){
		array_push( $buttons, "|", "qode_shortcodes" );
		return $buttons;
	}
}

if (!function_exists('add_plugin')){
	function add_plugin( $plugin_array ) {
		$plugin_array['qode_shortcodes'] = get_template_directory_uri() . '/includes/shortcodes/qode_shortcodes.js';
		return $plugin_array;
	}
}

if (!function_exists('qode_shortcodes_button')){
	function qode_shortcodes_button(){
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
			return;
		}

		if ( get_user_option('rich_editing') == 'true' ) {
			add_filter( 'mce_external_plugins', 'add_plugin' );
			add_filter( 'mce_buttons', 'register_button' );
		}
	}
}
add_action('after_setup_theme', 'qode_shortcodes_button');


if (!function_exists('num_shortcodes')){
	function num_shortcodes($content){
		$columns = substr_count( $content, '[pricing_cell' );
		return $columns;
	}
}

/* Action shortcode */

if (!function_exists('action')) {
	function action($atts, $content = null) {
		$args = array(
			"type"						        => "normal",
			"full_width"                        => "yes",
			"content_in_grid"                   => "yes",
			"icon_pack"                     	=> "",
			"fa_icon"                       	=> "",
			"fe_icon"                       	=> "",
			"icon_size"					        => "",
			"icon_color"				        => "",
			"custom_icon"				        => "",
			"background_color"                  => "",
			"border_color"                      => "",
			"show_button"                       => "yes",
			"button_size"                       => "",
			"button_link"                       => "",
			"button_text"                       => "",
			"button_target"                     => "",
			"button_text_color"                 => "",
			"button_hover_text_color"           => "",
			"button_background_color"           => "",
			"button_hover_background_color"     => "",
			"button_border_color"               => "",
			"button_hover_border_color"         => "",
			"text_color"                        => "", //used only when shortcode is called from call to action widget
			"text_size"                         => ""
		);

		extract(shortcode_atts($args, $atts));

		$html                   = '';
		$action_classes         = '';
		$action_styles          = '';
		$text_wrapper_classes   = '';
		$button_styles          = '';
		$icon_styles			= '';
		$data_attr              = '';
		$content_styles         = '';

		if($show_button == 'yes') {
			$text_wrapper_classes   .= 'column1';
		}

		if($background_color != '') {
			$action_styles .= 'background-color: '.$background_color.';';
		}
		$action_classes .= $type;
		if($border_color != '') {
			$action_styles .= 'border-top: 1px solid '.$border_color.';';
		}

		if($button_text_color != '') {
			$button_styles .= 'color: '.$button_text_color.';';
		}
		if($icon_color != "") {
			$icon_styles = " style='color: ".$icon_color . ";'";
		}

		if($icon_size != '') {
			$icon_styles .= 'font-size: '.$icon_size.'px;';
		}

		if($button_border_color != '') {
			$button_styles .= 'border-color: '.$button_border_color.';';
		}

		if($button_background_color != '') {
			$button_styles .= "background-color: {$button_background_color};";

		}

		if($button_hover_background_color != "") {
			$data_attr .= "data-hover-background-color=".$button_hover_background_color." ";
		}

		if($button_hover_border_color != "") {
			$data_attr .= "data-hover-border-color=".$button_hover_border_color." ";
		}

		if($button_hover_text_color != "") {
			$data_attr .= "data-hover-color=".$button_hover_text_color;
		}

		if($full_width == "no") {
			$html .= '<div class="container_inner">';
		}

		$html.=  '<div class="call_to_action '.$action_classes.'" style="'.$action_styles.'">';

		if($content_in_grid == 'yes' && $full_width == 'yes') {
			$html .= '<div class="container_inner">';
		}

		if($show_button == 'yes') {
			$html .= '<div class="two_columns_75_25 clearfix">';
		}

		if($text_size != '') {
			$content_styles .= 'font-size:'. $text_size.'px;';
		}

		if($text_color != '') {
			$content_styles .= 'color:'.$text_color.';';
		}

		$html .= '<div class="text_wrapper '.$text_wrapper_classes.'">';

		if($type == "with_icon"){
			$html .= '<div class="call_to_action_icon_holder">';
			$html .= '<div class="call_to_action_icon">';
			$html .= '<div class="call_to_action_icon_inner">';
			if($custom_icon != "") {
				if(is_numeric($custom_icon)) {
					$custom_icon_src = wp_get_attachment_url( $custom_icon );
				} else {
					$custom_icon_src = $custom_icon;
				}

				$html .= '<img src="' . $custom_icon_src . '" alt="">';
			} elseif($icon_pack == 'font_awesome' && $fa_icon != '') {
				$html .= '<i class="call_to_action_icon fa '.$fa_icon.'" style="'.$icon_styles.'"></i>';
			} elseif($icon_pack == 'font_elegant' && $fe_icon != '') {
				$html .= '<span class="call_to_action_icon q_font_elegant_icon '.$fe_icon.'" aria-hidden="true" style="'.$icon_styles.'"></span>';
			}

			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}

		$html .= '<div class="call_to_action_text" style="'.$content_styles.'">'.$content.'</div>';
		$html .= '</div>'; //close text_wrapper

		if($show_button == 'yes') {
			$button_link = ($button_link != '') ? $button_link : 'javascript: void(0)';

			$html .= '<div class="button_wrapper column2">';
			$html .= '<a href="'.$button_link.'" class="qbutton '. $button_size . '" target="'.$button_target.'" style="'.$button_styles.'"'. $data_attr . '>'.$button_text.'</a>';
			$html .= '</div>';//close button_wrapper
		}

		if($show_button == 'yes') {
			$html .= '</div>'; //close two_columns_75_25 if opened
		}

		if($content_in_grid == 'yes' && $full_width == 'yes') {
			$html .= '</div>'; // close .container_inner if oppened
		}

		$html .= '</div>';//close .call_to_action

		if($full_width == 'no') {
			$html .= '</div>'; // close .container_inner if oppened
		}

		return $html;
	}
}
add_shortcode('action', 'action');

/* Accordion shortcode */

if (!function_exists('accordion')) {
	function accordion($atts, $content = null) {
		extract(shortcode_atts(array("accordion_type"=>""), $atts));
		return "<div class='q_accordion_holder $accordion_type clearfix'>" . $content . "</div>";
	}
}
add_shortcode('accordion', 'accordion');

/* Accordion item shortcode */

if (!function_exists('accordion_item')) {
	function accordion_item($atts, $content = null) {
		extract(shortcode_atts(array("caption"=>"","title_color"=>"","icon"=>"","icon_color"=>"","background_color"=>""), $atts));
		$html           = '';
		$heading_styles = '';
		$no_icon        = '';

		if($icon == "") {
			$no_icon = 'no_icon';
		}

		if($title_color != "") {
			$heading_styles .= "color: ".$title_color.";";
		}

		if($background_color != "") {
			$heading_styles .= " background-color: ".$background_color.";";
		}

		$html .= "<h5 style='".$heading_styles."'>";
		if($icon != "") {
			$html .= '<div class="icon-wrapper"><i class="fa '.$icon.'" style="color: '.$icon_color.';"></i></div>';
		}
		$html .= "<div class='accordion_mark'></div><span class='tab-title'>".$caption."</span><span class='accordion_icon_mark'></span></h5><div class='accordion_content ".$no_icon."'><div class='accordion_content_inner'>" . $content . "</div></div>";

		return $html;
	}
}
add_shortcode('accordion_item', 'accordion_item');


/* Blockquote item shortcode */

if (!function_exists('blockquote')) {
	function blockquote($atts, $content = null) {
		$args = array(
			"text"              => "",
			"text_color"        => "",
			"title_tag"	        => "h5",
			"width"             => "",
			"line_height"       => "",
			"background_color"  => "",
			"border_color"      => "",
			"quote_icon_color"  => "",
			"show_quote_icon"   => "",
			"quote_icon_size"   => ""
		);

		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		//init variables
		$html               = "";
		$blockquote_styles  = "";
		$blockquote_classes = array();
		$heading_styles     = "";
		$quote_icon_styles  = array();

		if($show_quote_icon == 'yes') {
			$blockquote_classes[]= 'with_quote_icon';
		} else {
			$blockquote_classes[]= ' without_quote_icon';
		}

		if($width != "") {
			$blockquote_styles .= "width: ".$width."%;";
		}

		if($border_color != "") {
			$blockquote_styles .= "border-left-color: ".$border_color.";";
			$blockquote_classes[] = 'with_border';
		}

		if($background_color != "") {
			$blockquote_styles .= "background-color: ".$background_color.";";
			$blockquote_classes[] = 'with_background';
		}

		if($text_color != "") {
			$heading_styles .= "color: ".$text_color.";";
		}

		if($line_height != "") {
			$heading_styles .= " line-height: ".$line_height."px;";
		}

		if($quote_icon_color != "") {
			$quote_icon_styles[] = "color: ".$quote_icon_color;
		}

		if($quote_icon_size != '') {
			$quote_icon_styles[] = 'font-size: '.$quote_icon_size.'px';
		}

		$html .= "<blockquote class='".implode(' ', $blockquote_classes)."' style='".$blockquote_styles."'>"; //open blockquote
		if($show_quote_icon == 'yes') {
			$html .= "<span class='icon_quotations_holder'>";
			$html .= "<i class='q_font_elegant_icon icon_quotations' style='".implode(';', $quote_icon_styles)."'></i>";
			$html .= "</span>";
		}

		$html .= "<".$title_tag." class='blockquote_text' style='".$heading_styles."'>";
		$html .= "<span>".$text."</span>";
		$html .= "</".$title_tag.">";
		$html .= "</blockquote>"; //close blockquote
		return $html;
	}
}
add_shortcode('blockquote', 'blockquote');

/* Button shortcode */

if (!function_exists('button')) {
	function qbutton($atts, $content = null) {
		global $qode_options;

		$args = array(
			"size"                      => "",
			"style"                      => "",
			"text"                      => "",
			"icon_pack"              => "",
			"fa_icon"                => "",
			"fe_icon"                => "",
			"icon_color"                => "",
			"link"                      => "",
			"target"                    => "_self",
			"color"                     => "",
			"hover_color"               => "",
			"background_color"			=> "",
			"hover_background_color"    => "",
			"border_color"              => "",
			"hover_border_color"        => "",
			"font_style"                => "",
			"font_weight"               => "",
			"text_align"                => "",
			"margin"					=> "",
			"border_radius"				=> ""
		);

		extract(shortcode_atts($args, $atts));

		if($target == ""){
			$target = "_self";
		}

		//init variables
		$html  = "";
		$button_classes = "qbutton ";
		$button_styles  = "";
		$add_icon       = "";
		$data_attr      = "";

		if($size != "") {
			$button_classes .= " {$size}";
		}

		if($text_align != "") {
			$button_classes .= " {$text_align}";
		}
		if($style == "white") {
			$button_classes .= " {$style}";
		}
		if($color != ""){
			$button_styles .= 'color: '.$color.'; ';
		}

		if($border_color != ""){
			$button_styles .= 'border-color: '.$border_color.'; ';
		}

		if($font_style != ""){
			$button_styles .= 'font-style: '.$font_style.'; ';
		}

		if($font_weight != ""){
			$button_styles .= 'font-weight: '.$font_weight.'; ';
		}

		if($icon_pack != ""){
			$icon_style = "";
			$button_classes .= " qbutton_with_icon";
			if($icon_color != ""){
				$icon_style .= 'color: '.$icon_color.';';
			}

			if($icon_pack == 'font_awesome' && $fa_icon != '')
				$add_icon .= '<i class="button_icon fa '.$fa_icon.'" style="'.$icon_style.'"></i>';
			elseif ($icon_pack == 'font_elegant' && $fe_icon != ''){
				$add_icon .= '<span class="button_icon q_font_elegant_icon '.$fe_icon.'" aria-hidden="true" style="'.$icon_style.'"></span>';
			}
		}

		if($margin != ""){
			$button_styles .= 'margin: '.$margin.'; ';
		}

		if($border_radius != ""){
			$button_styles .= 'border-radius: '.$border_radius.'px;-moz-border-radius: '.$border_radius.'px;-webkit-border-radius: '.$border_radius.'px; ';
		}

		if($background_color != "" ) {
			$button_styles .= "background-color: {$background_color};";
		}

		if($hover_background_color != "") {
			$data_attr .= "data-hover-background-color=".$hover_background_color." ";
		}

		if($hover_border_color != "") {
			$data_attr .= "data-hover-border-color=".$hover_border_color." ";
		}

		if($hover_color != "") {
			$data_attr .= "data-hover-color=".$hover_color;
		}

		$html .=  '<a href="'.$link.'" target="'.$target.'" '.$data_attr.' class="'.$button_classes.'" style="'.$button_styles.'">'.$text.$add_icon.'</a>';

		return $html;
	}
}
add_shortcode('qbutton', 'qbutton');



/* Box shortcode */

if (!function_exists('q_box')) {
function q_box($atts, $content = null) {
    $args = array(
        "background_color"  => "",
        "background_image"  => "",
        "border_color"      => "",
        "border_width"      => "",
        "top_padding"       => "",
        "bottom_padding"    => "",
        "leftright_padding" => ""
    );
    
    extract(shortcode_atts($args, $atts));

    //init variables
    $html             = "";
    $box_styles       = "";
    $box_inner_styles = "";

    if($background_color != "") {
        $box_styles .= "background-color: ".$background_color.";";
    }

    if (is_numeric($background_image)) {
        $image_src = wp_get_attachment_url($background_image);
    } else {
        $image_src = $background_image;
    }

    if($background_image != "") {
        $box_styles .= "background-image: url(".$image_src.");";
    }

    if($border_width != "" && $border_color != ""){
        $box_styles .= "border: ".$border_width."px solid ".$border_color.";";
    } else if($border_width == "" && $border_color != ""){
        $box_styles .= "border: 2px solid ".$border_color.";";
    } else if($border_width != "" && $border_color == ""){
        $box_styles .= "border: ".$border_width."px solid #363636;";
    }

    if($top_padding != "") {
        $box_inner_styles .= "padding-top: ".$top_padding."px;";
    }

    if($bottom_padding != "") {
        $box_inner_styles .= "padding-bottom: ".$bottom_padding."px;";
    }

    if($leftright_padding != "") {
        $box_inner_styles .= "padding-left: ".$leftright_padding."px;";
        $box_inner_styles .= "padding-right: ".$leftright_padding."px;";
    }

    $html .= "<div class='q_boxes' style='".$box_styles."'>";
    $html .= "<div class='q_boxes_inner' style='".$box_inner_styles."'>";
        $html .= do_shortcode($content);
    $html .= "</div>";
    $html .= "</div>";

    return $html;
}
}
add_shortcode('q_box', 'q_box');







/* Counter shortcode */

if (!function_exists('counter')) {
	function counter($atts, $content = null) {
		$args = array(
			"type"              		=> "",
			"box"               		=> "",
			"box_border_color"  		=> "",
			"position"          		=> "",
			"digit"             		=> "",
			"font_size"         		=> "",
			"font_weight"       		=> "",
			"font_color"        		=> "",
			"text"              		=> "",
			"text_size"         		=> "",
			"text_font_weight"  		=> "",
			"text_transform"    		=> "",
			"text_color"        		=> "",
			"separator"         		=> "",
			"separator_color"   		=> "",
			"separator_border_style"   	=> ""
		);

		extract(shortcode_atts($args, $atts));

		//init variables
		$html                   = "";
		$counter_holder_classes = "";
		$counter_holder_styles  = "";
		$counter_classes        = "";
		$counter_styles         = "";
		$text_styles            = "";
		$separator_styles       = "";

		if($position != "") {
			$counter_holder_classes .= " ".$position;
		}

		if($box == "yes") {
			$counter_holder_classes .= " boxed_counter";
		}

		if($box_border_color != "") {
			$counter_holder_styles .= "border-color: ".$box_border_color.";";
		}

		if($type != "") {
			$counter_classes .= " ".$type;
		}

		if($font_color != "") {
			$counter_styles .= "color: ".$font_color.";";
		}

		if($font_size != "") {
			$counter_styles .= "font-size: ".$font_size."px;";
		}
		if($font_weight != "") {
			$counter_styles .= "font-weight: ".$font_weight.";";
		}
		if($text_size != "") {
			$text_styles .= "font-size: ".$text_size."px;";
		}
		if($text_font_weight != "") {
			$text_styles .= "font-weight: ".$text_font_weight.";";
		}
		if($text_transform != "") {
			$text_styles .= "text-transform: ".$text_transform.";";
		}

		if($text_color != "") {
			$text_styles .= "color: ".$text_color.";";
		}

		if($separator_color != "") {
			$separator_styles .= "border-color: ".$separator_color.";";
		}

		if($separator_border_style != "") {
			$separator_styles .= "border-bottom-style: ".$separator_border_style.';';
		}

		$html .= '<div class="q_counter_holder '.$counter_holder_classes.'" style="'.$counter_holder_styles.'">';
		$html .= '<span class="counter '.$counter_classes.'" style="'.$counter_styles.'">'.$digit.'</span>';

		if($separator == "yes") {
			$html .= '<span class="separator small" style="'.$separator_styles.'"></span>';
		}

		$html .= $content;

		if($text != "") {
			$html .= '<p class="counter_text" style="'.$text_styles.'">'.$text.'</p>';
		}

		$html .= '</div>'; //close q_counter_holder

		return $html;
	}
}
add_shortcode('counter', 'counter');

/* Custom font shortcode */

if (!function_exists('custom_font')) {
	function custom_font($atts, $content = null) {
		$args = array(
			"font_family"       => "",
			"font_size"         => "",
			"line_height"       => "",
			"font_style"        => "",
			"font_weight"       => "",
			"color"             => "",
			"text_decoration"   => "",
			"text_shadow"       => "",
			"letter_spacing"    => "",
			"background_color"  => "",
			"padding"           => "",
			"margin"            => "",
			"text_align"        => "left"
		);
		extract(shortcode_atts($args, $atts));

		$html = '';
		$html .= '<div class="custom_font_holder" style="';
		if($font_family != "") {
			$html .= 'font-family: '.$font_family.';';
		}

		if($font_size != "") {
			$html .= ' font-size: '.$font_size.'px;';
		}

		if($line_height != "") {
			$html .= ' line-height: '.$line_height.'px;';
		}

		if($font_style != "") {
			$html .= ' font-style: '.$font_style.';';
		}

		if($font_weight != "") {
			$html .= ' font-weight: '.$font_weight.';';
		}

		if($color != ""){
			$html .= ' color: '.$color.';';
		}

		if($text_decoration != "") {
			$html .= ' text-decoration: '.$text_decoration.';';
		}

		if($text_shadow == "yes") {
			$html .= ' text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);';
		}

		if($letter_spacing != "") {
			$html .= ' letter-spacing: '.$letter_spacing.'px;';
		}

		if($background_color != "") {
			$html .= ' background-color: '.$background_color.';';
		}

		if($padding != "") {
			$html .= ' padding: '.$padding.';';
		}

		if($margin != "") {
			$html .= ' margin: '.$margin.';';
		}

		$html .= ' text-align: ' . $text_align . ';';
		$html .= '">'.$content.'</div>';
		return $html;
	}
}
add_shortcode('custom_font', 'custom_font');

/* Cover Boxes shortcode */

if (!function_exists('cover_boxes')) {
	function cover_boxes($atts, $content = null) {
		$args = array(
			"active_element"    			=> "1",
			"title_tag"    					=> "h3",
			"title1"            			=> "",
			"title_color1"      			=> "",
			"text1"             			=> "",
			"text_color1"       			=> "",
			"image1"            			=> "",
			"link1"             			=> "",
			"link_label1"       			=> "",
			"link_target1"      			=> "",
			"title2"            			=> "",
			"title_color2"      			=> "",
			"text2"             			=> "",
			"text_color2"       			=> "",
			"image2"            			=> "",
			"link2"             			=> "",
			"link_label2"       			=> "",
			"link_target2"      			=> "",
			"title3"            			=> "",
			"title_color3"      			=> "",
			"text3"             			=> "",
			"text_color3"       			=> "",
			"image3"            			=> "",
			"link3"             			=> "",
			"link_label3"       			=> "",
			"link_target3"      			=> "",
			"read_more_button_style"      	=> ""
		);
		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		$html = "";
		$html .= "<div class='cover_boxes' data-active-element='".$active_element."'><ul class='clearfix'>";

		$html .= "<li>";
		$html .= "<div class='box'>";
		if($link_target1 != ""){
			$target1 = $link_target1;
		}else{
			$target1 = "_self";
		}
		if(is_numeric($image1)) {
			$image_src1 = wp_get_attachment_url( $image1 );
		}else {
			$image_src1 = $image1;
		}
		if(is_numeric($image2)) {
			$image_src2 = wp_get_attachment_url( $image2 );
		}else {
			$image_src2 = $image2;
		}
		if(is_numeric($image3)) {
			$image_src3 = wp_get_attachment_url( $image3 );
		}else {
			$image_src3 = $image3;
		}
		$html .= "<a class='thumb' href='".$link1."' target='".$target1."'><img alt='".$title1."' src='".$image_src1."' /></a>";
		if($title_color1 != ""){
			$color1 = " style='color:".$title_color1."''";
		}else{
			$color1 = "";
		}
		if($text_color1 != ""){
			$t_color1 = " style='color:".$text_color1."''";
		}else{
			$t_color1 = "";
		}
		$html .= "<div class='box_content'><".$title_tag." ".$color1." class='cover_box_title'>".$title1."</".$title_tag.">";
		$html .= "<p ".$t_color1.">".$text1."</p>";

		$button_class = "";
		$button_class_wrapper_open = "";
		$button_class_wrapper_close = "";
		if($read_more_button_style != "no"){
			$button_class = "qbutton small";
		}else {
			$button_class = "cover_boxes_read_more";
			$button_class_wrapper_open = "<h5>";
			$button_class_wrapper_close = "</h5>";
		}

		if($link_label1 != "") {
			$html .= $button_class_wrapper_open . "<a class='".$button_class."' href='".$link1."' target='".$target1."'>".$link_label1."</a>" . $button_class_wrapper_close;
		}

		$html .= "</div></div>";
		$html .= "</li>";

		$html .= "<li>";
		$html .= "<div class='box'>";
		if($link_target2 != ""){
			$target2 = $link_target2;
		}else{
			$target2 = "_self";
		}
		$html .= "<a class='thumb' href='".$link2."' target='".$target2."'><img alt='".$title2."' src='".$image_src2."' /></a>";
		if($title_color2 != ""){
			$color2 = " style='color:".$title_color2."''";
		}else{
			$color2 = "";
		}
		if($text_color2 != ""){
			$t_color2 = " style='color:".$text_color2."''";
		}else{
			$t_color2 = "";
		}
		$html .= "<div class='box_content'><".$title_tag." ".$color2." class='cover_box_title'>".$title2."</".$title_tag.">";
		$html .= "<p ".$t_color2.">".$text2."</p>";

		if($link_label2 != "") {
			$html .= $button_class_wrapper_open . "<a class='".$button_class."' href='".$link2."' target='".$target2."'>".$link_label2."</a>" . $button_class_wrapper_close;
		}

		$html .= "</div></div>";
		$html .= "</li>";

		$html .= "<li>";
		$html .= "<div class='box'>";
		if($link_target3 != ""){
			$target3 = $link_target3;
		}else{
			$target3 = "_self";
		}
		$html .= "<a class='thumb' href='".$link3."' target='".$target3."'><img alt='".$title3."' src='".$image_src3."' /></a>";
		if($title_color3 != ""){
			$color3 = " style='color:".$title_color3."''";
		}else{
			$color3 = "";
		}
		if($text_color3 != ""){
			$t_color3 = " style='color:".$text_color3."''";
		}else{
			$t_color3 = "";
		}
		$html .= "<div class='box_content'><".$title_tag." ".$color3." class='cover_box_title'>".$title3."</".$title_tag.">";
		$html .= "<p ".$t_color3.">".$text3."</p>";

		if($link_label3 != "") {
			$html .= $button_class_wrapper_open . "<a class='".$button_class."' href='".$link3."' target='".$target3."'>".$link_label3."</a>" . $button_class_wrapper_close;
		}

		$html .= "</div></div>";
		$html .= "</li>";

		$html .= "</ul></div>";
		return $html;
	}
}
add_shortcode('cover_boxes', 'cover_boxes');

/* Dropcaps shortcode */

if (!function_exists('dropcaps')) {
	function dropcaps($atts, $content = null) {
		$args = array(
			"color"             => "",
			"background_color"  => "",
			"border_color"      => "",
			"type"              => ""
		);
		extract(shortcode_atts($args, $atts));

		$html = "<span class='q_dropcap ".$type."' style='";
		if($background_color != ""){
			$html .= "background-color: $background_color;";
		}
		if($color != ""){
			$html .= " color: $color;";
		}
		if($border_color != ""){
			$html .= " border-color: $border_color;";
		}
		$html .= "'>" . $content  . "</span>";

		return $html;
	}
}
add_shortcode('dropcaps', 'dropcaps');

/* Highlights shortcode */

if (!function_exists('highlight')) {
	function highlight($atts, $content = null) {
		extract(shortcode_atts(array("color"=>"","background_color"=>""), $atts));
		$html =  "<span class='highlight'";
		if($color != "" || $background_color != ""){
			$html .= " style='color: ".$color."; background-color:".$background_color.";'";
		}
		$html .= ">" . $content . "</span>";
		return $html;
	}
}
add_shortcode('highlight', 'highlight');

//Icon shortcode
if(!function_exists('icons')) {
	function icons($atts, $content = null) {
		$default_atts = array(
			"icon_pack"            => "",
			"fa_size"              => "",
			"custom_size"          => "",
			"fa_icon"              => "",
			"fe_icon"              => "",
			"type"                 => "",
			"position"             => "",
			"border_color"         => "",
			"border_width"         => "",
			"icon_color"           => "",
			"background_color"     => "",
			"margin"               => "",
			"icon_animation"       => "",
			"icon_animation_delay" => "",
			"link"                 => "",
			"target"               => ""
		);

		extract(shortcode_atts($default_atts, $atts));

		$html = "";
		if($fa_icon != "" || $fe_icon != "") {

			if ($icon_pack == 'font_awesome' && $fa_icon != '')
				$size = $fa_size;

			//generate inline icon styles
			$icon_stack_classes    = '';
			$animation_delay_style = '';
			$icon_link_style       = '';

			//generate icon stack styles
			$icon_stack_style = '';
			$icon_stack_circle_styles = '';
			$icon_stack_square_styles = '';
			$icon_stack_normal_style  = '';

			if($custom_size != "") {
				$icon_stack_normal_style .= 'font-size: '.$custom_size;
				$icon_stack_circle_styles .= 'font-size: '.$custom_size;
				$icon_stack_square_styles .= 'font-size: '.$custom_size;

				if(!strstr($custom_size, 'px')) {
					$icon_stack_normal_style .= 'px;';
					$icon_stack_circle_styles .= 'px;';
					$icon_stack_square_styles .= 'px;';
				}
			}

			if($icon_color != "") {
				$icon_stack_normal_style .= 'color: '.$icon_color.';';
				$icon_stack_style .= 'color: '.$icon_color.';';
				$icon_link_style .= 'color: '.$icon_color.';';
			}

			if($position != "") {
				$icon_stack_classes .= 'pull-'.$position;
			}

			if($background_color != "") {
				$icon_stack_style .= 'background-color: '.$background_color.';';
			}

			if($border_color != "") {
				$icon_stack_style .= 'border-color: '.$border_color.';';
			}

			if($border_width != "") {
				$icon_stack_style .= 'border-width: '.$border_width.'px;';
			}

			if($icon_animation_delay != ""){
				$animation_delay_style .= 'transition-delay: '.$icon_animation_delay.'ms; -webkit-transition-delay: '.$icon_animation_delay.'ms; -moz-transition-delay: '.$icon_animation_delay.'ms; -o-transition-delay: '.$icon_animation_delay.'ms;';
			}

			if($margin != "") {
				$icon_stack_style .= 'margin: '.$margin.';';
				$icon_stack_normal_style .= 'margin: '.$margin.';';
			}

			switch ($type) {
				case 'circle':
					if($icon_pack == 'font_awesome' && $fa_icon != ''){

						$html = '<span class="fa-stack q_icon_shortcode q_font_awsome_icon_holder q_font_awsome_icon_circle '.$size.' '.$icon_stack_classes.' '.$icon_animation.'" style="'.$icon_stack_style.$icon_stack_circle_styles.' '.$animation_delay_style.'">';
						if($link != ""){
							$html .= '<a href="'.$link.'" target="'.$target.'" style="'.$icon_link_style.'">';
						}
						$html .= '<i class="fa '.$fa_icon.'"></i>';

					} elseif($icon_pack == 'font_elegant' && $fe_icon != ''){

						$html = '<span class="q_font_elegant_holder q_icon_shortcode '.$type.' '.$icon_stack_classes.' '.$icon_animation.'" style="'.$icon_stack_style.$icon_stack_circle_styles.' '.$animation_delay_style.'">';
						if($link != ""){
							$html .= '<a href="'.$link.'" target="'.$target.'" style="'.$icon_link_style.'">';
						}
						$html .= '<span class="q_font_elegant_icon '.$fe_icon.'" aria-hidden="true"></span>';

					}
					break;
				case 'square':
					if($icon_pack == 'font_awesome' && $fa_icon != ''){

						$html = '<span class="fa-stack q_font_awsome_icon_holder q_icon_shortcode q_font_awsome_icon_square '.$size.' '.$icon_stack_classes.' '.$icon_animation.'" style="'.$icon_stack_style.$icon_stack_square_styles.' '.$animation_delay_style.'">';
						if($link != ""){
							$html .= '<a href="'.$link.'" target="'.$target.'" style="'.$icon_link_style.'">';
						}
						$html .= '<i class="fa '.$fa_icon.'"></i>';

					} elseif($icon_pack == 'font_elegant' && $fe_icon != ''){

						$html = '<span class="q_font_elegant_holder q_icon_shortcode '.$type.' '.$icon_stack_classes.' '.$icon_animation.'" style="'.$icon_stack_style.$icon_stack_square_styles.' '.$animation_delay_style.'">';
						if($link != ""){
							$html .= '<a href="'.$link.'" target="'.$target.'" style="'.$icon_link_style.'">';
						}
						$html .= '<span class="q_font_elegant_icon '.$fe_icon.'" aria-hidden="true"></span>';

					}
					break;
				default:
					if($icon_pack == 'font_awesome' && $fa_icon != ''){

						$html = '<span class="q_font_awsome_icon q_icon_shortcode q_font_awsome_icon_holder '.$size.' '.$icon_stack_classes.' '.$icon_animation.'" style="'.$icon_stack_normal_style.' '.$animation_delay_style.'">';
						if($link != ""){
							$html .= '<a href="'.$link.'" target="'.$target.'" style="'.$icon_link_style.'">';
						}
						$html .= '<i class="fa '.$fa_icon.'"></i>';

					} elseif($icon_pack == 'font_elegant' && $fe_icon != ''){

						$html = '<span class="q_font_elegant_holder q_icon_shortcode '.$type.' '.$icon_stack_classes.' '.$icon_animation.'" style="'.$icon_stack_normal_style.' '.$animation_delay_style.'">';
						if($link != ""){
							$html .= '<a href="'.$link.'" target="'.$target.'" style="'.$icon_link_style.'">';
						}
						$html .= '<span class="q_font_elegant_icon '.$fe_icon.'" aria-hidden="true"></span>';

					}
					break;
			}

			if($link != ""){
				$html .= '</a>';
			}

			$html.= '</span>';
		}
		return $html;
	}
}
add_shortcode('icons', 'icons');

/* Icon with text shortcode */

if(!function_exists('icon_text')) {
	function icon_text($atts, $content = null) {
		$default_atts = array(
			"icon_size"             		=> "",
			"custom_icon_size"      		=> "20",
			"text_left_padding"     		=> "99",
			"icon_pack"             		=> "",
			"fa_icon"               		=> "",
			"fe_icon"               		=> "",
			"icon_animation"        		=> "",
			"icon_animation_delay"  	 	=> "",
			"icon_type"             	 	=> "",
			"icon_border_width"       	 	=> "",
			"without_double_border_icon" 	=> "",
			"icon_position"         		=> "",
			"icon_border_color"     		=> "",
			"icon_margin"           		=> "",
			"icon_color"            		=> "",
			"icon_background_color" 		=> "",
			"box_type"              		=> "",
			"box_border"            		=> "",
			"box_border_color"      		=> "",
			"box_background_color"  		=> "",
			"title"                 		=> "",
			"title_tag"             		=> "h5",
			"title_color"           		=> "",
			"title_padding"         		=> "",
			"text"                  		=> "",
			"text_color"            		=> "",
			"link"                  		=> "",
			"link_text"             		=> "",
			"link_color"            		=> "",
			"target"                		=> ""
		);

		extract(shortcode_atts($default_atts, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		//init icon styles
		$style = '';
		$icon_stack_classes = '';

		//init icon stack styles
		$icon_margin_style       	= '';
		$icon_stack_square_style 	= '';
		$icon_stack_base_style   	= '';
		$icon_stack_style        	= '';
		$icon_holder_style          = '';
		$animation_delay_style   	= '';

		//generate inline icon styles
		if($custom_icon_size != "" && $fe_icon != "" && $icon_pack == 'font_elegant') {
			$icon_stack_style .= 'font-size: '.$custom_icon_size.'px;';
		}

		if($icon_color != "") {
			$style .= 'color: '.$icon_color.';';
			$icon_stack_style .= 'color: '.$icon_color.';';
		}

		//generate icon stack styles
		if($icon_background_color != "") {
			$icon_stack_base_style .= 'background-color: '.$icon_background_color.';';
			$icon_stack_square_style .= 'background-color: '.$icon_background_color.';';
		}

		if($icon_border_width !== '') {
			$icon_stack_base_style .= 'border-width: '.$icon_border_width.'px;';
			$icon_holder_style .= 'border-width: '.$icon_border_width.'px;';
			$icon_stack_square_style .= 'border-width: '.$icon_border_width.'px;';
		}

		if($icon_border_color != "") {
			$icon_stack_style .= 'border-color: '.$icon_border_color.';';
			$icon_holder_style .= 'border-color: '.$icon_border_color.';';
		}

		if($icon_margin != "") {
			$icon_margin_style .= "margin: ".$icon_margin.";";
		}

		if($icon_animation_delay != "" && $icon_animation == "q_icon_animation"){
			$animation_delay_style .= 'transition-delay: '.$icon_animation_delay.'ms; -webkit-transition-delay: '.$icon_animation_delay.'ms; -moz-transition-delay: '.$icon_animation_delay.'ms; -o-transition-delay: '.$icon_animation_delay.'ms;';
		}

		$box_size = '';
		//generate icon text holder styles and classes

		//map value of the field to the actual class value

		if($icon_pack == 'font_awesome' && $fa_icon != ''){

			switch ($icon_size) {
				case 'large': //smallest icon size
					$box_size = 'tiny';
					break;
				case 'fa-2x':
					$box_size = 'small';
					break;
				case 'fa-3x':
					$box_size = 'medium';
					break;
				case 'fa-4x':
					$box_size = 'large';
					break;
				case 'fa-5x':
					$box_size = 'very_large';
					break;
				default:
					$box_size = 'tiny';
			}
		}

		$box_icon_type = '';
		switch ($icon_type) {
			case 'normal':
				$box_icon_type = 'normal_icon';
				break;
			case 'square':
				$box_icon_type = 'square';
				break;
			case 'circle':
				$box_icon_type = 'circle';
				break;
		}

		$html = "";
		$html_icon = "";

		//genererate icon html
		switch ($icon_type) {
			case 'circle':
				//if custom icon size is set and if it is larger than large icon size
				if($custom_icon_size != "") {
					//add custom font class that has smaller inner icon font
					$icon_stack_classes .= ' custom-font';
				}

				if($icon_pack == 'font_awesome' && $fa_icon != ''){
					$html_icon .= '<span class="fa-stack '.$icon_size.' '.$icon_stack_classes.'" style="'.$icon_stack_style . $icon_stack_base_style .'">';
					$html_icon .= '<i class="icon_text_icon fa '.$fa_icon.' fa-stack-1x"></i>';
					$html_icon .= '</span>';
				}elseif($icon_pack == 'font_elegant' && $fe_icon != ''){
					$html_icon .= '<span class="q_font_elegant_holder '.$icon_type.' '.$icon_stack_classes.'" style="'.$icon_stack_style.$icon_stack_base_style.'">';
					$html_icon .= '<span class="icon_text_icon q_font_elegant_icon '.$fe_icon.'" aria-hidden="true"></span>';
					$html_icon .= '</span>';
				}

				break;
			case 'square':
				//if custom icon size is set and if it is larget than large icon size
				if($custom_icon_size != "") {
					//add custom font class that has smaller inner icon font
					$icon_stack_classes .= ' custom-font';
				}

				if($icon_pack == 'font_awesome' && $fa_icon != ''){
					$html_icon .= '<span class="fa-stack '.$icon_size.' '.$icon_stack_classes.'" style="'.$icon_stack_style.$icon_stack_square_style.'">';
					$html_icon .= '<i class="icon_text_icon fa '.$fa_icon.' fa-stack-1x"></i>';
					$html_icon .= '</span>';
				} elseif($icon_pack == 'font_elegant' && $fe_icon != ''){
					$html_icon .= '<span class="q_font_elegant_holder '.$icon_type.' '.$icon_stack_classes.'" style="'.$icon_stack_style.$icon_stack_square_style.'">';
					$html_icon .= '<span class="icon_text_icon q_font_elegant_icon '.$fe_icon.'" aria-hidden="true"></span>';
					$html_icon .= '</span>';
				}

				break;
			default:

				if($icon_pack == 'font_awesome' && $fa_icon != ''){
					$html_icon .= '<span style="'.$icon_stack_style.'" class="q_font_awsome_icon '.$icon_size.' '.$icon_stack_classes.'">';
					$html_icon .= '<i class="icon_text_icon fa '.$fa_icon.'"></i>';
					$html_icon .= '</span>';
				} elseif($icon_pack == 'font_elegant' && $fe_icon != ''){
					$html_icon .= '<span class="q_font_elegant_holder '.$icon_type.' '.$icon_stack_classes.'" style="'.$icon_stack_style.'">';
					$html_icon .= '<span class="icon_text_icon q_font_elegant_icon '.$fe_icon.'" aria-hidden="true"></span>';
					$html_icon .= '</span>';
				}

				break;
		}

		$title_style = "";
		if($title_color != "") {
			$title_style .= "color: ".$title_color;
		}

		$text_style = "";
		if($text_color != "") {
			$text_style .= "color: ".$text_color;
		}

		$link_style = "";

		if($link_color != "") {
			$link_style .= "color: ".$link_color.";";
		}

		//generate normal type of a box html
		if($box_type == "normal") {

			//init icon text wrapper styles
			$icon_with_text_clasess = '';
			$icon_with_text_style   = '';
			$icon_text_inner_style  = '';
			$icon_text_holder_style = '';

			$icon_with_text_clasess .= $box_size;
			$icon_with_text_clasess .= ' '.$box_icon_type;

			if($box_border == "yes") {
				$icon_with_text_clasess .= ' with_border_line';
			}

			if($without_double_border_icon == 'yes') {
				$icon_with_text_clasess .= ' without_double_border';
			}

			if($text_left_padding != "" && $fe_icon != "" && $icon_position == "left"){
				$icon_text_holder_style .= 'padding-left: '.$text_left_padding.'px';
			}

			if($box_border == "yes" && $box_border_color != "") {
				$icon_text_inner_style .= 'border-color: '.$box_border_color;
			}

			if($icon_position == "" || $icon_position == "top") {
				$icon_with_text_clasess .= " center";
			}
			if($icon_position == "left_from_title"){
				$icon_with_text_clasess .= " left_from_title";
			}

			$html .= "<div class='q_icon_with_title ".$icon_with_text_clasess."'>";
			if($icon_position != "left_from_title") {
				//generate icon holder html part with icon
				$html .= '<div class="icon_holder '.$icon_animation.'" style="'.$icon_margin_style.' '.$animation_delay_style.'">';
				$html .= '<div class="icon_holder_inner" style="'.$icon_holder_style.'">';
				$html .= $html_icon;
				$html .= '</div>'; // close icon_holder_inner
				$html .= '</div>'; //close icon_holder
			}

			//generate text html
			$html .= '<div class="icon_text_holder" style="'.$icon_text_holder_style.'">';
			$html .= '<div class="icon_text_inner" style="'.$icon_text_inner_style.'">';
			if($icon_position == "left_from_title") {
				$html .= '<div class="icon_title_holder">'; //generate icon_title holder for icon from title
				//generate icon holder html part with icon
				$html .= '<div class="icon_holder '.$icon_animation.'" style="'.$icon_margin_style.' '.$animation_delay_style.'">';
				$html .= '<div class="icon_holder_inner" style="'.$icon_holder_style.'">';
				$html .= $html_icon;
				$html .= '</div>'; //close icon_holder_inner
				$html .= '</div>'; //close icon_holder
			}
			$html .= '<'.$title_tag.' class="icon_title" style="'.$title_style.'">'.$title.'</'.$title_tag.'>';
			if($icon_position == "left_from_title") {
				$html .= '</div>'; //close icon_title holder for icon from title
			}
			$html .= "<p style='".$text_style."'>".$text."</p>";
			if($link != ""){
				if($target == ""){
					$target = "_self";
				}

				if($link_text == ""){
					$link_text = "READ MORE";
				}

				$html .= "<a class='icon_with_title_link' href='".$link."' target='".$target."' style='".$link_style."'>".$link_text."</a>";
			}
			$html .= '</div>';  //close icon_text_inner
			$html .= '</div>'; //close icon_text_holder

			$html.= '</div>'; //close icon_with_title
		} else {
			//init icon text wrapper styles
			$icon_with_text_clasess = '';
			$box_holder_styles = '';

			if($box_border_color != "") {
				$box_holder_styles .= 'border-color: '.$box_border_color.';';
			}

			if($box_background_color != "") {
				$box_holder_styles .= 'background-color: '.$box_background_color.';';
			}

			if($title_padding != ""){
				$valid_title_padding = (strstr($title_padding, 'px', true)) ? $title_padding : $title_padding.'px';
				$title_style .= 'padding-top: '.$valid_title_padding.';';
			}

			$icon_with_text_clasess .= $box_size;
			$icon_with_text_clasess .= ' '.$box_icon_type;

			if($without_double_border_icon == 'yes') {
				$icon_with_text_clasess .= ' without_double_border';
			}

			$html .= '<div class="q_box_holder with_icon" style="'.$box_holder_styles.'">';

			$html .= '<div class="box_holder_icon">';
			$html .= '<div class="box_holder_icon_inner '.$icon_with_text_clasess.' '.$icon_animation.'" style="'.$animation_delay_style.'">';
			$html .= '<div class="icon_holder_inner" style="'.$icon_holder_style.'">';
			$html .= $html_icon;
			$html .= '</div>'; //close icon_holder_inner
			$html .= '</div>'; //close box_holder_icon_inner
			$html .= '</div>'; //close box_holder_icon

			//generate text html
			$html .= '<div class="box_holder_inner '.$box_size.' center">';
			$html .= '<'.$title_tag.' class="icon_title" style="'.$title_style.'">'.$title.'</'.$title_tag.'>';
			$html .= '<p style="'.$text_style.'">'.$text.'</p>';
			$html .= '</div>'; //close box_holder_inner

			$html .= '</div>'; //close box_holder
		}

		return $html;

	}
}
add_shortcode('icon_text', 'icon_text');

/* Image hover shortcode */

if (!function_exists('image_hover')) {

	function image_hover($atts, $content = null) {
		$args = array(
			"image"             => "",
			"hover_image"       => "",
			"link"              => "",
			"target"            => "_self",
			"animation"         => "",
			"transition_delay"  => ""
		);

		extract(shortcode_atts($args, $atts));

		//init variables
		$html               = "";
		$image_classes      = "";
		$image_src          = $image;
		$hover_image_src    = $hover_image;
		$images_styles      = "";

		if (is_numeric($image)) {
			$image_src = wp_get_attachment_url($image);
		}

		if (is_numeric($hover_image)) {
			$hover_image_src = wp_get_attachment_url($hover_image);
		}

		if($hover_image_src != "") {
			$image_classes .= "active_image ";
		}

		$css_transition_delay = ($transition_delay != "" && $transition_delay > 0) ? $transition_delay / 1000 . "s" : "";

		$animate_class = ($animation == "yes") ? "hovered" : "";

		//generate output
		$html .= "<div class='image_hover {$animate_class}' style='' data-transition-delay='{$transition_delay}'>";
		$html .= "<div class='images_holder'>";

		if($link != "") {
			$html .= "<a href='{$link}' target='{$target}'>";
		}

		$html .= "<img class='{$image_classes}' src='{$image_src}' alt='' style='{$images_styles}' />";
		$html .= "<img class='hover_image' src='{$hover_image_src}' alt='' style='{$images_styles}' />";

		if($link != "") {
			$html .= "</a>";
		}

		$html .= "</div>"; //close image_hover
		$html .= "</div>"; //close images_holder

		return $html;
	}

	add_shortcode('image_hover', 'image_hover');
}

/* Icon List Item shortcode */

if (!function_exists('icon_list_item')) {
	function icon_list_item($atts, $content = null) {
		$args = array(
			"icon_pack"                => "",
			"fa_icon"                  => "",
			"fe_icon"                  => "",
			"icon_type"                => "",
			"icon_color"               => "",
			"border_type"              => "",
			"border_color"             => "",
			"title"                    => "",
			"title_color"              => "",
			"title_size"               => ""
		);

		extract(shortcode_atts($args, $atts));

		$html           = '';
		$icon_style     = "";
		$icon_classes   = "";
		$title_style    = "";

		$icon_classes .= $icon_type." ";

		if($icon_color != "") {
			$icon_style .= "color:".$icon_color.";";
		}

		if($border_color != "" && $border_type != "") {
			$icon_style .= "border-color: ".$border_color.";";
		}

		if($title_color != "") {
			$title_style .= "color:".$title_color.";";
		}

		if($title_size != "") {
			$title_style .= "font-size: ".$title_size."px;";
		}

		$html .= '<div class="q_icon_list">';
		if($icon_pack == 'font_awesome' && $fa_icon != ''){

			$html .= '<i class="fa '.$fa_icon.' '.$icon_classes.' '.$border_type.'" style="'.$icon_style.'"></i>';

		} elseif($icon_pack == 'font_elegant' && $fe_icon != ''){

			$html .= '<span class="q_font_elegant_icon '.$fe_icon.' '.$icon_classes.' '.$border_type.'" aria-hidden="true" style="'.$icon_style.'"></span>';
		}

		$html .= '<p class="'.$icon_classes.'" style="'.$title_style.'">'.$title.'</p>';
		$html .= '</div>';
		return $html;
	}
}
add_shortcode('icon_list_item', 'icon_list_item');

/* Image with text shortcode */

if (!function_exists('image_with_text')) {

	function image_with_text($atts, $content = null) {
		$args = array(
			"image" => "",
			"title" => "",
			"title_color" => "",
			"title_tag" => "h5"
		);
		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		$html = '';
		$html .= '<div class="image_with_text">';
		if (is_numeric($image)) {
			$image_src = wp_get_attachment_url($image);
		} else {
			$image_src = $image;
		}
		$html .= '<img src="' . $image_src . '" alt="' . $title . '" />';
		$html .= '<'.$title_tag.' ';
		if ($title_color != "") {
			$html .= 'style="color:' . $title_color . ';"';
		}
		$html .= '>' . $title . '</'.$title_tag.'>';
		$html .= '<span style="margin: 6px 0px;" class="separator transparent"></span>';
		$html .= do_shortcode($content);
		$html .= '</div>';

		return $html;
	}

	add_shortcode('image_with_text', 'image_with_text');
}

/* Image with text over shortcode */

if (!function_exists('interactive_banners')) {

	function interactive_banners($atts, $content = null) {
		$args = array(
			"layout_width"          => "",
			"image"                 => "",
			"icon_pack"             => "",
			"fa_icon"               => "",
			"fe_icon"               => "",
			"icon_custom_size"      => "55",
			"icon_color"            => "",
			"title"                 => "",
			"title_color"           => "",
			"title_size"            => "",
			"title_tag"             => "h5",
			"link"                  => "",
			"link_text"             => "SEE MORE",
			"target"                => "_self",
			"link_color"            => "",
			"link_border_color"     => "",
			"link_background_color" => ""
		);

		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		//init variables
		$html            = "";
		$title_styles    = "";
		$subtitle_styles = "";
		$line_styles     = "";
		$no_icon         = "";
		$icon_styles     = "";
		$link_style      = "";
		$icon_font_style = "";

		//generate styles
		if($title_color != "") {
			$title_styles .= "color: ".$title_color.";";
		}

		if($title_size != "") {
			$valid_title_size = (strstr($title_size, 'px', true)) ? $title_size : $title_size.'px';
			$title_styles .= "font-size: ".$valid_title_size.";";
		}

		$icon_styles .= "style='";

		if($icon_color != "") {
			$icon_styles .= "color: ".$icon_color.";";
		}

		if($icon_custom_size != "") {
			$icon_font_style .= ' font-size: '.$icon_custom_size;
			if(!strstr($icon_custom_size, 'px')) {
				$icon_font_style .= 'px;';
			}
			$icon_styles .= $icon_font_style;
		}

		$icon_styles .= "'";

		if (is_numeric($image)) {
			$image_src = wp_get_attachment_url($image);
		} else {
			$image_src = $image;
		}

		if(($icon_pack == '') || (($icon_pack == 'font_awesome') && ($fa_icon == '')) || (($icon_pack == 'font_elegant') && ($fe_icon == ''))){
			$no_icon = "no_icon";
		}

		if($link_color != ""){
			$link_style .= "color: ".$link_color.";";
		}

		if($link_border_color != ""){
			$link_style .= "border-color: ".$link_border_color.";";
		}

		if($link_background_color != ""){
			$link_style .= "background-color: ".$link_background_color.";";
		}

		//generate output
		$html .= '<div class="q_image_with_text_over '.$layout_width.'">';
		$html .= '<div class="shader"></div>';

		$html .= '<img src="' . $image_src . '" alt="' . $title . '" />';
		$html .= '<div class="text">';

		//title and subtitle html
		$html .= '<span class="front_holder">';
		$html .= '<span class="front_inner">';

		if($icon_pack == 'font_awesome' && $fa_icon != ""){
			$html .= '<i class="icon_holder fa '.$fa_icon.'" '.$icon_styles .'></i>';
		}
		elseif($icon_pack == 'font_elegant' && $fe_icon != ""){
			$html .= '<span class="icon_holder q_font_elegant_icon '.$fe_icon.'" aria-hidden="true" '.$icon_styles .'></span>';
		}

		$html .= '<'.$title_tag.' class="caption '.$no_icon.'" style="'.$title_styles.'">'.$title.'</'.$title_tag.'>';
		$html .= '</span>';
		$html .= '</span>';

		//image description html which appears on mouse hover
		$html .= '<span class="back_holder">';
		$html .= '<span class="back_inner">';
		$html .= '<div class="desc"><p class="desc_text">' . do_shortcode($content) .'</p>';

		if($link != ""){
			$html .= '<a class="qbutton medium" htef="'.$link.'" target="'.$target.'" style="'.$link_style.'">'.$link_text.'</a>';
		}

		$html .= '</div>';
		$html .= '</span>';
		$html .= '</span>';

		$html .= '</div>'; //close text div
		$html .= '</div>'; //close image_with_text_over

		return $html;
	}

	add_shortcode('interactive_banners', 'interactive_banners');
}

/* Latest posts shortcode */

if (!function_exists('latest_post')) {
	function latest_post($atts, $content = null) {
		global $qode_options;

		$blog_hide_comments = "";
		if (isset($qode_options['blog_hide_comments'])) {
			$blog_hide_comments = $qode_options['blog_hide_comments'];
		}

		$qode_like = "on";
		if (isset($qode_options['qode_like'])) {
			$qode_like = $qode_options['qode_like'];
		}

		$args = array(
			"type"       			=> "date_in_box",
			"number_of_posts"       => "",
			"number_of_columns"      => "",
			"rows"                  => "",
			"order_by"              => "",
			"order"                 => "",
			"category"              => "",
            "box_background_color"  => "",
            "show_post_format_icon" => "",
            "text_align"           => "",
			"text_length"           => "",
			"title_tag"             => "h5",
			"display_category"    	=> "0",
			"display_date"          => "1",
			"date_format"	        => "",
			"display_comments"      => "1",
			"display_like"          => "0",
			"display_share"         => "0",
			"display_author"		=> "1"
		);

		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		//get proper number of posts based on type param
		$posts_number =$type != 'boxes' ? $number_of_posts : $number_of_columns;

		$show_title_separator = false;
		if(isset($qode_options['blog_masonry_title_separator']) && $qode_options['blog_masonry_title_separator'] == 'yes') {
			$show_title_separator = true;
		}

		//run query to get posts
		$q = new WP_Query(array(
			'orderby' => $order_by,
			'order' => $order,
			'posts_per_page' => $posts_number,
			'category_name' => $category
		));

		//get number of columns class for boxes type
		$columns_number = "";
		if($type == 'boxes') {
			switch($number_of_columns) {
				case 2:
					$columns_number = 'two_columns';
					break;
				case 3:
					$columns_number = 'three_columns';
					break;
				case 4:
					$columns_number = 'four_columns';
					break;
				default:
					break;
			}
		}

		$boxes_classes = '';
		if($type == 'boxes' && $show_title_separator) {
			$boxes_classes .= 'with_title_separator';
		}

        $boxes_background_color = '';
        $boxes_background_color_padding = '';
        if($type == 'boxes' && $box_background_color != ''){
            $boxes_background_color = 'style="background-color:'.$box_background_color.';"';
            $boxes_background_color_padding = 'style="padding:0px 20px 20px 20px;"';
        }

		$html = "";
		$html .= "<div class='latest_post_holder $type $text_align $columns_number $boxes_classes'>";
		$html .= "<ul>";

		while ($q->have_posts()) : $q->the_post();
			$li_classes = "";

			$cat = get_the_category();
            $_post_format = get_post_format(get_the_ID());
            $icon_class = '';
            switch ($_post_format) {
                case "video":
                    $icon_class = 'icon_film';
                break;
                case "audio":
                    $icon_class = 'icon_volume-low';
                break;
                case "link":
                    $icon_class = 'icon_link';
                break;
                case "gallery":
                    $icon_class = 'icon_images';
                break;
                case "quote":
                    $icon_class = 'icon_quotations';
                break;
                default:
                    $icon_class = 'icon_document_alt';

            }

			$html .= '<li class="clearfix" '.$boxes_background_color.'>';
			if($type == "date_in_box") {
				$html .= '<div class="latest_post_date">';
				$html .= '<div class="post_publish_day">'.get_the_time('d').'</div>';
				$html .= '<div class="post_publish_month">'.get_the_time('M').'</div>';
				$html .= '</div>';
			}

			if($type == "boxes") {
				$html .= '<div class="boxes_image">';
				$html .= '<a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(), 'latest_post_boxes').'</a>';
                if($show_post_format_icon == 'yes'){
                    $html .= '<span class="q_font_elegant_holder q_icon_shortcode circle  "><span class="q_font_elegant_icon '.$icon_class.'"></span></span>';
                }
                $html .= '</div>';
			}

			$html .= '<div class="latest_post" '.$boxes_background_color_padding.'>';

			if($type == "image_in_box") {
				$html .= '<div class="latest_post_image clearfix">';
				$html .= '<a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(), 'latest_post_small_image').'</a>';
				$html .= '</div>';
			}

			$html .= '<div class="latest_post_text">';
			$html .= '<div class="latest_post_inner">';
			$html .= '<div class="latest_post_text_inner">';

			if($type != "minimal") {
				$html .= '<'.$title_tag.' class="latest_post_title "><a href="' . get_permalink() . '">' . get_the_title() . '</a></'.$title_tag.'>';
			}

			if($type != "minimal") {
				if($text_length != '0') {
					$excerpt = ($text_length > 0) ? substr(get_the_excerpt(), 0, intval($text_length)) : get_the_excerpt();

					if($type == 'boxes' && $display_author == 1) {
						$html .= '<div class="latest_post_author_holder"><a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'"><span class="icon_pencil-edit"></span>'.get_the_author_meta('display_name').'</a></div>';

						if($show_title_separator) {
							$html .= '<div class="separator small left"></div>';
						}
					}

					$html .= '<p class="excerpt">'.$excerpt.'...</p>';
				}
			}

			if($type == "minimal") {
				$html .= '<'.$title_tag.' class="latest_post_title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></'.$title_tag.'>';
			}

			$html .= '<div class="post_infos">';

			if($display_date == '1'){
				$date_format = $date_format != '' ? $date_format : 'm.d.Y';

				$html .= '<span class="post_info_section date_hour_holder">';
				$html .= '<span class="latest_post_info_icon icon_clock_alt"></span>';
				$html .= '<span class="date">' . get_the_time($date_format) . '</span>';
				$html .= '</span>';//close date_hour_holder
			}
			if($display_category == '1'){
				$html .= '<span class="post_info_section latest_post_categories">';
				$html .= '<span class="latest_post_info_icon icon_ribbon_alt"></span>';
				foreach ($cat as $categ) {
					$html .=' <a href="' . get_category_link($categ->term_id) . '">' . $categ->cat_name . ' </a> ';
				}

				$html .= '</span>'; //close span.latest_post_categories
			}

			//generate comments part of description
			if ($blog_hide_comments != "yes" && $display_comments == "1") {
				$comments_count = get_comments_number();

				switch ($comments_count) {
					case 0:
						$comments_count_text = __('No comment', 'qode');
						break;
					case 1:
						$comments_count_text = $comments_count . ' ' . __('Comment', 'qode');
						break;
					default:
						$comments_count_text = $comments_count . ' ' . __('Comments', 'qode');
						break;
				}

				$html .= '<span class="post_info_section latest_post_comments">';
				$html .= '<a class="post_comments" href="' . get_comments_link() . '">';
				$html .= '<span class="latest_post_info_icon icon_comment_alt"></span>';
				$html .= $comments_count_text;
				$html .= '</a>';//close post_comments
				$html .= '</span>'; //close soan.latest_post_comments
			}

			if($qode_like == "on" && function_exists('qode_like')) {
				if($display_like == '1'){
					$html .= '<span class="post_info_section blog_like">';
					$html .= qode_like_latest_posts();
					$html .= '</span>';
				}
			}

			if($display_share == '1'){
				$html .= do_shortcode('[social_share]');
			}

			$html .= '</div>'; //close post_infos span

			$html .= '</div>'; //close latest_post_text_inner span
			$html .= '</div>'; //close latest_post_inner div
			$html .= '</div>'; //close latest_post_text div
			$html .= '</div>'; //close latest_post div

		endwhile;
		wp_reset_query();

		$html .= "</ul></div>";
		return $html;
	}

	add_shortcode('latest_post', 'latest_post');
}

/* Line graph shortcode */

if (!function_exists('line_graph')) {
	function line_graph($atts, $content = null) {
		global $qode_options;
		extract(shortcode_atts(array("type" => "rounded", "custom_color" => "", "labels" => "", "width" => "750", "height" => "350", "scale_steps" => "6", "scale_step_width" => "20"), $atts));
		$id = mt_rand(1000, 9999);
		if($type == "rounded"){
			$bezierCurve = "true";
		}else{
			$bezierCurve = "false";
		}

		$id = mt_rand(1000, 9999);
		$html = "<div class='q_line_graf_holder'><div class='q_line_graf'><canvas id='lineGraph".$id."' height='".$height."' width='".$width."'></canvas></div><div class='q_line_graf_legend'><ul>";
		$line_graph_array = explode(";", $content);
		for ($i = 0 ; $i < count($line_graph_array) ; $i = $i + 1){
			$line_graph_el = explode(",", $line_graph_array[$i]);
			$html .=  "<li><div class='color_holder' style='background-color: ".trim($line_graph_el[0]).";'></div><p style='color: ".$custom_color.";'>".trim($line_graph_el[1])."</p></li>";
		}
		$html .=  "</ul></div></div><script>var lineGraph".$id." = {labels : [";
		$line_graph_labels_array = explode(",", $labels);
		for ($i = 0 ; $i < count($line_graph_labels_array) ; $i = $i + 1){
			if ($i > 0) $html .= ",";
			$html .=  '"'.$line_graph_labels_array[$i].'"';
		}
		$html .= "],";
		$html .= "datasets : [";
		$line_graph_array = explode(";", $content);
		for ($i = 0 ; $i < count($line_graph_array) ; $i = $i + 1){
			$line_graph_el = explode(",", $line_graph_array[$i]);
			if ($i > 0) $html .= ",";
			$values = "";
			for ($j = 2 ; $j < count($line_graph_el) ; $j = $j + 1){
				if ($j > 2) $values .= ",";
				$values .= $line_graph_el[$j];
			}
			$color = qode_hex2rgb(trim($line_graph_el[0]));
			$html .=  "{fillColor: 'rgba(".$color[0].",".$color[1].",".$color[2].",0.7)',data:[".$values."]}";
		}
		if(!empty($qode_options['text_fontsize'])){
			$text_fontsize = $qode_options['text_fontsize'];
		}else{
			$text_fontsize = 15;
		}
		if(!empty($qode_options['text_color']) && $custom_color == ""){
			$text_color = $qode_options['text_color'];
		} else if(empty($qode_options['text_color']) && $custom_color != ""){
			$text_color = $custom_color;
		} else if(!empty($qode_options['text_color']) && $custom_color != ""){
			$text_color = $custom_color;
		}else{
			$text_color = '#818181';
		}
		$html .= "]};
			var \$j = jQuery.noConflict();
			\$j(document).ready(function() {
				if(\$j('.touch .no_delay').length){
					new Chart(document.getElementById('lineGraph".$id."').getContext('2d')).Line(lineGraph".$id.",{scaleOverride : true,
					scaleStepWidth : ".$scale_step_width.",
					scaleSteps : ".$scale_steps.",
					bezierCurve : ".$bezierCurve.",
					pointDot : false,
					scaleLineColor: '#505050',
					scaleFontColor : '".$text_color."',
					scaleFontSize : ".$text_fontsize.",
					scaleGridLineColor : '#e1e1e1',
					datasetStroke : false,
					datasetStrokeWidth : 0,
					animationSteps : 120,});
				}else{
					\$j('#lineGraph".$id."').appear(function() {
						new Chart(document.getElementById('lineGraph".$id."').getContext('2d')).Line(lineGraph".$id.",{scaleOverride : true,
						scaleStepWidth : ".$scale_step_width.",
						scaleSteps : ".$scale_steps.",
						bezierCurve : ".$bezierCurve.",
						pointDot : false,
						scaleLineColor: '#000000',
						scaleFontColor : '".$text_color."',
						scaleFontSize : ".$text_fontsize.",
						scaleGridLineColor : '#e1e1e1',
						datasetStroke : false,
						datasetStrokeWidth : 0,
						animationSteps : 120,});
					},{accX: 0, accY: -200});
				}						
			});
		</script>";
		return $html;
	}
}
add_shortcode('line_graph', 'line_graph');

/* Message shortcode */

if (!function_exists('message')) {
	function message($atts, $content = null) {
		global $qode_options_theme18;

		$args = array(
			"type"                  => "",
			"background_color"      => "",
			"border_color"          => "",
			"border_width"          => "",
			"icon_pack"             => "",
			"fa_icon"               => "",
			"fe_icon"               => "",
			"icon_size"            	=> "fa-2x",
			"icon_custom_size"      => "",
			"icon_color"            => "",
			"icon_background_color" => "",
			"custom_icon"           => "",
			"close_button_style"    => ""
		);
		extract(shortcode_atts($args, $atts));

		//init variables
		$html               = "";
		$icon_html          = "";
		$message_classes    = "";
		$message_styles     = "";
		$icon_styles        = "";

		if($type == "with_icon"){
			$message_classes .= " with_icon";
		}

		if($background_color != "") {
			$message_styles .= "background-color: ".$background_color.";";
		}

		if($border_color != "") {
			if($border_width != ""){
				$message_styles .= "border: ".$border_width."px solid ".$border_color.";";
			} else {
				$message_styles .= "border: 2px solid ".$border_color.";";
			}
		}

		if($icon_color != "") {
			$icon_styles .= "color: ".$icon_color;
		}

		if($icon_background_color != "") {
			$icon_styles .= " background-color: ".$icon_background_color;
		}

		if($icon_custom_size != "") {
			$icon_font_style = ' font-size: '.$icon_custom_size;
			if(!strstr($icon_custom_size, 'px')) {
				$icon_font_style .= 'px;';
			}
			$icon_styles .= $icon_font_style;
		}

		$html .= "<div class='q_message ".$message_classes."' style='".$message_styles."'>";
		$html .= "<div class='q_message_inner'>";
		if($type == "with_icon"){
			$icon_html .= '<div class="q_message_icon_holder"><div class="q_message_icon"><div class="q_message_icon_inner">';
			if($custom_icon != "") {
				if(is_numeric($custom_icon)) {
					$custom_icon_src = wp_get_attachment_url( $custom_icon );
				} else {
					$custom_icon_src = $custom_icon;
				}

				$icon_html .= '<img src="' . $custom_icon_src . '" alt="">';
			} elseif($icon_pack == 'font_awesome' && $fa_icon != "") {
				$icon_html .= "<i class='fa ".$fa_icon." ". $icon_size . "' style='".$icon_styles."'></i>";
			} elseif($icon_pack == 'font_elegant' && $fe_icon != ""){
				$icon_html .= "<span class='q_font_elegant_icon ".$fe_icon."' aria-hidden='true' style='".$icon_styles ."'></span>";
			}
			$icon_html .= '</div></div></div>';
		}

		$html .= $icon_html;

		$html .= "<a href='#' class='close'>";
		$html .= "<i class='q_font_elegant_icon icon_close ".$close_button_style."'></i>";
		$html .= "</a>"; //close a.close

		$html .= "<div class='message_text_holder'><div class='message_text'><div class='message_text_inner'>".do_shortcode($content)."</div></div></div>";

		$html .= "</div></div>"; //close message text div
		return $html;
	}
}
add_shortcode('message', 'message');

/* Ordered List shortcode */

if (!function_exists('ordered_list')) {
	function ordered_list($atts, $content = null) {
		$html =  "<div class=ordered>" . $content . "</div>";
		return $html;
	}
}
add_shortcode('ordered_list', 'ordered_list');

/* Pie Chart shortcode */

if (!function_exists('pie_chart')) {

	function pie_chart($atts, $content = null) {
		$args = array(
			"title"                 => "",
			"title_color"           => "",
			"title_tag"             => "h5",
			"percent"               => "",
			"percentage_color"      => "",
			"percent_font_size"     => "",
			"percent_font_weight"   => "",
			"active_color"          => "",
			"noactive_color"        => "",
			"line_width"            => "",
			"text"                  => "",
			"text_color"            => "",
			"separator"           	=> "yes",
			"separator_color"       => ""
		);

		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		$html = '';
		$html .= '<div class="q_pie_chart_holder"><div class="q_percentage" data-percent="' . $percent . '" data-linewidth="' . $line_width . '" data-active="' . $active_color . '" data-noactive="' . $noactive_color . '"';
		if ($percentage_color != "" || $percent_font_size != "" || $percent_font_weight != "") {
			$html .= ' style="';

			if($percentage_color != ""){
				$html .= 'color:'.$percentage_color.';';
			}
			if($percent_font_size != ""){
				$html .= 'font-size:'.$percent_font_size.'px;';
			}
			if($percent_font_weight != ""){
				$html .= 'font-weight:'.$percent_font_weight.';';
			}
			$html .= '"';
		}
		$html .= '><span class="tocounter">' . $percent . '</span>';
		$html .= '</div><div class="pie_chart_text">';
		if ($title != "") {
			$html .= '<'.$title_tag.' class="pie_title"';
			if ($title_color != "") {
				$html .= ' style="color: ' . $title_color . ';"';
			}
			$html .= '>' . $title . '</'.$title_tag.'>';
		}
		$separator_styles = "";
		if($separator_color != "") {
			$separator_styles .= " style='background-color: ".$separator_color.";'";
		}

		if($separator == "yes") {
			$html .= '<span class="separator small"'.$separator_styles.'"></span>';
		}

		if ($text != "") {
			$html .= '<p';
			if($text_color != ""){
				$html .= ' style="color: '.$text_color.';"';
			}
			$html .= '>' . $text . '</p>';
		}
		$html .= "</div></div>";
		return $html;
	}

}
add_shortcode('pie_chart', 'pie_chart');

/* Pie Chart With Icon shortcode */

if (!function_exists('pie_chart_with_icon')) {

	function pie_chart_with_icon($atts, $content = null) {

		global $qode_options_theme18;

		$args = array(
			"percent"         => "",
			"active_color"    => "",
			"noactive_color"  => "",
			"line_width"      => "",
			"icon_pack"       => "",
			"fa_icon"         => "",
			"fe_icon"         => "",
			"icon_color"      => "",
			"title"           => "",
			"title_color"     => "",
			"title_tag"       => "h5",
			"text"            => "",
			"text_color"      => ""
		);

		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		$html = '';

		$html .= '<div class="q_pie_chart_with_icon_holder"><div class="q_percentage_with_icon" data-percent="'.$percent.'" data-linewidth="'.$line_width.'" data-active="'.$active_color.'" data-noactive="'.$noactive_color.'">';

		if($icon_pack == 'font_awesome' && $fa_icon != ""){
			$html .= '<i class="fa '.$fa_icon.'"';


			if ($icon_color != "") {
				$html .= ' style="color: ' . $icon_color . ';"';
			}
			$html .= '></i>';
		}

		elseif($icon_pack == 'font_elegant' && $fe_icon != ""){
			$html .= '<span class="q_font_elegant_icon '.$fe_icon.'"';


			if ($icon_color != "") {
				$html .= ' style="color: ' . $icon_color . ';"';
			}
			$html .= '></span>';
		}

		$html .= '</div><div class="pie_chart_text">';
		if ($title != "") {
			$html .= '<'.$title_tag.' class="pie_title"';
			if ($title_color != "") {
				$html .= ' style="color: ' . $title_color . ';"';
			}
			$html .= '>' . $title . '</'.$title_tag.'>';
		}

		if ($text != "") {
			$html .= '<p ';
			if ($text_color != "") {
				$html .= ' style="color: ' . $text_color . ';"';
			}
			$html .= '>' . $text . '</p>';
		}
		$html .= "</div></div>";
		return $html;
	}
}
add_shortcode('pie_chart_with_icon', 'pie_chart_with_icon');

/* Pie Chart Full shortcode */

if (!function_exists('pie_chart2')) {
	function pie_chart2($atts, $content = null) {
		extract(shortcode_atts(array("width" => "120", "height" => "120", "color" => ""), $atts));
		$id = mt_rand(1000, 9999);
		$html = "<div class='q_pie_graf_holder'><div class='q_pie_graf'><canvas id='pie".$id."' height='".$height."' width='".$width."'></canvas></div><div class='q_pie_graf_legend'><ul>";
		$pie_chart_array = explode(";", $content);
		for ($i = 0 ; $i < count($pie_chart_array) ; $i = $i + 1){
			$pie_chart_el = explode(",", $pie_chart_array[$i]);
			$html .= "<li><div class='color_holder' style='background-color: ".trim($pie_chart_el[1]).";'></div><p style='color: ".$color.";'>".trim($pie_chart_el[2])."</p></li>";
		}
		$html .= "</ul></div></div><script>var pie".$id." = [";
		$pie_chart_array = explode(";", $content);
		for ($i = 0 ; $i < count($pie_chart_array) ; $i = $i + 1){
			$pie_chart_el = explode(",", $pie_chart_array[$i]);
			if ($i > 0) $html .= ",";
			$html .= "{value: ".trim($pie_chart_el[0]).",color:'".trim($pie_chart_el[1])."'}";
		}
		$html .= "];
		var \$j = jQuery.noConflict();
		\$j(document).ready(function() {
			if(\$j('.touch .no_delay').length){
				new Chart(document.getElementById('pie".$id."').getContext('2d')).Pie(pie".$id.",{segmentStrokeColor : 'transparent',});
			}else{
				\$j('#pie".$id."').appear(function() {
					new Chart(document.getElementById('pie".$id."').getContext('2d')).Pie(pie".$id.",{segmentStrokeColor : 'transparent',});
				},{accX: 0, accY: -200});
			}
		});
	</script>";
		return $html;
	}
}
add_shortcode('pie_chart2', 'pie_chart2');


/* Pie Chart Doughnut shortcode */

if (!function_exists('pie_chart3')) {
	function pie_chart3($atts, $content = null) {
		extract(shortcode_atts(array("width" => "120", "height" => "120", "color" => ""), $atts));
		$id = mt_rand(1000, 9999);
		$html = "<div class='q_pie_graf_holder'><div class='q_pie_graf'><canvas id='pie".$id."' height='".$height."' width='".$width."'></canvas></div><div class='q_pie_graf_legend'><ul>";
		$pie_chart_array = explode(";", $content);
		for ($i = 0 ; $i < count($pie_chart_array) ; $i = $i + 1){
			$pie_chart_el = explode(",", $pie_chart_array[$i]);
			$html .= "<li><div class='color_holder' style='background-color: ".trim($pie_chart_el[1]).";'></div><p style='color: ".$color.";'>".trim($pie_chart_el[2])."</p></li>";
		}
		$html .= "</ul></div></div><script>var pie".$id." = [";
		$pie_chart_array = explode(";", $content);
		for ($i = 0 ; $i < count($pie_chart_array) ; $i = $i + 1){
			$pie_chart_el = explode(",", $pie_chart_array[$i]);
			if ($i > 0) $html .= ",";
			$html .= "{value: ".trim($pie_chart_el[0]).",color:'".trim($pie_chart_el[1])."'}";
		}
		$html .= "];
		var \$j = jQuery.noConflict();
		\$j(document).ready(function() {
			if(\$j('.touch .no_delay').length){
				new Chart(document.getElementById('pie".$id."').getContext('2d')).Doughnut(pie".$id.",{segmentStrokeColor : 'transparent',});
			}else{
				\$j('#pie".$id."').appear(function() {
					new Chart(document.getElementById('pie".$id."').getContext('2d')).Doughnut(pie".$id.",{segmentStrokeColor : 'transparent',});
				},{accX: 0, accY: -200});
			}							
		});
	</script>";
		return $html;
	}
}
add_shortcode('pie_chart3', 'pie_chart3');

/* Portfolio list shortcode */

if (!function_exists('portfolio_list')) {

	function portfolio_list($atts, $content = null) {

		global $wp_query;
		global $qode_options;
		$portfolio_qode_like = "on";
		if (isset($qode_options['portfolio_qode_like'])) {
			$portfolio_qode_like = $qode_options['portfolio_qode_like'];
		}

		$args = array(
			"type"                  			=> "standard",
			"box_border"            			=> "",
			"box_background_color"  			=> "",
			"box_border_color"      			=> "",
			"box_border_width"      			=> "",
			"columns"               			=> "3",
			"image_size"            			=> "",
			"order_by"              			=> "menu_order",
			"order"                 			=> "ASC",
			"number"                			=> "-1",
			"filter"                			=> "no",
			"filter_color"          			=> "",
			"lightbox"              			=> "yes",
			"category"              			=> "",
			"category_color"        			=> "",
			"selected_projects"     			=> "",
			"show_load_more"        			=> "yes",
			"title_tag"             			=> "h5",
			"title_color"           			=> "",
			"separator_after_title" 			=> "",
			"separator_color"					=> "",
			"separator_thickness"				=> "",
			"separator_width"					=> "",
			"separator_type"					=> "",
			"text_align"            			=> "",
			"features_icons_background_color"	=> "",
			"features_icons_color"				=> ""
		);

		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		$html = "";

		$_type_class = '';
		$_portfolio_space_class = '';
		$_portfolio_masonry_with_space_class = '';
		if ($type == "hover_text") {
			$_type_class = " hover_text";
			$_portfolio_space_class = "portfolio_with_space portfolio_with_hover_text";
		} elseif ($type == "standard" || $type == "masonry_with_space"){
			$_type_class = " standard";
			$_portfolio_space_class = "portfolio_with_space portfolio_standard";
			if($type == "masonry_with_space"){
				$_portfolio_masonry_with_space_class = ' masonry_with_space';
			}
		} elseif ($type == "standard_no_space"){
			$_type_class = " standard_no_space";
			$_portfolio_space_class = "portfolio_no_space portfolio_standard";
		} elseif ($type == "hover_text_no_space"){
			$_type_class = " hover_text no_space";
			$_portfolio_space_class = "portfolio_no_space portfolio_with_hover_text";
		}

		$portfolio_box_style = "";
		$portfolio_description_class = "";
		if($box_border == "yes" || $box_background_color != ""){

			$portfolio_box_style .= "style=";
			if($box_border == "yes"){
				$portfolio_box_style .= "border-style:solid;";
				if($box_border_color != "" ){
					$portfolio_box_style .= "border-color:" . $box_border_color . ";";
				}
				if($box_border_width != "" ){
					$portfolio_box_style .= "border-width:" . $box_border_width . "px;";
				}
			}
			if($box_background_color != ""){
				$portfolio_box_style .= "background-color:" . $box_background_color . ";";
			}
			$portfolio_box_style .= "'";

			$portfolio_description_class .= 'with_padding';

			$_portfolio_space_class = ' with_description_background';

		}

		if($text_align !== '') {
			$portfolio_description_class .= ' text_align_'.$text_align;
		}

		$filter_style = "";
		if($filter_color != ""){
			$filter_style = " style='";
			$filter_style .= "color:$filter_color";
			$filter_style .= "'";
		}

		$category_style = array();
		if($category_color != '') {
			$category_style[] = 'color: '.$category_color;
		}

		if(is_array($category_style) && count($category_style)) {
			$category_style = 'style="'.implode(';', $category_style).'"';
		} else {
			$category_style = '';
		}

		$separator_html = '';
		$portfolio_has_separator = '';
		if($separator_after_title !== '') {
			$portfolio_has_separator == $separator_after_title;
		} elseif(isset($qode_options['portfolio_separator_after_title']) && $qode_options['portfolio_separator_after_title'] !== '') {
			$portfolio_has_separator = $qode_options['portfolio_separator_after_title'];
		}

		if($portfolio_has_separator == 'yes') {
			$separator_after_title_styles = array();
			if($separator_color !== '') {
				$separator_after_title_styles[] = 'border-bottom-color: '.$separator_color;
			}

			if($separator_thickness !== '') {
				$separator_after_title_styles[] = 'border-bottom-width: '.$separator_thickness.'px';
			}

			if($separator_type !== '') {
				$separator_after_title_styles[] = 'border-style: '.$separator_type;
			}

			if($separator_width !== '') {
				$separator_after_title_styles[] = 'width: '.$separator_width.'px';
			}

			if(is_array($separator_after_title_styles) && count($separator_after_title_styles)) {
				$separator_after_title_styles = 'style="'.implode(';', $separator_after_title_styles).'"';
			} else {
				$separator_after_title_styles = '';
			}

			$separator_html .= '<span class="separator small" '.$separator_after_title_styles.'></span>';
		}

		$title_styles = array();
		if($title_color !== '') {
			$title_styles[] = 'color: '.$title_color;
		}

		if(count($title_styles)) {
			$title_styles = 'style="'.implode(';', $title_styles).'"';
		} else {
			$title_styles = '';
		}

		$features_icons_styles = array();
		if($features_icons_background_color !== '') {
			$features_icons_styles[] = 'background-color: '.$features_icons_background_color;
		}

		if($features_icons_color !== '') {
			$features_icons_styles[] = 'color: '.$features_icons_color;
		}

		if(is_array($features_icons_styles) && count($features_icons_styles)) {
			$features_icons_styles = 'style="'.implode(';' ,$features_icons_styles).'"';
		} else {
			$features_icons_styles = '';
		}

		if($type != 'masonry') {
			$html .= "<div class='projects_holder_outer v$columns $_portfolio_space_class $_portfolio_masonry_with_space_class'>";
			if ($filter == "yes") {

				if($type == 'masonry_with_space'){
					$html .= "<div class='filter_outer'>";
					$html .= "<div class='filter_holder'>
						<ul>
						<li class='filter' data-filter='*'><span>" . __('All', 'qode') . "</span></li>";
					if ($category == "") {
						$args = array(
							'parent' => 0
						);
						$portfolio_categories = get_terms('portfolio_category', $args);
					} else {
						$top_category = get_term_by('slug', $category, 'portfolio_category');
						$term_id = '';
						if (isset($top_category->term_id))
							$term_id = $top_category->term_id;
						$args = array(
							'parent' => $term_id
						);
						$portfolio_categories = get_terms('portfolio_category', $args);
					}
					foreach ($portfolio_categories as $portfolio_category) {
						$html .= "<li class='filter' data-filter='.portfolio_category_$portfolio_category->term_id'><span>$portfolio_category->name</span>";
						$args = array(
							'child_of' => $portfolio_category->term_id
						);
						$html .= '</li>';
					}
					$html .= "</ul></div>";
					$html .= "</div>";

				}else{
					$html .= "<div class='filter_outer'>";
					$html .= "<div class='filter_holder'>
                            <ul>
                            <li class='filter' data-filter='all'><span". $filter_style .">" . __('All', 'qode') . "</span></li>";
					if ($category == "") {
						$args = array(
							'parent' => 0
						);
						$portfolio_categories = get_terms('portfolio_category', $args);
					} else {
						$top_category = get_term_by('slug', $category, 'portfolio_category');
						$term_id = '';
						if (isset($top_category->term_id))
							$term_id = $top_category->term_id;
						$args = array(
							'parent' => $term_id
						);
						$portfolio_categories = get_terms('portfolio_category', $args);
					}
					foreach ($portfolio_categories as $portfolio_category) {
						$html .= "<li class='filter' data-filter='portfolio_category_$portfolio_category->term_id'><span". $filter_style .">$portfolio_category->name</span>";
						$args = array(
							'child_of' => $portfolio_category->term_id
						);
						$html .= '</li>';
					}
					$html .= "</ul></div>";
					$html .= "</div>";
				}


			}

			$html .= "<div class='projects_holder clearfix v$columns$_type_class'>\n";
			if (get_query_var('paged')) {
				$paged = get_query_var('paged');
			} elseif (get_query_var('page')) {
				$paged = get_query_var('page');
			} else {
				$paged = 1;
			}
			if ($category == "") {
				$args = array(
					'post_type' => 'portfolio_page',
					'orderby' => $order_by,
					'order' => $order,
					'posts_per_page' => $number,
					'paged' => $paged
				);
			} else {
				$args = array(
					'post_type' => 'portfolio_page',
					'portfolio_category' => $category,
					'orderby' => $order_by,
					'order' => $order,
					'posts_per_page' => $number,
					'paged' => $paged
				);
			}
			$project_ids = null;
			if ($selected_projects != "") {
				$project_ids = explode(",", $selected_projects);
				$args['post__in'] = $project_ids;
			}
			query_posts($args);
			if (have_posts()) : while (have_posts()) : the_post();
				$terms = wp_get_post_terms(get_the_ID(), 'portfolio_category');
				$html .= "<article class='mix ";
				foreach ($terms as $term) {
					$html .= "portfolio_category_$term->term_id ";
				}

				$title = get_the_title();
				$featured_image_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); //original size
				$large_image = $featured_image_array[0];
				$slug_list_ = "pretty_photo_gallery";

				//get proper image size
				switch($image_size) {
					case 'landscape':
						$thumb_size = 'portfolio-landscape';
						break;
					case 'portrait':
						$thumb_size = 'portfolio-portrait';
						break;
					case 'square':
						$thumb_size = 'portfolio-square';
						break;
					case 'full':
						$thumb_size = 'full';
						break;
					default:
						$thumb_size = 'portfolio-landscape';
						break;
				}

				if($type == "masonry_with_space"){
					$thumb_size = 'portfolio_masonry_with_space';
				}

				$custom_portfolio_link = get_post_meta(get_the_ID(), 'qode_portfolio-external-link', true);
				$portfolio_link = $custom_portfolio_link != "" ? $custom_portfolio_link : get_permalink();
				$target = $custom_portfolio_link != "" ? '_blank' : '_self';

				$html .="'>";

				$html .= "<div class='image_holder'>";
				$html .= "<a class='portfolio_link_for_touch' href='".$portfolio_link."' target='".$target."'>";
				$html .= "<span class='image'>";
				$html .= get_the_post_thumbnail(get_the_ID(), $thumb_size);
				$html .= "</span>";
				$html .= "</a>";

				if ($type == "standard" || $type == "standard_no_space" || $type == "masonry_with_space") {
					$html .= "<span class='text_holder'>";
					$html .= "<span class='text_outer'>";
					$html .= "<span class='text_inner'>";
					$html .= "<span class='feature_holder'>";
					$html .= '<span class="feature_holder_icons">';
					if ($lightbox == "yes") {
						$html .= "<a class='lightbox hover_icon_holder' title='" . $title . "' href='" . $large_image . "' data-rel='prettyPhoto[" . $slug_list_ . "]'><span ".$features_icons_styles." class='hover_icon icon_search'></span></a>";
					}
					$html .= "<a class='preview hover_icon_holder' href='" . $portfolio_link . "' target='".$target."'><span ".$features_icons_styles." class='hover_icon icon_link_alt'></span></a>";
					if ($portfolio_qode_like == "on") {
						$html .= "<span ".$features_icons_styles." class='portfolio_like hover_icon_holder'>";

						if (function_exists('qode_like_portfolio_list')) {
							$html .= qode_like_portfolio_list(get_the_ID());
						}
						$html .= "</span>";
					}
					$html .= "</span>";
					$html .= "</span></span></span></span>";


				} else if ($type == "hover_text" || $type == "hover_text_no_space") {

					$html .= "<span class='text_holder'>";
					$html .= "<span class='text_outer'>";
					$html .= "<span class='text_inner'>";
					$html .= '<div class="hover_feature_holder_title">';
					$html .= '<div class="hover_feature_holder_title_inner">';
					$html .= '<'.$title_tag.' class="portfolio_title"><a href="' . $portfolio_link . '" '.$title_styles.' target="'.$target.'">' . get_the_title() . '</a></'.$title_tag.'>';
					$html .= $separator_html;
					$html .= '<span '.$category_style.' class="project_category">';
					$k = 1;
					foreach ($terms as $term) {
						$html .= "$term->name";
						if (count($terms) != $k) {
							$html .= ', ';
						}
						$k++;
					}

					$html .= '</span>'; //close span.project_category
					$html .= '</div>'; //close div.hover_feature_holder_title_inner
					$html .= '</div>'; //close div.hover_feature_holder_title

					$html .= "<span class='feature_holder'>";
					$html .= '<span class="feature_holder_icons">';
					if ($lightbox == "yes") {
						$html .= "<a class='lightbox hover_icon_holder' title='" . $title . "' href='" . $large_image . "' data-rel='prettyPhoto[" . $slug_list_ . "]'><span ".$features_icons_styles." class='hover_icon icon_search'></span></a>";
					}
					$html .= "<a class='preview hover_icon_holder' href='" . $portfolio_link . "' target='".$target."'><span ".$features_icons_styles." class='hover_icon icon_link_alt'></span></a>";
					if ($portfolio_qode_like == "on") {
						$html .= "<span ".$features_icons_styles." class='portfolio_like hover_icon_holder'>";

						if (function_exists('qode_like_portfolio_list')) {
							$html .= qode_like_portfolio_list(get_the_ID());
						}
						$html .= "</span>";
					}
					$html .= "</span>"; //close span.feature_holder_icons
					$html .= "</span>"; //close span.feature_holder
					$html .= "</span>"; //close span.text_inner
					$html .= "</span>"; //close span.text_outer
					$html .= "</span>"; //close span.text_holder

				}
				$html .= "</div>";
				if ($type == "standard" || $type == "standard_no_space" || $type == "masonry_with_space") {
					$html .= "<div class='portfolio_description ".$portfolio_description_class."'". $portfolio_box_style .">";
					$html .= '<'.$title_tag.' class="portfolio_title"><a href="' . $portfolio_link . '" '.$title_styles.' target="'.$target.'">' . get_the_title() . '</a></'.$title_tag.'>';
					$html .= $separator_html;
					$html .= '<span '.$category_style.' class="project_category">';
					$k = 1;
					foreach ($terms as $term) {
						$html .= "$term->name";
						if (count($terms) != $k) {
							$html .= ', ';
						}
						$k++;
					}
					$html .= '</span>';
					$html .= '</div>';
				}

				$html .= "</article>\n";

			endwhile;

				$i = 1;
				while ($i <= $columns) {
					$i++;
					if ($columns != 1) {
						$html .= "<div class='filler'></div>\n";
					}
				}

			else:
				?>
				<p><?php _e('Sorry, no posts matched your criteria.', 'qode'); ?></p>
			<?php
			endif;


			$html .= "</div>";
			if (get_next_posts_link()) {
				if ($show_load_more == "yes" || $show_load_more == "") {
					$html .= '<div class="portfolio_paging"><span rel="' . $wp_query->max_num_pages . '" class="load_more">' . get_next_posts_link(__('Show more', 'qode')) . '</span></div>';
				}
			}
			$html .= "</div>";
			wp_reset_query();
		} else {
			if ($filter == "yes") {

				$html .= "<div class='filter_outer'>";
				$html .= "<div class='filter_holder'>
						<ul>
						<li class='filter' data-filter='*'><span>" . __('All', 'qode') . "</span></li>";
				if ($category == "") {
					$args = array(
						'parent' => 0
					);
					$portfolio_categories = get_terms('portfolio_category', $args);
				} else {
					$top_category = get_term_by('slug', $category, 'portfolio_category');
					$term_id = '';
					if (isset($top_category->term_id))
						$term_id = $top_category->term_id;
					$args = array(
						'parent' => $term_id
					);
					$portfolio_categories = get_terms('portfolio_category', $args);
				}
				foreach ($portfolio_categories as $portfolio_category) {
					$html .= "<li class='filter' data-filter='.portfolio_category_$portfolio_category->term_id'><span>$portfolio_category->name</span>";
					$args = array(
						'child_of' => $portfolio_category->term_id
					);
					$html .= '</li>';
				}
				$html .= "</ul></div>";
				$html .= "</div>";


			}
			$html .= "<div class='projects_masonry_holder'>";
			if (get_query_var('paged')) {
				$paged = get_query_var('paged');
			} elseif (get_query_var('page')) {
				$paged = get_query_var('page');
			} else {
				$paged = 1;
			}
			if ($category == "") {
				$args = array(
					'post_type' => 'portfolio_page',
					'orderby' => $order_by,
					'order' => $order,
					'posts_per_page' => $number,
					'paged' => $paged
				);
			} else {
				$args = array(
					'post_type' => 'portfolio_page',
					'portfolio_category' => $category,
					'orderby' => $order_by,
					'order' => $order,
					'posts_per_page' => $number,
					'paged' => $paged
				);
			}
			$project_ids = null;
			if ($selected_projects != "") {
				$project_ids = explode(",", $selected_projects);
				$args['post__in'] = $project_ids;
			}
			query_posts($args);
			if (have_posts()) : while (have_posts()) : the_post();
				$terms = wp_get_post_terms(get_the_ID(), 'portfolio_category');
				$featured_image_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); //original size
				$large_image = $featured_image_array[0];

				$custom_portfolio_link = get_post_meta(get_the_ID(), 'qode_portfolio-external-link', true);
				$portfolio_link = $custom_portfolio_link != "" ? $custom_portfolio_link : get_permalink();
				$target = $custom_portfolio_link != "" ? '_blank' : '_self';

				$masonry_size = "default";
				$masonry_size =  get_post_meta(get_the_ID(), "qode_portfolio_type_masonry_style", true);

				$image_size = "";
				if($masonry_size == "large_width"){
					$image_size = "portfolio_masonry_wide";
				}elseif($masonry_size == "large_height"){
					$image_size = "portfolio_masonry_tall";
				}elseif($masonry_size == "large_width_height"){
					$image_size = "portfolio_masonry_large";
				} else{
					$image_size = "portfolio_masonry_regular";
				}

				if($type == "masonry_with_space"){
					$image_size = "portfolio_masonry_with_space";
				}

				$slug_list_ = "pretty_photo_gallery";
				$title = get_the_title();
				$html .= "<article class='portfolio_masonry_item ";

				foreach ($terms as $term) {
					$html .= "portfolio_category_$term->term_id ";
				}

				$html .=" " . $masonry_size;
				$html .="'>";

				$html .= "<div class='image_holder'>";
				$html .= "<a class='portfolio_link_for_touch' href='".$portfolio_link."' target='".$target."'>";
				$html .= "<span class='image'>";
				$html .= get_the_post_thumbnail(get_the_ID(), $image_size);
				$html .= "</span>"; //close span.image
				$html .= "</a>"; //close a.portfolio_link_for_touch

				$html .= "<span class='text_holder'>";
				$html .= "<span class='text_outer'>";
				$html .= "<span class='text_inner'>";
				$html .= '<div class="hover_feature_holder_title">';
				$html .= '<div class="hover_feature_holder_title_inner">';
				$html .= '<'.$title_tag.' '.$title_styles.' class="portfolio_title"><a href="' . $portfolio_link . '" '.$title_styles.' target="'.$target.'">' . get_the_title() . '</a></'.$title_tag.'>';
				$html .= $separator_html;
				$html .= '<span '.$category_style.' class="project_category">';
				$k = 1;
				foreach ($terms as $term) {
					$html .= "$term->name";
					if (count($terms) != $k) {
						$html .= ', ';
					}
					$k++;
				}
				$html .= '</span>'; //close span.project_category
				$html .= '</div>'; //close div.hover_feature_holder_title_inner
				$html .= '</div>'; //close div.hover_feature_holder_title

				$html .= "<span class='feature_holder'>";
				$html .= '<span class="feature_holder_icons">';
				if ($lightbox == "yes") {
					$html .= "<a class='lightbox hover_icon_holder' title='" . $title . "' href='" . $large_image . "' data-rel='prettyPhoto[" . $slug_list_ . "]'><span ".$features_icons_styles." class='hover_icon icon_search'></span></a>";
				}
				$html .= "<a class='preview hover_icon_holder' href='" . $portfolio_link . "' target='".$target."'><span ".$features_icons_styles." class='hover_icon icon_link_alt'></span></a>";
				if ($portfolio_qode_like == "on") {
					$html .= "<span ".$features_icons_styles." class='portfolio_like hover_icon_holder'>";

					if (function_exists('qode_like_portfolio_list')) {
						$html .= qode_like_portfolio_list(get_the_ID());
					}
					$html .= "</span>";
				}
				$html .= "</span>"; //close span.feature_holder_icons
				$html .= "</span>"; //close span.feature_holder

				$html .= "</span>"; //close span.text_inner
				$html .= "</span>"; //close span.text_outer
				$html .= "</span>"; //close span.text_holder
				$html .= "</div>"; //close div.image_holder
				$html .= "</article>";

			endwhile;
			else:
				?>
				<p><?php _e('Sorry, no posts matched your criteria.', 'qode'); ?></p>
			<?php
			endif;
			wp_reset_query();
			$html .= "</div>";
		}
		return $html;
	}

}
add_shortcode('portfolio_list', 'portfolio_list');

/* Portfolio Slider shortcode */

if (!function_exists('portfolio_slider')) {
	function portfolio_slider( $atts, $content = null ) {

		global $qode_options;
		$portfolio_qode_like = "on";
		if (isset($qode_options['portfolio_qode_like'])) {
			$portfolio_qode_like = $qode_options['portfolio_qode_like'];
		}

		$args = array(
			"order_by"          =>  "menu_order",
			"order"             =>  "ASC",
			"number"            =>  "-1",
			"category"          =>  "",
			"selected_projects" =>  "",
			"lightbox"          =>  "",
			"title_tag"         =>  "h5",
			"separator"         =>  "",
			"image_size"        =>  "portfolio-square",
			"enable_navigation" =>  ""
		);
		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		$html = "";
		$lightbox_slug = 'portfolio_slider_'.rand();

		$html .= "<div class='portfolio_slider_holder clearfix'><div class='portfolio_slider'><ul class='portfolio_slides'>";

		if ($category == "") {
			$q = array(
				'post_type' => 'portfolio_page',
				'orderby' => $order_by,
				'order' => $order,
				'posts_per_page' => $number
			);
		} else {
			$q = array(
				'post_type' => 'portfolio_page',
				'portfolio_category' => $category,
				'orderby' => $order_by,
				'order' => $order,
				'posts_per_page' => $number
			);
		}

		$project_ids = null;
		if ($selected_projects != "") {
			$project_ids = explode(",", $selected_projects);
			$q['post__in'] = $project_ids;
		}

		query_posts($q);

		if ( have_posts() ) : $postCount = 0; while ( have_posts() ) : the_post();

			$title = get_the_title();
			$terms = wp_get_post_terms(get_the_ID(), 'portfolio_category');

			//get proper image size
			switch($image_size) {
				case 'landscape':
					$thumb_size = 'portfolio-landscape';
					break;
				case 'portrait':
					$thumb_size = 'portfolio-portrait';
					break;
				case 'square':
					$thumb_size = 'portfolio-square';
					break;
				case 'full':
					$thumb_size = 'full';
					break;
				default:
					$thumb_size = 'portfolio-landscape';
					break;
			}

			$featured_image_array = wp_get_attachment_image_src(get_post_thumbnail_id(), $thumb_size);
			$large_image = $featured_image_array[0];

			$custom_portfolio_link = get_post_meta(get_the_ID(), 'qode_portfolio-external-link', true);
			$portfolio_link = $custom_portfolio_link != "" ? $custom_portfolio_link : get_permalink();
			$target = $custom_portfolio_link != "" ? '_blank' : '_self';

			$html .= "<li class='item'>";

			$html .= "<div class='image_holder'>";
			$html .= "<span class='image'>";
			$html .= "<span class='image_pixel_hover'></span>";
			$html .= "<a href='" . $portfolio_link . "' target='".$target."'>";
			$html .= "<img src='".$large_image."' alt='".$title."'>";
			$html .= "</a>";
			$html .= "</span>"; /* close span.image */

			$html .= "<div class='hover_feature_holder'>";
			$html .= '<div class="hover_feature_holder_outer">';
			$html .= '<div class="hover_feature_holder_inner">';
			$html .= '<'.$title_tag.' class="portfolio_title"><a href="' . $portfolio_link . '" target="'.$target.'">' . get_the_title() . '</a></'.$title_tag.'>';
			$separator_class = "";
			if($separator == "no"){
				$separator_class = " transparent";
			}

			$html .= '<div class="project_category">';
			$k = 1;
			foreach ($terms as $term) {
				$html .= "$term->name";
				if (count($terms) != $k) {
					$html .= ', ';
				}
				$k++;
			}
			$html .= '</div>'; /* close div.project_category */
			$html .= '<div class="feature_holder_icons">';
			if ($lightbox == "yes") {
				$html .= "<a class='lightbox hover_icon_holder' title='" . $title . "' href='" . $large_image . "' data-rel='prettyPhoto[".$lightbox_slug."]'><span class='hover_icon icon_search'></span></a>";
			}
			$html .= "<a class='preview hover_icon_holder' href='" . $portfolio_link . "' target='".$target."'><span class='hover_icon icon_link_alt'></span></a>";

			if ($portfolio_qode_like == "on") {
				$html .= "<span class='portfolio_like hover_icon_holder'>";

				if (function_exists('qode_like_portfolio_list')) {
					$html .= qode_like_portfolio_list(get_the_ID());
				}
				$html .= "</span>";
			}

			$html .= '</div>'; // close div.feature_holder_icons
			$html .= '</div>'; /* close div.hover_feature_holder_inner */
			$html .= '</div>'; /* close div.hover_feature_holder_outer */
			$html .= "</div>"; /* close div.hover_feature_holder */
			$html .= "</div>"; /* close div.image_holder */

			$html .= "</li>";

			$postCount++;

		endwhile;

		else:
			$html .= __('Sorry, no posts matched your criteria.','qode');
		endif;

		wp_reset_query();

		$html .= "</ul>";
		if($enable_navigation){
			$html .= '<ul class="caroufredsel-direction-nav"><li><a id="caroufredsel-prev" class="caroufredsel-prev" href="#"><div><i class="fa fa-angle-left"></i></div></a></li><li><a class="caroufredsel-next" id="caroufredsel-next" href="#"><div><i class="fa fa-angle-right"></i></div></a></li></ul>';
		}
		$html .= "</div></div>";

		return $html;
	}
}
add_shortcode('portfolio_slider', 'portfolio_slider');

/* Progress bar horizontal shortcode */

if (!function_exists('progress_bar')) {

	function progress_bar($atts, $content = null) {
		$args = array(
			"title"                     => "",
			"title_color"               => "",
			"title_tag"                 => "h6",
			"title_custom_size"         => "",
			"percent"                   => "",
			"percent_color"             => "",
			"percent_font_size"         => "",
			"percent_font_weight"       => "",
			"active_background_color"   => "",
			"active_border_color"       => "",
			"noactive_background_color" => "",
			"height"                    => "",
			"border_radius"            	=> ""
		);

		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		//init variables
		$html                           = "";
		$progress_title_holder_styles   = "";
		$number_styles                  = "";
		$outer_progress_styles          = "";
		$percentage_styles              = "";

		//generate styles
		if ($title_color != "") {
			$progress_title_holder_styles .= "color: " . $title_color . ";";
		}

		if($title_custom_size != "") {
			$progress_title_holder_styles .= "font-size: ".$title_custom_size."px;";
		}

		if ($percent_color != "") {
			$number_styles .= "color: " . $percent_color . ";";
		}

		if ($percent_font_size != "") {
			$number_styles .= "font-size: " . $percent_font_size . "px;";
		}
		if ($percent_font_weight != "") {
			$number_styles .= "font-weight: " . $percent_font_weight . ";";
		}
		if ($height != "") {
			$valid_height = (strstr($height, 'px', true)) ? $height : $height . "px";
			$outer_progress_styles .= "height: " . $valid_height . ";";
			$percentage_styles .= "height: " . $valid_height . ";";
		}

		if ($border_radius != "") {
			$border_radius = (strstr($height, 'px', true)) ? $border_radius : $border_radius . "px";
			$outer_progress_styles .= "border-radius: " . $border_radius . ";-moz-border-radius: " . $border_radius . ";-webkit-border-radius: " . $border_radius . ";";
		}

		if ($noactive_background_color != "") {
			$outer_progress_styles .= "background-color: " . $noactive_background_color . ";";
		}

		if ($active_background_color != "") {
			$percentage_styles .= "background-color: " . $active_background_color . ";";
		}

		if($active_border_color) {
			$percentage_styles .= "border-color: " . $active_border_color . ";";
		}

		$html .= "<div class='q_progress_bar'>";
		$html .= "<{$title_tag} class='progress_title_holder clearfix' style='{$progress_title_holder_styles}'>";
		$html .= "<span class='progress_title'>";
		$html .= "<span>$title</span>";
		$html .= "</span>"; //close progress_title

		$html .= "<span class='progress_number' style='{$number_styles}'>";
		$html .= "<span>0</span>%</span>";
		$html .= "</{$title_tag}>"; //close progress_title_holder

		$html .= "<div class='progress_content_outer' style='{$outer_progress_styles}'>";
		$html .= "<div data-percentage='" . $percent . "' class='progress_content' style='{$percentage_styles}'>";
		$html .="</div>"; //close progress_content
		$html .= "</div>"; //close progress_content_outer

		$html .= "</div>"; //close progress_bar
		return $html;
	}

	add_shortcode('progress_bar', 'progress_bar');
}

/* Progress bar vertical shortcode */

if (!function_exists('progress_bar_vertical')) {

	function progress_bar_vertical($atts, $content = null) {
		$args = array(
			"title"                             => "",
			"title_color"                       => "",
			"title_tag"                         => "h5",
			"title_size"                        => "",
			"percent"                           => "100",
			"percentage_text_size"              => "",
			"percent_color"                     => "",
			"bar_color"                         => "",
			"bar_border_color"                  => "",
			"background_color"                  => "",
			"border_radius"     	            => "",
			"text"                              => ""
		);

		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		//init variables
		$html               = "";
		$title_styles       = "";
		$bar_styles         = "";
		$percentage_styles  = "";
		$bar_holder_styles  = "";

		//generate styles
		if($title_color != "") {
			$title_styles .= "color:".$title_color.";";
		}

		if($title_size != "") {
			$title_styles .= "font-size:".$title_size."px;";
		}

		//generate bar holder gradient styles
		if($background_color != "") {
			$bar_holder_styles .= "background-color: " . $background_color . ";";
		}

		if($border_radius != "") {
			$bar_holder_styles .= "border-radius: " . $border_radius . "px " . $border_radius . "px 0 0;border-radius: " . $border_radius . "px " . $border_radius . "px 0 0;border-radius: " . $border_radius . "px " . $border_radius . "px 0 0;";
		}

		//generate bar gradient styles
		if($bar_color != "") {
			$bar_styles .= "background-color: " . $bar_color . ";";
		}

		if($bar_border_color != "") {
			$bar_styles .= "border-color: ".$bar_border_color;
		}

		if($percentage_text_size != "") {
			$percentage_styles .= "font-size: ".$percentage_text_size."px;";

		}

		if($percent_color != "") {
			$percentage_styles .= "color: ".$percent_color.";";
		}

		$html .= "<div class='q_progress_bars_vertical'>";
		$html .= "<div class='progress_content_outer' style='".$bar_holder_styles."'>";
		$html .= "<div data-percentage='$percent' class='progress_content' style='".$bar_styles."'></div>";
		$html .= "</div>"; //close progress_content_outer
		$html .= "<{$title_tag} class='progress_title' style='".$title_styles."'>$title</{$title_tag}>";
		$html .= "<span class='progress_number' style='".$percentage_styles."'>";
		$html .= "<span>$percent</span>%";
		$html .= "</span>"; //close progress_number
		$html .= "<span class='progress_text'>".$text."</span>"; //close progress_number
		$html .= "</div>"; //close progress_bars_vertical

		return $html;
	}

}
add_shortcode('progress_bar_vertical', 'progress_bar_vertical');

/* Progress bars icon shortcode */

if (!function_exists('progress_bar_icon')) {
	function progress_bar_icon($atts, $content = null) {

		$args =  array(
			"icons_number"              => "",
			"active_number"             => "",
			"type"                      => "",
			"icon_pack"                 => "",
			"fa_icon"                   => "",
			"fe_icon"                   => "",
			"size"                      => "",
			"icon_color"                => "",
			"icon_active_color"         => "",
			"background_color"          => "",
			"background_active_color"   => "",
			"border_color"              => "",
			"border_active_color"       => ""
		);

		extract(shortcode_atts($args, $atts));
		$html =  "<div class='q_progress_bars_icons_holder'><div class='q_progress_bars_icons'><div class='q_progress_bars_icons_inner ".$type." ".$size;

		$html .= " clearfix' data-number='".$active_number."'>";

		$i = 0;
		while ($i < $icons_number) {
			$html .= "<div class='bar'><span class='bar_noactive fa-stack ";
			if($size != ""){
				if($size == "tiny"){
					$html .= "fa-lg";
				} else if($size == "small"){
					$html .= "fa-2x";
				} else if($size == "medium"){
					$html .= "fa-3x";
				} else if($size == "large"){
					$html .= "fa-4x";
				} else if($size == "very_large"){
					$html .= "fa-5x";
				}
			}
			$html .= "'";
			if($type == "circle" || $type == "square"){
				if($background_active_color != "" || $border_active_color != ""){
					$html .= " style='";
					if($background_active_color != ""){
						$html .= "background-color: ".$background_active_color.";";
					}
					if($border_active_color != ""){
						$html .= " border-color: ".$border_active_color.";";
					}
					$html .= "'";
				}
			}
			$html .= ">";

			if($icon_pack == 'font_awesome' && $fa_icon != ''){
				$html .= "<i class='fa fa-stack-1x ".$fa_icon."'";

				if($icon_active_color != ""){
					$html .= " style='color: ".$icon_active_color.";'";
				}

				$html .= "></i>";
			}
			elseif($icon_pack == 'font_elegant' && $fe_icon != ''){
				$html .= "<span class='q_font_elegant_icon ".$fe_icon."'";

				if($icon_active_color != ""){
					$html .= " style='color: ".$icon_active_color.";'";
				}

				$html .= "></span>";
			}

			$html .= "</span><span class='bar_active fa-stack ";
			if($size != ""){
				if($size == "tiny"){
					$html .= "fa-lg";
				} else if($size == "small"){
					$html .= "fa-2x";
				} else if($size == "medium"){
					$html .= "fa-3x";
				} else if($size == "large"){
					$html .= "fa-4x";
				} else if($size == "very_large"){
					$html .= "fa-5x";
				}
			}
			$html .= "'";
			if($type == "circle" || $type == "square"){
				if($background_color != "" || $border_color != ""){
					$html .= " style='";
					if($background_color != ""){
						$html .= "background-color: ".$background_color.";";
					}
					if($border_color != ""){
						$html .= " border-color: ".$border_color.";";
					}
					$html .= "'";
				}
			}
			$html .= ">";

			if($icon_pack == 'font_awesome' && $fa_icon != ''){
				$html .= "<i class='fa ".$fa_icon." fa-stack-1x'";

				if($icon_color != ""){
					$html .= " style='color: ".$icon_color.";'";
				}

				$html .= "></i>";
			}
			elseif($icon_pack == 'font_elegant' && $fa_icon != ''){
				$html .= "<span class='q_font_elegant_icon ".$fe_icon."'";

				if($icon_color != ""){
					$html .= " style='color: ".$icon_color.";'";
				}

				$html .= "></span>";
			}

			$html .= "</span></div>";


			$i++;
		}
		$html .= "</div></div></div>";
		return $html;
	}
}
add_shortcode('progress_bar_icon', 'progress_bar_icon');

/* Services shortcode */

//if (!function_exists('service')) {
//    /**
//     * @deprecated
//     * @param $atts
//     * @param null $content
//     * @return string
//     *
//     */
//    function service($atts, $content = null) {
//        $args = array(
//            "type"      => "top",
//            "title"     => "",
//            "color"     => "",
//            "link"      => "",
//            "target"    => "",
//            "animate"   => ""
//        );
//
//        extract(shortcode_atts($args, $atts));
//
//        //init variables
//        $html            = "";
//        $service_classes = "circle_item circle_{$type}";
//        $service_styles  = "";
//
//        //generate service classes
//        if($animate == "yes") {
//            $service_classes .= " fade_in_circle_holder";
//        }
//
//        //generate service styles
//        if($color != "") {
//            $service_styles .= "color: ".$color.";";
//        }
//
//        //generate output
//        $html .= '<div class="'.$service_classes.'">'; //open service div
//
//        if ($link == "") {
//            $html .= '<div class="circle fade_in_circle" style="'.$service_styles.'">'; //open circle div
//            $html .= '<div>' . $title . '</div>';
//            $html .= '</div>'; //close circle div
//        } else {
//            $html .= '<div class="circle hover fade_in_circle">'; //open circle div
//            $html .= '<a href="' . $link . '" target="' . $target . '" style="'.$service_styles.'">';
//            $html .= '<div>' . $title . '</div>';
//            $html .= '</a>'; //close circle link
//            $html .= '</div>'; //close circle div
//        }
//
//        $html .= '<div class="text">';
//        $html .= $content;
//        $html .= '</div>'; //close text div
//        $html .= '</div>'; //close service div
//
//        return $html;
//    }
//
//    add_shortcode('service', 'service');
//}

/* Social Icon shortcode */

if (!function_exists('social_icons')) {
	function social_icons($atts, $content = null) {
		$args = array(
			"type"                => "",
			"icon_pack"           => "",
			"fa_icon"             => "",
			"fe_icon"             => "",
			"link"                => "",
			"target"              => "",
			"size"                => "",
			"icon_color"          => "",
			"background_color"    => "",
			"border_color"        => "",
			"border_width"        => ""
		);

		extract(shortcode_atts($args, $atts));

		$html            		= "";
		$fa_stack_styles 		= "";
		$icon_styles     		= "";
		$icon_holder_classes 	= array();

		if($link != "") {
			$icon_holder_classes[] = "with_link";
		}

		if($type != "") {
			$icon_holder_classes[] = $type;
		}

		if($icon_color != ""){
			$icon_styles .= "color: ".$icon_color.";";
		}

		if($background_color != ""){
			$fa_stack_styles .= "background-color: {$background_color};";
		}

		if($border_color != ""){
			$fa_stack_styles .= "border: 2px solid {$border_color};";
		}

		if($border_width != ""){
			$fa_stack_styles .= "border-width: {$border_width}px;";
		}

		$html .= "<span class='q_social_icon_holder ".implode(' ', $icon_holder_classes)."'>";

		if($link != ""){
			$html .= "<a href='".$link."' target='".$target."'>";
		}

		if($type == "normal_social"){

			if($icon_pack == 'font_awesome' && $fa_icon != ""){
				$html .= "<i class='social_icon fa ".$fa_icon." ".$size." simple_social' style='".$icon_styles."'></i>";
			}
			elseif($icon_pack == 'font_elegant' && $fe_icon != ""){
				$html .= "<span class='social_icon ".$fe_icon." ".$size." simple_social' style='".$icon_styles."'></span>";
			}

		} else {

			$html .= "<span class='fa-stack ".$size." ".$type."' style='".$icon_styles.$fa_stack_styles."'>";

			if($icon_pack == 'font_awesome' && $fa_icon != ""){
				$html .= "<i class='social_icon fa ".$fa_icon."'></i>";
			} elseif($icon_pack == 'font_elegant' && $fe_icon != ""){
				$html .= "<span class='social_icon ".$fe_icon."'></span>";
			}

			$html .= "</span>"; //close fa-stack

		}

		if($link != ""){
			$html .= "</a>";
		}

		$html .= "</span>"; //close q_social_icon_holder
		return $html;
	}
}
add_shortcode('social_icons', 'social_icons');

/* Social Share shortcode */

if (!function_exists('social_share')) {
	function social_share($atts, $content = null) {
		global $qode_options;
		if(isset($qode_options['twitter_via']) && !empty($qode_options['twitter_via'])) {
			$twitter_via = " via " . $qode_options['twitter_via'] . " ";
		} else {
			$twitter_via = 	"";
		}
		if(isset($_SERVER["https"])) {
			$count_char = 23;
		} else{
			$count_char = 22;
		}
		$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
		$html = "";
		if(isset($qode_options['enable_social_share']) && $qode_options['enable_social_share'] == "yes") {
			$post_type = get_post_type();
			if(isset($qode_options["post_types_names_$post_type"])) {
				if($qode_options["post_types_names_$post_type"] == $post_type) {
					if($post_type == "portfolio_page") {
						$html .= '<div class="portfolio_share">';
					} elseif($post_type == "post") {
						$html .= '<div class="blog_share">';
					} elseif($post_type == "page") {
						$html .= '<div class="page_share">';
					}
					$html .= '<div class="social_share_holder">';
					$html .= '<a href="javascript:void(0)" target="_self"><i class="social_share_icon"></i>';
					$html .= '<span class="social_share_title"><i class="social_share social_share_icon"></i>'.  __('Share','qode') .'</span>';
					$html .= '</a>';
					$html .= '<div class="social_share_dropdown"><ul>';
					if(isset($qode_options['enable_facebook_share']) &&  $qode_options['enable_facebook_share'] == "yes") {
						$html .= '<li class="facebook_share">';
						$html .= '<a href="#" onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=' . urlencode(qode_addslashes(get_the_title())) . '&amp;p[summary]=' . urlencode(qode_addslashes(get_the_excerpt())) . '&amp;p[url]=' . urlencode(get_permalink()) . '&amp;&p[images][0]=';
						if(function_exists('the_post_thumbnail')) {
							$html .=  wp_get_attachment_url(get_post_thumbnail_id());
						}
						$html .='\', \'sharer\', \'toolbar=0,status=0,width=620,height=280\');" href="javascript: void(0)">';
						if(!empty($qode_options['facebook_icon'])) {
							$html .= '<img src="' . $qode_options["facebook_icon"] . '" alt="" />';
						} else {
							$html .= '<span class="social_network_icon social_facebook_circle"></span>';
						}
						$html .= "<span class='share_text'>" . __("Facebook","qode") . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}

					if($qode_options['enable_twitter_share'] == "yes") {
						$html .= '<li class="twitter_share">';
						$html .= '<a href="#" onclick="popUp=window.open(\'http://twitter.com/home?status=' . urlencode(the_excerpt_max_charlength($count_char) . $twitter_via) . get_permalink() . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;">';
						if(!empty($qode_options['twitter_icon'])) {
							$html .= '<img src="' . $qode_options["twitter_icon"] . '" alt="" />';
						} else {
							$html .= '<span class="social_network_icon social_twitter_circle"></span>';
						}
						$html .= "<span class='share_text'>" . __("Twitter", 'qode') . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}
					if($qode_options['enable_google_plus'] == "yes") {
						$html .= '<li  class="google_share">';
						$html .= '<a href="#" onclick="popUp=window.open(\'https://plus.google.com/share?url=' . urlencode(get_permalink()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($qode_options['google_plus_icon'])) {
							$html .= '<img src="' . $qode_options['google_plus_icon'] . '" alt="" />';
						} else {
							$html .= '<span class="social_network_icon social_googleplus_circle"></span>';
						}
						$html .= "<span class='share_text'>" . __("Google+","qode") . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($qode_options['enable_linkedin']) && $qode_options['enable_linkedin'] == "yes") {
						$html .= '<li  class="linkedin_share">';
						$html .= '<a href="#" onclick="popUp=window.open(\'http://linkedin.com/shareArticle?mini=true&amp;url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($qode_options['linkedin_icon'])) {
							$html .= '<img src="' . $qode_options['linkedin_icon'] . '" alt="" />';
						} else {
							$html .= '<span class="social_network_icon social_linkedin_circle"></span>';
						}
						$html .= "<span class='share_text'>" . __("LinkedIn","qode") . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($qode_options['enable_tumblr']) && $qode_options['enable_tumblr'] == "yes") {
						$html .= '<li  class="tumblr_share">';
						$html .= '<a href="#" onclick="popUp=window.open(\'http://www.tumblr.com/share/link?url=' . urlencode(get_permalink()). '&amp;name=' . urlencode(get_the_title()) .'&amp;description='.urlencode(get_the_excerpt()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($qode_options['tumblr_icon'])) {
							$html .= '<img src="' . $qode_options['tumblr_icon'] . '" alt="" />';
						} else {
							$html .= '<span class="social_network_icon social_tumblr_circle"></span>';
						}
						$html .= "<span class='share_text'>" . __("Tumblr","qode") . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($qode_options['enable_pinterest']) && $qode_options['enable_pinterest'] == "yes") {
						$html .= '<li  class="pinterest_share">';
						$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
						$html .= '<a href="#" onclick="popUp=window.open(\'http://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink()). '&amp;description=' . qode_addslashes(get_the_title()) .'&amp;media='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($qode_options['pinterest_icon'])) {
							$html .= '<img src="' . $qode_options['pinterest_icon'] . '" alt="" />';
						} else {
							$html .= '<span class="social_network_icon social_pinterest_circle"></span>';
						}
						$html .= "<span class='share_text'>" . __("Pinterest","qode") . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}
//                    if(isset($qode_options['enable_vk']) && $qode_options['enable_vk'] == "yes") {
//                        $html .= '<li  class="vk_share">';
//                        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
//                        $html .= '<a href="#" onclick="popUp=window.open(\'http://vkontakte.ru/share.php?url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) .'&amp;description=' . urlencode(get_the_excerpt()) .'&amp;image='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
//                        if(!empty($qode_options['vk_icon'])) {
//                            $html .= '<img src="' . $qode_options['vk_icon'] . '" alt="" />';
//                        } else {
//                            $html .= '<i class="fa fa-vk"></i>';
//                        }
//                        $html .= "<span class='share_text'>" . __("VK","qode") . "</span>";
//                        $html .= "</a>";
//                        $html .= "</li>";
//                    }
					$html .= "</ul></div>";
					$html .= "</div>";

					if($post_type == "portfolio_page" || $post_type == "post" || $post_type == "page") {
						$html .= '</div>';
					}
				}
			}
		}
		return $html;
	}
}
add_shortcode('social_share', 'social_share');

/* Social Share List shortcode */

if (!function_exists('social_share_list')) {
	function social_share_list($atts, $content = null) {
		global $qode_options;
		if(isset($qode_options['twitter_via']) && !empty($qode_options['twitter_via'])) {
			$twitter_via = " via " . $qode_options['twitter_via'] . " ";
		} else {
			$twitter_via = 	"";
		}
		$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
		$html = "";
		if(isset($qode_options['enable_social_share']) && $qode_options['enable_social_share'] == "yes") {
			$post_type = get_post_type();

			if(isset($qode_options["post_types_names_$post_type"])) {
				if($qode_options["post_types_names_$post_type"] == $post_type) {
					$html .= '<div class="social_share_list_holder">';
					$html .= "<span>".__('Share on: ')."</span>";
					$html .= '<ul>';

					if(isset($qode_options['enable_facebook_share']) &&  $qode_options['enable_facebook_share'] == "yes") {
						$html .= '<li class="facebook_share">';
						$html .= '<a title="'.__("Share on Facebook","qode").'" href="#" onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=' . qode_addslashes(get_the_title()) . '&amp;p[summary]=' . qode_addslashes(strip_tags(get_the_excerpt())) . '&amp;p[url]=' . urlencode(get_permalink()) . '&amp;&p[images][0]=';
						if(function_exists('the_post_thumbnail')) {
							$html .=  wp_get_attachment_url(get_post_thumbnail_id());
						}
						$html .='\', \'sharer\', \'toolbar=0,status=0,width=620,height=280\');" href="javascript: void(0)">';
						if(!empty($qode_options['facebook_icon'])) {
							$html .= '<img src="' . $qode_options["facebook_icon"] . '" alt="" />';
						} else {
							$html .= '<i class="social_facebook_circle"></i>';
						}
						$html .= "</a>";
						$html .= "</li>";
					}

					if($qode_options['enable_twitter_share'] == "yes") {
						$html .= '<li class="twitter_share">';
						$html .= '<a href="#" title="'.__("Share on Twitter", 'qode').'" onclick="popUp=window.open(\'http://twitter.com/home?status=' . urlencode(the_excerpt_max_charlength(mb_strlen(get_permalink())) . $twitter_via) . get_permalink() . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;">';
						if(!empty($qode_options['twitter_icon'])) {
							$html .= '<img src="' . $qode_options["twitter_icon"] . '" alt="" />';
						} else {
							$html .= '<i class="social_twitter_circle"></i>';
						}

						$html .= "</a>";
						$html .= "</li>";
					}
					if($qode_options['enable_google_plus'] == "yes") {
						$html .= '<li  class="google_share">';
						$html .= '<a href="#" title="'.__("Share on Google+","qode").'" onclick="popUp=window.open(\'https://plus.google.com/share?url=' . urlencode(get_permalink()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($qode_options['google_plus_icon'])) {
							$html .= '<img src="' . $qode_options['google_plus_icon'] . '" alt="" />';
						} else {
							$html .= '<i class="social_googleplus_circle"></i>';
						}

						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($qode_options['enable_linkedin']) && $qode_options['enable_linkedin'] == "yes") {
						$html .= '<li  class="linkedin_share">';
						$html .= '<a href="#" class="'.__("Share on LinkedIn","qode").'" onclick="popUp=window.open(\'http://linkedin.com/shareArticle?mini=true&amp;url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($qode_options['linkedin_icon'])) {
							$html .= '<img src="' . $qode_options['linkedin_icon'] . '" alt="" />';
						} else {
							$html .= '<i class="social_linkedin_circle"></i>';
						}

						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($qode_options['enable_tumblr']) && $qode_options['enable_tumblr'] == "yes") {
						$html .= '<li  class="tumblr_share">';
						$html .= '<a href="#" title="'.__("Share on Tumblr","qode").'" onclick="popUp=window.open(\'http://www.tumblr.com/share/link?url=' . urlencode(get_permalink()). '&amp;name=' . urlencode(get_the_title()) .'&amp;description='.urlencode(get_the_excerpt()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($qode_options['tumblr_icon'])) {
							$html .= '<img src="' . $qode_options['tumblr_icon'] . '" alt="" />';
						} else {
							$html .= '<i class="social_tumblr_circle"></i>';
						}

						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($qode_options['enable_pinterest']) && $qode_options['enable_pinterest'] == "yes") {
						$html .= '<li  class="pinterest_share">';
						$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
						$html .= '<a href="#" title="'.__("Share on Pinterest","qode").'" onclick="popUp=window.open(\'http://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink()). '&amp;description=' . qode_addslashes(get_the_title()) .'&amp;media='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($qode_options['pinterest_icon'])) {
							$html .= '<img src="' . $qode_options['pinterest_icon'] . '" alt="" />';
						} else {
							$html .= '<i class="social_pinterest_circle"></i>';
						}

						$html .= "</a>";
						$html .= "</li>";
					}
//                    if(isset($qode_options['enable_vk']) && $qode_options['enable_vk'] == "yes") {
//                        $html .= '<li  class="vk_share">';
//                        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
//                        $html .= '<a href="#" title="'.__("Share on VK","qode").'" onclick="popUp=window.open(\'http://vkontakte.ru/share.php?url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) .'&amp;description=' . urlencode(get_the_excerpt()) .'&amp;image='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
//                        if(!empty($qode_options['vk_icon'])) {
//                            $html .= '<img src="' . $qode_options['vk_icon'] . '" alt="" />';
//                        } else {
//                            $html .= '<i class="fa fa-vk"></i>';
//                        }
//
//                        $html .= "</a>";
//                        $html .= "</li>";
//                    }

					$html .= '</ul>'; //close ul
					$html .= '</div>'; //close div.social_share_list_holder
				}
			}
		}
		return $html;
	}

	add_shortcode('social_share_list', 'social_share_list');
}

/* Steps shortcode */

//if (!function_exists('steps')) {
//    /**
//     * @deprecated
//     * @param $atts
//     * @param null $content
//     * @return string
//     */
//    function steps($atts, $content = null) {
//        $args = array(
//            "number_of_steps"   => "4",
//            "background_color" => "",
//            "number_color" => "",
//            "title_color" => "",
//            "circle_wrapper_border_color" => "",
//
//            "title_1" => "",
//            "step_number_1" => "",
//            "step_description_1" => "",
//            "step_link_1" => "",
//            "step_link_target_1" => "_blank",
//
//            "title_2" => "",
//            "step_number_2" => "",
//            "step_description_2" => "",
//            "step_link_2" => "",
//            "step_link_target_2" => "_self",
//
//            "title_3" => "",
//            "step_number_3" => "",
//            "step_description_3" => "",
//            "step_link_3" => "",
//            "step_link_target_3" => "_self",
//
//            "title_4" => "",
//            "step_number_4" => "",
//            "step_description_4" => "",
//            "step_link_4" => "",
//            "step_link_target_4" => "_self"
//        );
//
//        extract(shortcode_atts($args, $atts));
//
//        $steps_array = array();
//
//        //for the number of steps
//        for ($i = 1; $i <= $number_of_steps; $i++) {
//            //generate object for each step that  holds that steps data
//            $step_object = new stdClass();
//
//            $step_object->title = ${"title_".$i};
//            $step_object->step_number = ${"step_number_".$i};
//            $step_object->step_description = ${"step_description_".$i};
//            $step_object->step_link = ${"step_link_".$i};
//            $step_object->step_link_target = ${"step_link_target_".$i};
//
//            //push object to steps array
//            $steps_array[] = $step_object;
//        }
//
//        //init variables
//        $html                   = "";
//        $number_styles          = "";
//        $title_styles           = "";
//        $circle_styles          = "";
//        $circle_wrapper_styles  = "";
//
//        if($number_color != "") {
//            $number_styles .= "color: ".$number_color.";";
//        }
//
//        if($title_color != "") {
//            $title_styles .= "color: ".$title_color.";";
//        }
//
//        if($background_color != "") {
//            $circle_styles .= "background-color: ".$background_color.";";
//        }
//
//        if($circle_wrapper_border_color != "") {
//            $circle_wrapper_styles .= "border-top-color: ".$circle_wrapper_border_color.";";
//        }
//
//        if(is_array($steps_array) && count($steps_array)) {
//            $html .= "<div class='q_steps_holder'>";
//            $html .= "<div class='steps_holder_inner'>";
//
//
//            for($i = 1; $i <= count($steps_array); $i++) {
//                $step = $steps_array[$i - 1];
//                $html .= "<div class='circle_small_holder step{$i}'>";
//                $html .= "<div class='circle_small_holder_inner'>";
//                $html .= "<div class='circle_small_wrapper' style='{$circle_wrapper_styles}'>";
//                $html .= "<div class='circle_small' style='{$circle_styles}'>";
//
//                if($step->step_link != "") {
//                    $html .= "<a href='{$step->step_link}' target='{$step->step_link_target}' class='circle_small_inner'>";
//
//                    if($step->step_number != "") {
//                        $html .= "<span style='{$number_styles}'>{$step->step_number}</span>";
//                    }
//
//                    $html .= "<p class='step_title' style='{$title_styles}'>{$step->title}</p>";
//                    $html .= "</a>"; //close circle_small_inner
//                } else {
//                    $html .= "<div class='circle_small_inner'>";
//
//                    if($step->step_number != "") {
//                        $html .= "<span style='{$number_styles}'>{$step->step_number}</span>";
//                    }
//
//                    $html .= "<p class='step_title' style='{$title_styles}'>{$step->title}</p>";
//                    $html .= "</div>"; //close circle_small_inner
//                }
//
//                $html .= "</div>"; //close circle_small
//                $html .= "</div>"; //close circle_small_wrapper
//
//                $html .= "</div>"; //close circle_small_holder_inner
//                $html .= "<p>{$step->step_description}</p>";
//                $html .= "</div>"; //close circle_small_holder
//            }
//
//            $html .= "</div>"; //close steps_holder_inner
//            $html .= "</div>"; //close steps_holder
//        }
//
//        return $html;
//
//    }
//}
//add_shortcode('steps', 'steps');


/* Team shortcode */

if (!function_exists('q_team')) {
	function q_team($atts, $content = null) {
		$args = array(
			"team_image"								=> "",
			"team_name"									=> "",
			"team_name_color"							=> "",
			"team_name_font_size"						=> "",
			"team_name_font_weight"						=> "",
			"team_position"								=> "",
			"team_position_color"						=> "",
			"team_position_font_size"					=> "",
			"team_position_font_weight"					=> "",
			"team_description"							=> "",
			"background_color"							=> "",
			"box_border"								=> "",
			"box_border_width"							=> "",
			"box_border_color"							=> "",
			"show_separator"							=> "yes",

			"team_social_icon_pack"     				=> "",
			"team_social_icon_type"     				=> "normal_social",
			"team_social_icon_color"    				=> "",
			"team_social_icon_background_color"    		=> "",
			"team_social_icon_border_color"	    		=> "",

			"team_social_fa_icon_1"						=> "",
			"team_social_fe_icon_1"						=> "",
			"team_social_icon_1_link"					=> "",
			"team_social_icon_1_target"					=> "",

			"team_social_fa_icon_2"						=> "",
			"team_social_fe_icon_2"						=> "",
			"team_social_icon_2_link"					=> "",
			"team_social_icon_2_target"					=> "",

			"team_social_fa_icon_3"						=> "",
			"team_social_fe_icon_3"						=> "",
			"team_social_icon_3_link"					=> "",
			"team_social_icon_3_target"					=> "",

			"team_social_fa_icon_4"						=> "",
			"team_social_fe_icon_4"						=> "",
			"team_social_icon_4_link"					=> "",
			"team_social_icon_4_target"					=> "",

			"team_social_fa_icon_5"     				=> "",
			"team_social_fe_icon_5"     				=> "",
			"team_social_icon_5_link"   				=> "",
			"team_social_icon_5_target" 				=> "",

			"title_tag"									=> "h5",

			"show_skills"								=> "no",
			"skills_title_size"							=> "",
			"skill_title_1"								=> "",
			"skill_percentage_1"						=> "",
			"skill_title_2"								=> "",
			"skill_percentage_2"						=> "",
			"skill_title_3"								=> "",
			"skill_percentage_3"						=> "",
		);

		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];
		if(is_numeric($team_image)) {
			$team_image_src = wp_get_attachment_url( $team_image );
		} else {
			$team_image_src = $team_image;
		}

		$q_team_holder_classes = array();

		if($background_color != "" || ($box_border != "")) {
			$q_team_holder_classes[] = "with_padding";
		}

		if($show_separator == 'no') {
			$q_team_holder_classes[] = 'without_separator';
		}

		$q_team_style = "";
		if($background_color != ""){
			$q_team_style .= " style='";
			$q_team_style .= 'background-color:' . $background_color . ';';
			$q_team_style .= "'";
		}

		$qteam_box_style = "";
		if($box_border == "yes"){

			$qteam_box_style .= "style=";

			$qteam_box_style .= "border-style:solid;";
			if($box_border_color != "" ){
				$qteam_box_style .= "border-color:" . $box_border_color . ";";
			}
			if($box_border_width != "" ){
				$qteam_box_style .= "border-width:" . $box_border_width . "px;";
			}

			$qteam_box_style .= "'";

		}

		$q_team_name_style_array = array();
		$q_team_name_style 		 = '';

		if($team_name_color != '') {
			$q_team_name_style_array[] = 'color: '.$team_name_color;
		}

		if($team_name_font_size != '') {
			$q_team_name_style_array[] = 'font-size: '.$team_name_font_size.'px';
		}

		if($team_name_font_weight != '') {
			$q_team_name_style_array[] = 'font-weight: '.$team_name_font_weight;
		}

		if(is_array($q_team_name_style_array) && count($q_team_name_style_array)) {
			$q_team_name_style = 'style ="'.implode(';', $q_team_name_style_array).'"';
		}

		$q_team_position_style_array = array();
		$q_team_position_style 		 = '';

		if($team_position_color != '') {
			$q_team_position_style_array[] = 'color: '.$team_position_color;
		}

		if($team_position_font_size != '') {
			$q_team_position_style_array[] = 'font-size: '.$team_position_font_size.'px';
		}

		if($team_position_font_weight != '') {
			$q_team_position_style_array[] = 'font-weight: '.$team_position_font_weight;
		}

		if(is_array($q_team_position_style_array) && count($q_team_position_style_array)) {
			$q_team_position_style = 'style ="'.implode(';', $q_team_position_style_array).'"';
		}


		$html =  "<div class='q_team ".implode(' ', $q_team_holder_classes)."'". $q_team_style .">";
		$html .=  "<div class='q_team_inner'>";
		if($team_image != "") {
			$html .=  "<div class='q_team_image'>";
			$html .= "<img src='$team_image_src' alt='' />";
			$html .=  "</div>";
		}
		$html .=  "<div class='q_team_text' ". $qteam_box_style .">";
		$html .=  "<div class='q_team_text_inner'>";
		$html .=  "<div class='q_team_title_holder'>";
		$html .=  "<$title_tag class='q_team_name' ".$q_team_name_style.">";
		$html .= $team_name;
		$html .=  "</$title_tag>";
		if($team_position != "") {
			$html .= "<h6 class='q_team_position' ".$q_team_position_style.">" . $team_position . "</6>";
		}

		$html .=  "</div>"; //close div.q_team_title_holder

		if($team_description != "") {

			$html .= "<div class='q_team_description'>";
			$html .= "<p>".$team_description."</p>";
			$html .= "</div>"; // close div.q_team_description
		}

		if($show_skills == 'yes') {
			$html .= '<div class="q_team_skills_holder">';

			for($i = 1; $i <=3; $i++) {
				$skill_title = ${"skill_title_".$i};
				$skill_percentage = ${"skill_percentage_".$i};

				if($skill_title != '' && $skill_percentage != '') {

					$skills_param_array = array(
						'title ='.$skill_title,
						'percent = '.$skill_percentage
					);

					if($skills_title_size != '') {
						$skills_param_array[] = 'title_custom_size = '.$skills_title_size;
					}

					$html .= do_shortcode('[progress_bar '.implode(' ', $skills_param_array).']');
				}
			}

			$html .= '</div>';
		}

		if($show_separator != "no"){
			$html .=  "<div class='double_separator'></div>";
		}
		$html .=  "</div>"; //close div.q_team_text_inner

		$html .=  "<div class='q_team_social_holder'>";

		//generate social icons html
		$team_social_icon_type_label = ''; //used in generating shortcode parameters based on icon pack
		$team_social_icon_param_label = ''; //used in generating shortcode parameters based on icon pack

		//is font awesome icon pack chosen?
		if($team_social_icon_pack == 'font_awesome') {
			$team_social_icon_type_label = 'team_social_fa_icon';
			$team_social_icon_param_label = 'fa_icon';
		} else {
			$team_social_icon_type_label = 'team_social_fe_icon';
			$team_social_icon_param_label = 'fe_icon';
		}

		//for each of available icons
		for($i = 1; $i <= 5; $i++) {
			$team_social_icon 		= ${$team_social_icon_type_label.'_'.$i};
			$team_social_link 		= ${'team_social_icon_'.$i.'_link'};
			$team_social_target		= ${'team_social_icon_'.$i.'_target'};

			if($team_social_icon != "") {
				$social_icons_param_array = array();

				$social_icons_param_array[] = $team_social_icon_param_label."='".$team_social_icon."'";

				if($team_social_link !== '') {
					$social_icons_param_array[] = "link='".$team_social_link."'";
				}

				if($team_social_target !== '') {
					$social_icons_param_array[] = "target='".$team_social_target."'";
				}

				if($team_social_icon_type !== '') {
					$social_icons_param_array[] = "type='".$team_social_icon_type."'";
				}

				if($team_social_icon_color !== '') {
					$social_icons_param_array[] = "icon_color='".$team_social_icon_color."'";
				}

				if($team_social_icon_background_color !== '') {
					$social_icons_param_array[] = "background_color='".$team_social_icon_background_color."'";
				}

				if($team_social_icon_border_color !== '') {
					$social_icons_param_array[] = "border_color='".$team_social_icon_border_color."'";
				}

				$html .=  do_shortcode('[social_icons icon_pack="'.$team_social_icon_pack.'" '.implode(' ', $social_icons_param_array).']');
			}

		}

		$html .=  "</div>"; //close div.q_team_social_holder
		$html .=  "</div>"; //close div.q_team_text
		$html .=  "</div>"; //close div.q_team_inner
		$html .=  "</div>"; //close div.q_team
		return $html;
	}
}
add_shortcode('q_team', 'q_team');


/* Testimonials shortcode */

if (!function_exists('testimonials')) {

	function testimonials($atts, $content = null) {
		$deafult_args = array(
			"number"					=> "-1",
			"category"					=> "",
			"text_color"				=> "",
			"text_font_size"			=> "",
			"author_text_color"			=> "",
			"show_navigation"			=> "",
			"navigation_style"			=> "",
			"auto_rotate_slides"		=> "",
			"animation_type"			=> "",
			"animation_speed"			=> ""
		);

		extract(shortcode_atts($deafult_args, $atts));

		$html                           = "";
		$testimonial_text_inner_styles  = "";
		$testimonial_p_style			= "";
		$navigation_button_radius		= "";
		$testimonial_name_styles        = "";

		if($text_font_size != "" || $text_color != ""){
			$testimonial_p_style = " style='";
			if($text_font_size != ""){
				$testimonial_p_style .= "font-size:". $text_font_size . "px;";
			}
			if($text_color != ""){
				$testimonial_p_style .= "color:". $text_color . ";";
			}
			$testimonial_p_style .= "'";
		}

		if($text_color != "") {
			$testimonial_text_inner_styles  .= "color: ".$text_color.";";
			$testimonial_name_styles        .= "color: ".$text_color.";";
		}

		if($author_text_color != "") {
			$testimonial_name_styles .= "color: ".$author_text_color.";";
		}

		$args = array(
			'post_type' => 'testimonials',
			'orderby' => "date",
			'order' => "DESC",
			'posts_per_page' => $number
		);

		if ($category != "") {
			$args['testimonials_category'] = $category;
		}

		$html .= "<div class='testimonials_holder clearfix ".$navigation_style."'>";
		$html .= '<div class="testimonials testimonials_carousel" data-show-navigation="'.$show_navigation.'" data-animation-type="'.$animation_type.'" data-animation-speed="'.$animation_speed.'" data-auto-rotate-slides="'.$auto_rotate_slides.'">';
		$html .= '<ul class="slides">';

		query_posts($args);
		if (have_posts()) :
			while (have_posts()) : the_post();
				$author = get_post_meta(get_the_ID(), "qode_testimonial-author", true);
				$website = get_post_meta(get_the_ID(), "qode_testimonial_website", true);
				$company_position = get_post_meta(get_the_ID(), "qode_testimonial-company_position", true);
				$text = get_post_meta(get_the_ID(), "qode_testimonial-text", true);

				$html .= '<li id="testimonials' . get_the_ID() . '" class="testimonial_content">';
				$html .= '<div class="testimonial_content_inner"';

				$html .= '>';
				$html .= '<div class="testimonial_text_holder">';
				$html .= '<div class="testimonial_text_inner" style="'.$testimonial_text_inner_styles.'">';
				$html .= '<p'. $testimonial_p_style .'>' . trim($text) . '</p>';

				$html .= '<p class="testimonial_author" style="'.$testimonial_name_styles.'">- ' . $author;

				$html .= '</p>';
				$html .= '</div>'; //close testimonial_text_inner
				$html .= '</div>'; //close testimonial_text_holder

				$html .= '</div>'; //close testimonial_content_inner
				$html .= '</li>'; //close testimonials
			endwhile;
		else:
			$html .= __('Sorry, no posts matched your criteria.', 'qode');
		endif;

		wp_reset_query();
		$html .= '</ul>';//close slides
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}

}
add_shortcode('testimonials', 'testimonials');

/* Unordered List shortcode */

if (!function_exists('unordered_list')) {
	function unordered_list($atts, $content = null) {
		$args = array(
			"style"         => "",
			"animate"       => "",
			'number_type'   => "",
			"font_weight"   => ""
		);

		extract(shortcode_atts($args, $atts));

		$list_item_classes = "";

		if($style != "") {
			$list_item_classes .= "{$style}";
		}

		if($number_type != "") {
			$list_item_classes .= " {$number_type}";
		}

		if($font_weight != "") {
			$list_item_classes .= " {$font_weight}";
		}

		$html =  "<div class='q_list $list_item_classes";
		if($animate == "yes"){
			$html .= " animate_list'>" . $content . "</div>";
		} else {
			$html .= "'>" . $content . "</div>";
		}
		return $html;
	}
}
add_shortcode('unordered_list', 'unordered_list');

/* Service table shortcode */

if (!function_exists('service_table')) {
	function service_table($atts, $content = null) {
		global $qode_options;
		$args = array(
			"title"                    	=> "",
			"title_tag"                	=> "h5",
			"title_color"              	=> "",
			"title_background_type"    	=> "",
			"title_background_color"   	=> "",
			"background_image"         	=> "",
			"background_image_height"  	=> "",
			"icon_pack"              	=> "",
			"fa_icon"                	=> "",
			"fe_icon"                	=> "",
			"custom_size"              	=> "",
			"border"					=> "",
			"border_width"              => "",
			"border_color"              => "",
			"content_background_color" 	=> ""
		);

		extract(shortcode_atts($args, $atts));

		$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

		//get correct heading value. If provided heading isn't valid get the default one
		$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

		//init variables
		$html = "";
		$title_holder_style = "";
		$title_style = "";
		$title_classes = "";
		$icon_style = "";
		$content_style = "";
		$service_table_holder_style = "";
		$service_table_style = "";
		$background_image_src = "";

		if($title_background_type == "background_color_type"){
			if($title_background_color != ""){
				$title_holder_style .= "background-color: ".$title_background_color.";";
			}

		} else {
			if(is_numeric($background_image)) {
				$background_image_src = wp_get_attachment_url( $background_image );
			} else {
				$background_image_src = $background_image;
			}

			if(!empty($qode_options['first_color'])){
				$service_table_style = $qode_options['first_color'];
			} else {
				$service_table_style = "#00c6ff";
			}

			if($background_image != ""){
				$title_holder_style .= "background-image: url(".$background_image_src.");";
			}

			if($background_image_height != ""){
				$title_holder_style .= "height: ".$background_image_height."px;";
			}
		}
		if($border == "yes"){
			$service_table_holder_style .= " style='border-style:solid;";
			if($border_width != ""){
				$service_table_holder_style .= "border-width:". $border_width . "px;";
			}
			if($border_color != ""){
				$service_table_holder_style .= "border-color:". $border_color . ";";
			}
			$service_table_holder_style .="'";
		}
		if($title_color != ""){
			$title_style .= "color: ".$title_color.";";

			$title_holder_style .= "color: ".$title_color.";";
		}

		$title_classes .= $title_background_type;

		if($custom_size != ""){
			$icon_style .= "font-size: ".$custom_size."px;";
		}

		if($content_background_color != ""){
			$content_style .= "background-color: ".$content_background_color.";";
		}

		$html .= "<div class='service_table_holder'". $service_table_holder_style ."><ul class='service_table_inner'>";

		$html .= "<li class='service_table_title_holder ".$title_classes."' style='".$title_holder_style."'>";

		$html .= "<div class='service_table_title_inner'><div class='service_table_title_inner2'>";

		if($title != ""){
			$html .= "<".$title_tag." class='service_title' style='".$title_style."'>".$title."</".$title_tag.">";
		}

		if ($icon_pack == 'font_awesome' && $fa_icon != ''){
			if($fa_icon != ""){
				$html .= "<i class='service_table_icon fa ".$fa_icon."' style='".$icon_style."'></i>";
			}
		} elseif ($icon_pack == 'font_elegant' && $fe_icon != ''){
			if($fe_icon != ""){
				$html .= "<span class='service_table_icon ".$fe_icon."' style='".$icon_style."'></span>";
			}
		}

		$html .= "</div></div>";

		$html .= "</li>";

		$html .= "<li class='service_table_content' style='".$content_style."'>";

		$html .= do_shortcode($content);

		$html .= "</li>";

		$html .= "</ul></div>";

		return $html;
	}
}
add_shortcode('service_table', 'service_table');

/* Qode Slider shortcode */

if (!function_exists('qode_slider')) {
	function qode_slider( $atts, $content = null ) {
		global $qode_options;
		extract(shortcode_atts(array("slider"=>"", "height"=>"", "responsive_height"=>"", "background_color"=>"", "auto_start"=>"", "animation_type"=>"", "slide_animation"=>"6000", "anchor" => ""), $atts));
		$html = "";

		if ($slider != "") {
			$args = array(
				'post_type'=> 'slides',
				'slides_category' => $slider,
				'orderby' => "menu_order",
				'order' => "ASC",
				'posts_per_page' => -1
			);

			$slider_id = get_term_by('slug',$slider,'slides_category')->term_id;
			$slider_meta = get_option( "taxonomy_term_".$slider_id );
			$slider_header_effect =  $slider_meta['header_effect'];
			if($slider_header_effect == 'yes'){
				$header_effect_class = 'header_effect';
			}else{
				$header_effect_class = '';
			}

			$slider_css_position_class = '';
			$slider_parallax = 'yes';
			if(isset($slider_meta['slider_parallax_effect'])){
				$slider_parallax = $slider_meta['slider_parallax_effect'];
			}
			if($slider_parallax == 'no'){
				$data_parallax_effect = 'data-parallax="no"';
				$slider_css_position_class = 'relative_position';
			}else{
				$data_parallax_effect = 'data-parallax="yes"';
			}

			$slider_thumbs =  'no';
			if($slider_thumbs == 'yes'){
				$slider_thumbs_class = 'slider_thumbs';
			}else{
				$slider_thumbs_class = '';
			}

			if($height == "" || $height == "0"){
				$full_screen_class = "full_screen";
				$responsive_height_class = "";
				$slide_height = "";
				$data_height = "";
			}else{
				$full_screen_class = "";
				if($responsive_height == "yes"){
					$responsive_height_class = "responsive_height";
				}else{
					$responsive_height_class = "";
				}
				$slide_height = "height: ".$height."px;";
				$data_height = "data-height='".$height."'";
			}

			$anchor_data = '';
			if($anchor != "") {
				$anchor_data .= 'data-q_id = "#'.$anchor.'"';
			}

			$slider_transparency_class = "header_not_transparent";
			if(isset($qode_options['header_background_transparency_initial']) && $qode_options['header_background_transparency_initial'] != "1" && $qode_options['header_background_transparency_initial'] != ""){
				$slider_transparency_class = "";
			}

			if($background_color != ""){
				$background_color = 'background-color:'.$background_color.';';
			}

			$auto = "true";
			if($auto_start != ""){
				$auto = $auto_start;
			}

			if($auto == "true"){
				$auto_start_class = "q_auto_start";
			} else {
				$auto_start_class = "";
			}

			if($slide_animation != ""){
				$slide_animation = 'data-slide_animation="'.$slide_animation.'"';
			} else {
				$slide_animation = 'data-slide_animation=""';
			}

			if($animation_type == 'fade'){
				$animation_type_class = 'fade';
			}else{
				$animation_type_class = '';
			}

			/**************** Count positioning of navigation arrows and preloader depending on header transparency and layout - START ****************/

			global $wp_query;

			$page_id = $wp_query->get_queried_object_id();
			$header_height_padding = 0;
			if((get_post_meta($page_id, "qode_header_color_transparency_per_page", true) == "" || get_post_meta($page_id, "qode_header_color_transparency_per_page", true) == "1") && ($qode_options['header_background_transparency_initial'] == "" || $qode_options['header_background_transparency_initial'] == "1")){
				if (!empty($qode_options['header_height'])) {
					$header_height = $qode_options['header_height'];
				} else {
					$header_height = 100;
				}
				if($qode_options['header_bottom_appearance'] == 'stick menu_bottom'){
					$menu_bottom = '46';
					if(is_active_sidebar('header_fixed_right')){
						$menu_bottom = $menu_bottom + 22;
					}
				} else {
					$menu_bottom = 0;
				}

				$header_top = 0;
				if(isset($qode_options['header_top_area']) && $qode_options['header_top_area'] == "yes"){
					$header_top = 34;
				}
				$header_height_padding = $header_height + $menu_bottom + $header_top;
				if (isset($qode_options['center_logo_image']) && $qode_options['center_logo_image'] == "yes") {
					if(isset($qode_options['logo_image'])){
						$logo_width = 0;
						$logo_height = 0;
						if (!empty($qode_options['logo_image'])) {
							$logo_url_obj = parse_url($qode_options['logo_image']);
							list($logo_width, $logo_height, $logo_type, $logo_attr) = getimagesize($_SERVER['DOCUMENT_ROOT'].$logo_url_obj['path']);
						}
					}
					$header_height_padding = $logo_height + 30 + $menu_bottom + $header_top; // 30 is top and bottom margin of centered logo
				}
			}
			if($header_height_padding != 0){
				$navigation_margin_top = 'style="margin-top:'. ($header_height_padding/2 - 30).'px;"'; // 30 is top and bottom margin of centered logo
				$loader_margin_top = 'style="margin-top:'. ($header_height_padding/2).'px;"';
			}
			else {
				$navigation_margin_top = '';
				$loader_margin_top = '';
			}

			/**************** Count positioning of navigation arrows and preloader depending on header transparency and layout - END ****************/


			$html .= '<div id="qode-'.$slider.'" '.$anchor_data.' class="carousel slide '.$animation_type_class.' '.$full_screen_class.' '.$responsive_height_class.' '.$auto_start_class.' '.$header_effect_class.' '.$slider_thumbs_class.' '.$slider_transparency_class.'" '.$slide_animation.' '.$data_height.' '.$data_parallax_effect.' style="'.$slide_height.' '.$background_color.'"><div class="qode_slider_preloader"><div class="ajax_loader" '.$loader_margin_top.'><div class="ajax_loader_1">'.qode_loading_spinners(true).'</div></div></div>';
			$html .= '<div class="carousel-inner '.$slider_css_position_class.'" data-start="transform: translateY(0px);" data-1440="transform: translateY(-500px);">';
			query_posts( $args );


			$found_slides =  $wp_query->post_count;

			if ( have_posts() ) : $postCount = 0; while ( have_posts() ) : the_post();
				$active_class = '';
				if($postCount == 0){
					$active_class = 'active';
				}else{
					$active_class = 'inactive';
				}

				$slide_type = get_post_meta(get_the_ID(), "qode_slide-background-type", true);

				$image = get_post_meta(get_the_ID(), "qode_slide-image", true);
				$thumbnail = get_post_meta(get_the_ID(), "qode_slide-thumbnail", true);
				$thumbnail_animation = get_post_meta(get_the_ID(), "qode_slide-thumbnail-animation", true);

				$video_webm = get_post_meta(get_the_ID(), "qode_slide-video-webm", true);
				$video_mp4 = get_post_meta(get_the_ID(), "qode_slide-video-mp4", true);
				$video_ogv = get_post_meta(get_the_ID(), "qode_slide-video-ogv", true);
				$video_image = get_post_meta(get_the_ID(), "qode_slide-video-image", true);
				$video_overlay = get_post_meta(get_the_ID(), "qode_slide-video-overlay", true);
				$video_overlay_image = get_post_meta(get_the_ID(), "qode_slide-video-overlay-image", true);

				$content_animation = '';
				$content_animation .= get_post_meta(get_the_ID(), "qode_slide-content-animation", true);
				if(get_post_meta(get_the_ID(), 'qode_slide-subtitle', true) != '') {
					if(get_post_meta(get_the_ID(), 'qode_slide-subtitle-position', true) == "bellow_title"){
						$content_animation .= ' subtitle_bellow_title';
					}else{
						$content_animation .= ' subtitle_above_title';
					}
				}else{
					$content_animation .= ' no_subtitle';
				}
				if(get_post_meta(get_the_ID(), "qode_slide-separator-after-title", true) == 'yes') {
					$content_animation .= ' has_separator';
				}else{
					$content_animation .= ' no_separator';
				}

				$title_classes = "";
				if(get_post_meta(get_the_ID(), "qode_slide-title-hide-border", true) == "") {
					$title_classes .= "with_border ";
				}

                $small_title_class = "";
                if(get_post_meta(get_the_ID(), "qode_slide-small-title-on-bottom", true) == true){
                    $small_title_class .= "small_title";
                }
                $small_title_position_class = "";
                if(get_post_meta(get_the_ID(), "qode_slide-small-title-on-bottom-position", true) == "left"){
                    $small_title_position_class .= "small_title_left";
                }else{
                    $small_title_position_class .= "small_title_right";
                }
				$title_color = "";
				if(get_post_meta(get_the_ID(), "qode_slide-title-color", true) != ""){
					$title_color .= "color: ". get_post_meta(get_the_ID(), "qode_slide-title-color", true) . ";";
				}
				$title_font_size = "";
				if(get_post_meta(get_the_ID(), "qode_slide-title-font-size", true) != ""){
					$title_font_size .= "font-size: ". get_post_meta(get_the_ID(), "qode_slide-title-font-size", true) . "px;";
				}
				$title_line_height = "";
				if(get_post_meta(get_the_ID(), "qode_slide-title-line-height", true) != ""){
					$title_line_height .= "line-height: ". get_post_meta(get_the_ID(), "qode_slide-title-line-height", true) . "px;";
				}
				$title_font_family = "";
				if(get_post_meta(get_the_ID(), "qode_slide-title-font-family", true) != ""){
					$title_font_family .= "font-family: '". str_replace('+', ' ', get_post_meta(get_the_ID(), "qode_slide-title-font-family", true)) . "';";
				}
				$title_font_style = "";
				if(get_post_meta(get_the_ID(), "qode_slide-title-font-style", true) != ""){
					$title_font_style .= "font-style: ". get_post_meta(get_the_ID(), "qode_slide-title-font-style", true) . ";";
				}
				$title_font_weight = "";
				if(get_post_meta(get_the_ID(), "qode_slide-title-font-weight", true) != ""){
					$title_font_weight .= "font-weight: ". get_post_meta(get_the_ID(), "qode_slide-title-font-weight", true) . ";";
				}
				$title_letter_spacing = '';
				if(get_post_meta(get_the_ID(), 'qode_slide-title-letter-spacing', true) !== '') {
					$title_letter_spacing = 'letter-spacing: '.get_post_meta(get_the_ID(), 'qode_slide-title-letter-spacing', true).'px; margin-right: -'.get_post_meta(get_the_ID(), 'qode_slide-title-letter-spacing', true).'px;';
				}

				$text_color = "";
				$button_style = "";
				if(get_post_meta(get_the_ID(), "qode_slide-text-color", true) != ""){
					$text_color = "color: ". get_post_meta(get_the_ID(), "qode_slide-text-color", true) . ";";
					$button_style = " style='border-color:". get_post_meta(get_the_ID(), "qode_slide-text-color", true) . ";color:". get_post_meta(get_the_ID(), "qode_slide-text-color", true) . ";'";
				}
				$text_font_size = "";
				if(get_post_meta(get_the_ID(), "qode_slide-text-font-size", true) != ""){
					$text_font_size = "font-size: ". get_post_meta(get_the_ID(), "qode_slide-text-font-size", true) . "px;";
				}
				$text_line_height = "";
				if(get_post_meta(get_the_ID(), "qode_slide-text-line-height", true) != ""){
					$text_line_height = "line-height: ". get_post_meta(get_the_ID(), "qode_slide-text-line-height", true) . "px;";
				}
				$text_font_family = "";
				if(get_post_meta(get_the_ID(), "qode_slide-text-font-family", true) != ""){
					$text_font_family = "font-family: '". str_replace('+', ' ', get_post_meta(get_the_ID(), "qode_slide-text-font-family", true)) . "';";
				}
				$text_font_style = "";
				if(get_post_meta(get_the_ID(), "qode_slide-text-font-style", true) != ""){
					$text_font_style = "font-style: ". get_post_meta(get_the_ID(), "qode_slide-text-font-style", true) . ";";
				}
				$text_font_weight = "";
				if(get_post_meta(get_the_ID(), "qode_slide-text-font-weight", true) != ""){
					$text_font_weight = "font-weight: ". get_post_meta(get_the_ID(), "qode_slide-text-font-weight", true) . ";";
				}
				$text_letter_spacing= "";
				if(get_post_meta(get_the_ID(), "qode_slide-text-letter-spacing", true) != ""){
					$text_letter_spacing = "letter-spacing: ". get_post_meta(get_the_ID(), "qode_slide-text-letter-spacing", true) . "px;";
				}

				$graphic_alignment = get_post_meta(get_the_ID(), "qode_slide-graphic-alignment", true);
				$content_alignment = get_post_meta(get_the_ID(), "qode_slide-content-alignment", true);

				$separate_text_graphic = get_post_meta(get_the_ID(), "qode_slide-separate-text-graphic", true);

				if(get_post_meta(get_the_ID(), "qode_slide-content-width", true) != ""){
					$content_width = "width:".get_post_meta(get_the_ID(), "qode_slide-content-width", true)."%;";
				}else{
					$content_width = "width:80%;";
				}
				if(get_post_meta(get_the_ID(), "qode_slide-content-left", true) != ""){
					$content_xaxis= "left:".get_post_meta(get_the_ID(), "qode_slide-content-left", true)."%;";
				}else{
					if(get_post_meta(get_the_ID(), "qode_slide-content-right", true) != ""){
						$content_xaxis = "right:".get_post_meta(get_the_ID(), "qode_slide-content-right", true)."%;";
					}else{
						$content_xaxis = "left: 10%;";
					}
				}
				if(get_post_meta(get_the_ID(), "qode_slide-content-top", true) != ""){
					$content_yaxis_start = "top:".get_post_meta(get_the_ID(), "qode_slide-content-top", true)."%;";
					$content_yaxis_end = "top:".(get_post_meta(get_the_ID(), "qode_slide-content-top", true)-10)."%;";
				}else{
					if(get_post_meta(get_the_ID(), "qode_slide-content-bottom", true) != ""){
						$content_yaxis_start = "bottom:".get_post_meta(get_the_ID(), "qode_slide-content-bottom", true)."%;";
						$content_yaxis_end = "bottom:".(get_post_meta(get_the_ID(), "qode_slide-content-bottom", true)+10)."%;";
					}else{
						$content_yaxis_start = "top: 35%;";
						$content_yaxis_end = "top: 10%;";
					}
				}

				if(get_post_meta(get_the_ID(), "qode_slide-graphic-width", true) != ""){
					$graphic_width = "width:".get_post_meta(get_the_ID(), "qode_slide-graphic-width", true)."%;";
				}else{
					$graphic_width = "width:50%;";
				}
				if(get_post_meta(get_the_ID(), "qode_slide-graphic-left", true) != ""){
					$graphic_xaxis= "left:".get_post_meta(get_the_ID(), "qode_slide-graphic-left", true)."%;";
				}else{
					if(get_post_meta(get_the_ID(), "qode_slide-graphic-right", true) != ""){
						$graphic_xaxis = "right:".get_post_meta(get_the_ID(), "qode_slide-graphic-right", true)."%;";
					}else{
						$graphic_xaxis = "left: 25%;";
					}
				}
				if(get_post_meta(get_the_ID(), "qode_slide-graphic-top", true) != ""){
					$graphic_yaxis_start = "top:".get_post_meta(get_the_ID(), "qode_slide-graphic-top", true)."%;";
					$graphic_yaxis_end = "top:".(get_post_meta(get_the_ID(), "qode_slide-graphic-top", true)-10)."%;";
				}else{
					if(get_post_meta(get_the_ID(), "qode_slide-graphic-bottom", true) != ""){
						$graphic_yaxis_start = "bottom:".get_post_meta(get_the_ID(), "qode_slide-graphic-bottom", true)."%;";
						$graphic_yaxis_end = "bottom:".(get_post_meta(get_the_ID(), "qode_slide-graphic-bottom", true)+10)."%;";
					}else{
						$graphic_yaxis_start = "top: 30%;";
						$graphic_yaxis_end = "top: 10%;";
					}
				}

				$header_style = "";
				if(get_post_meta(get_the_ID(), "qode_slide-header-style", true) != ""){
					$header_style = get_post_meta(get_the_ID(), "qode_slide-header-style", true);
				}

				$navigation_color = "";
				if(get_post_meta(get_the_ID(), "qode_slide-navigation-color", true) != ""){
					$navigation_color = 'data-navigation-color="'.get_post_meta(get_the_ID(), "qode_slide-navigation-color", true).'"';
				}

				$title = get_the_title();

				$html .= '<div class="item '.$header_style.' '.$small_title_class.' '.$small_title_position_class.'" '.$navigation_color.' style="'.$slide_height.'">';
				if($slide_type == 'video'){

					$html .= '<div class="video"><div class="mobile-video-image" style="background-image: url('.$video_image.')"></div><div class="video-overlay';
					if($video_overlay == "yes"){
						$html .= ' active';
					}
					$html .= '"';
					if($video_overlay_image != ""){
						$html .= ' style="background-image:url('.$video_overlay_image.');"';
					}
					$html .= '>';
					if($video_overlay_image != ""){
						$html .= '<img src="'.$video_overlay_image.'" alt="" />';
					}else{
						$html .= '<img src="'.get_template_directory_uri().'/css/img/pixel-video.png" alt="" />';
					}
					$html .= '</div><div class="video-wrap">
									
									<video class="video" width="1920" height="800" poster="'.$video_image.'" controls="controls" preload="auto" loop autoplay muted>';
					if(!empty($video_webm)) { $html .= '<source type="video/webm" src="'.$video_webm.'">'; }
					if(!empty($video_mp4)) { $html .= '<source type="video/mp4" src="'.$video_mp4.'">'; }
					if(!empty($video_ogv)) { $html .= '<source type="video/ogg" src="'. $video_ogv.'">'; }
					$html .='<object width="320" height="240" type="application/x-shockwave-flash" data="'.get_template_directory_uri().'/js/flashmediaelement.swf">
													<param name="movie" value="'.get_template_directory_uri().'/js/flashmediaelement.swf" />
													<param name="flashvars" value="controls=true&file='.$video_mp4.'" />
													<img src="'.$video_image.'" width="1920" height="800" title="No video playback capabilities" alt="Video thumb" />
											</object>
									</video>		
							</div></div>';
				}else{
					$html .= '<div class="image" style="background-image:url('.$image.');">';
					if($slider_thumbs == 'no'){
						$html .= '<img src="'.$image.'" alt="'.$title.'">';
					}
					$html .= '</div>';
				}

				$html_thumb = "";
				if($thumbnail != "" && get_post_meta(get_the_ID(), "qode_slide-small-title-on-bottom", true) == false){
					$html_thumb .= '<div class="thumb '.$thumbnail_animation.'">';
					$html_thumb .= '<img src="'.$thumbnail.'" alt="'.$title.'">';
					$html_thumb .= '</div>';
				}
				$html_text = "";
				$html_text .= '<div class="text '.$content_animation.'">';

				//generate slide subtitle section
				if(get_post_meta(get_the_ID(), 'qode_slide-subtitle', true) != '') {
					//init variables
					$slide_subtitle_styles_string   = '';
					$slide_subtitle_styles 			= array();
					$slide_subtitle_color  			= get_post_meta(get_the_ID(), 'qode_slide-subtitle-color', true);
					$slide_subtitle_font_size  		= get_post_meta(get_the_ID(), 'qode_slide-subtitle-font-size', true);
					$slide_subtitle_line_height  	= get_post_meta(get_the_ID(), 'qode_slide-subtitle-line-height', true);
					$slide_subtitle_font_family  	= get_post_meta(get_the_ID(), 'qode_slide-subtitle-font-family', true);
					$slide_subtitle_font_style   	= get_post_meta(get_the_ID(), 'qode_slide-subtitle-font-style', true);
					$slide_subtitle_font_weight   	= get_post_meta(get_the_ID(), 'qode_slide-subtitle-font-weight', true);
					$slide_subtitle_letter_spacing 	= get_post_meta(get_the_ID(), 'qode_slide-subtitle-letter-spacing', true);
					$slide_subtitle_position	   	= get_post_meta(get_the_ID(), 'qode_slide-subtitle-position', true);

					if($slide_subtitle_color !== '') {
						$slide_subtitle_styles[] = 'color: '.$slide_subtitle_color;
					}

					if($slide_subtitle_font_size !== '') {
						$slide_subtitle_styles[] = 'font-size: '.$slide_subtitle_font_size.'px';
					}

					if($slide_subtitle_line_height !== '') {
						$slide_subtitle_styles[] = 'line-height: '.$slide_subtitle_line_height.'px';
					}

					if($slide_subtitle_font_family !== '') {
						$slide_subtitle_styles[] = 'font-family: '. str_replace('+', ' ', $slide_subtitle_font_family);
					}

					if($slide_subtitle_font_style !== '') {
						$slide_subtitle_styles[] = 'font-style: '.$slide_subtitle_font_style;
					}

					if($slide_subtitle_font_weight !== '') {
						$slide_subtitle_styles[] = 'font-weight: '.$slide_subtitle_font_weight;
					}

					if($slide_subtitle_letter_spacing !== '') {
						$slide_subtitle_styles[] = 'letter-spacing: '.$slide_subtitle_letter_spacing.'px';
					}

					if(count($slide_subtitle_styles)) {
						$slide_subtitle_styles_string = 'style="'.implode(';', $slide_subtitle_styles).'"';
					}
					if($slide_subtitle_position != "bellow_title") {
						$html_text .= '<h4 class="slide_subtitle" '.$slide_subtitle_styles_string.'><span>'.get_post_meta(get_the_ID(), 'qode_slide-subtitle', true).'</span></h4>';
					}
				}

				if(get_post_meta(get_the_ID(), "qode_slide-hide-title", true) != true){
					$html_text .= '<h2 class="'.$title_classes.'" style="'.$title_color.$title_font_size.$title_line_height.$title_font_family.$title_font_style.$title_font_weight.$title_letter_spacing.'"><span>'.get_the_title().'</span></h2>';
				}

				//is separator after title option selected for current slide?
				if(get_post_meta(get_the_ID(), "qode_slide-separator-after-title", true) == 'yes') {

					//init variables
					$slide_separator_styles 		= '';
					$slide_separator_color  		= get_post_meta(get_the_ID(), "qode_slide-separator-color", true);
					$slide_separator_transparency  	= get_post_meta(get_the_ID(), "qode_slide-separator-transparency", true);
					$slide_separator_width			= get_post_meta(get_the_ID(), "qode_slide-separator-width", true);
					$slide_separator_thickness		= get_post_meta(get_the_ID(), "qode_slide-separator-thickness", true);

					//is separator color chosen?
					if($slide_separator_color !== '') {
						//is separator transparenct set?
						if($slide_separator_transparency !== '') {
							//get rgba color value
							$slide_separator_rgba_color = qode_rgba_color($slide_separator_color, $slide_separator_transparency);

							//set color style
							$slide_separator_styles .= 'border-bottom-color: '.$slide_separator_rgba_color.';';
						} else {
							//set color without transparency
							$slide_separator_styles .= 'border-bottom-color: '.$slide_separator_color.';';
						}
					}

					//is separator width set?
					if($slide_separator_width !== '') {
						//set separator width
						$slide_separator_styles .= 'width: '.$slide_separator_width.'%;';
					}

					if($slide_separator_thickness !== '') {
						$slide_separator_styles .= 'border-bottom-width: '.$slide_separator_thickness.'px;';
					}

					//append separator html
					$html_text .= '<div style="'.$slide_separator_styles.'" class="separator small"></div>';
				}

//				if(get_post_meta(get_the_ID(), "qode_slide-text-field-type", true) == "text" && get_post_meta(get_the_ID(), "qode_slide-text", true) != ""){
				$html_text .= '<p style="'.$text_color.$text_font_size.$text_line_height.$text_font_family.$text_font_style.$text_font_weight.$text_letter_spacing.'"><span>'.get_post_meta(get_the_ID(), "qode_slide-text", true).'</span></p>';
//               }
//                if(get_post_meta(get_the_ID(), "qode_slide-text-field-type", true) == "content"){
//                    $slide_content = get_the_content();
//                    $filtered_content = apply_filters( 'the_content', $slide_content);
//                    $html_text .= do_shortcode($filtered_content);
//                }

				//check if first button should be displayed
				$is_first_button_shown = get_post_meta(get_the_ID(), "qode_slide-button-label", true) != "" && get_post_meta(get_the_ID(), "qode_slide-button-link", true) != "" && get_post_meta(get_the_ID(), "qode_slide-small-title-on-bottom", true) == false;

				//check if second button should be displayed
				$is_second_button_shown = get_post_meta(get_the_ID(), "qode_slide-button-label2", true) != "" && get_post_meta(get_the_ID(), "qode_slide-button-link2", true) != "" && get_post_meta(get_the_ID(), "qode_slide-small-title-on-bottom", true) == false;

				//does any button should be displayed?
				$is_any_button_shown = $is_first_button_shown || $is_second_button_shown;

				if($is_any_button_shown) {
					$html_text .= '<div class="slide_buttons_holder">';
				}

				if($is_first_button_shown){
					$html_text .= '<a class="qbutton" href="'.get_post_meta(get_the_ID(), "qode_slide-button-link", true).'">'.get_post_meta(get_the_ID(), "qode_slide-button-label", true).'</a>';
				}
				if($is_second_button_shown){
					$html_text .= '<a class="qbutton white"' . $button_style . 'href="'.get_post_meta(get_the_ID(), "qode_slide-button-link2", true).'">'.get_post_meta(get_the_ID(), "qode_slide-button-label2", true).'</a>';
				}

				if($is_any_button_shown) {
					$html_text .= '</div>'; //close div.slide_button_holder
				}

				if(get_post_meta(get_the_ID(), "qode_slide-anchor-button", true) !== '') {
					$slide_anchor_style = array();
					if($text_color !== '') {
						$slide_anchor_style[] = $text_color;
					}

					if($slide_anchor_style !== '') {
						$slide_anchor_style = 'style="'. implode(';', $slide_anchor_style).'"';
					}

					$html_text .= '<div class="slide_anchor_holder"><a '.$slide_anchor_style.' class="slide_anchor_button anchor" href="'.get_post_meta(get_the_ID(), "qode_slide-anchor-button", true).'"><span class="arrow_carrot-down"></span></a></div>';
				}

				$html_text .= '</div>';

				$html .= '<div class="slider_content_outer">';

				if($separate_text_graphic != 'yes'){
					$html .= '<div class="slider_content '.$content_alignment.'" style="'.$content_width.$content_xaxis.$content_yaxis_start.'" data-start="'.$content_width.' opacity:1; '.$content_xaxis.' '.$content_yaxis_start.'" data-300="opacity: 0; '.$content_xaxis.' '.$content_yaxis_end.'">';
					$html .= $html_thumb;
					$html .= $html_text;
					$html .= '</div>';
				}else{
					$html .= '<div class="slider_content '.$graphic_alignment.'" style="'.$graphic_width.$graphic_xaxis.$graphic_yaxis_start.'" data-start="'.$graphic_width.' opacity:1; '.$graphic_xaxis.' '.$graphic_yaxis_start.'" data-300="opacity: 0; '.$graphic_xaxis.' '.$graphic_yaxis_end.'">';
					$html .= $html_thumb;
					$html .= '</div>';
					$html .= '<div class="slider_content '.$content_alignment.'" style="'.$content_width.$content_xaxis.$content_yaxis_start.'" data-start="'.$content_width.' opacity:1; '.$content_xaxis.' '.$content_yaxis_start.'" data-300="opacity: 0; '.$content_xaxis.' '.$content_yaxis_end.'">';
					$html .= $html_text;
					$html .= '</div>';
				}

				$html .= '</div>';
				$html .= '</div>';

				$postCount++;
			endwhile;
			else:
				$html .= __('Sorry, no slides matched your criteria.','qode');
			endif;
			wp_reset_query();

			$html .= '</div>';
			if($found_slides > 1){
				$html .= '<ol class="carousel-indicators" data-start="opacity: 1;" data-300="opacity:0;">';
				query_posts( $args );
				if ( have_posts() ) : $postCount = 0; while ( have_posts() ) : the_post();

					$html .= '<li data-target="#qode-'.$slider.'" data-slide-to="'.$postCount.'"';
					if($postCount == 0){
						$html .= ' class="active"';
					}
					$html .= '></li>';

					$postCount++;
				endwhile;
				else:
					$html .= __('Sorry, no posts matched your criteria.','qode');
				endif;

				wp_reset_query();
				$html .= '</ol>';
				$html .= '<a class="left carousel-control" href="#qode-'.$slider.'" data-slide="prev" data-start="opacity: 0.35;" data-300="opacity:0;"><span class="prev_nav" '.$navigation_margin_top.'><i class="fa fa-angle-left"></i></span><span class="thumb_holder" '.$navigation_margin_top.'><span class="thumb_top clearfix"><span class="arrow_left"><i class="fa fa-angle-left"></i></span><span class="numbers"><span class="prev"></span> / '.$postCount.'</span></span><span class="img_outer"><span class="img"></span></span></span></a>';
				$html .= '<a class="right carousel-control" href="#qode-'.$slider.'" data-slide="next" data-start="opacity: 0.35;" data-300="opacity:0;"><span class="next_nav" '.$navigation_margin_top.'><i class="fa fa-angle-right"></i></span><span class="thumb_holder" '.$navigation_margin_top.'><span class="thumb_top clearfix"><span class="numbers"> <span class="next"></span> / '.$postCount.'</span><span class="arrow_right"><i class="fa fa-angle-right"></i></span></span><span class="img_outer"><span class="img"></span></span></span></a>';
			}
			$html .= '</div>';
		}


		return $html;
	}
}
add_shortcode('qode_slider', 'qode_slider');

/* Qode Carousel shortcode */

if (!function_exists('qode_carousel')) {
	function qode_carousel( $atts, $content = null ) {
		$args = array(
			"carousel" 			=> "",
			"orderby"  			=> "date",
			"order"    			=> "ASC",
			"show_navigation"	=> "",
			"control_style" 	=> "control_style",
			"show_in_two_rows" 	=> ""
		);

		extract(shortcode_atts($args, $atts));

		$html = "";


		if ($carousel != "") {
			$carousel_holder_classes = array();

			if($show_in_two_rows == 'yes') {
				$carousel_holder_classes[] = 'two_rows';
			}

			$html .= "<div class='qode_carousels_holder clearfix ".implode(' ', $carousel_holder_classes)."'><div class='qode_carousels ".$control_style."'><ul class='slides'>";

			$q = array('post_type'=> 'carousels', 'carousels_category' => $carousel, 'orderby' => $orderby, 'order' => $order, 'posts_per_page' => '-1');

			query_posts($q);
			$have_posts = false;

			if ( have_posts() ) : $post_count = 1; $have_posts = true; while ( have_posts() ) : the_post();

				if(get_post_meta(get_the_ID(), "qode_carousel-image", true) != "") {
					$image = get_post_meta(get_the_ID(), "qode_carousel-image", true);
				} else {
					$image = "";
				}

				if(get_post_meta(get_the_ID(), "qode_carousel-hover-image", true) != ""){
					$hover_image = get_post_meta(get_the_ID(), "qode_carousel-hover-image", true);
					$has_hover_image = "has_hover_image";
				} else {
					$hover_image = "";
					$has_hover_image = "";
				}

				if(get_post_meta(get_the_ID(), "qode_carousel-item-link", true) != ""){
					$link = get_post_meta(get_the_ID(), "qode_carousel-item-link", true);
				} else {
					$link = "";
				}

				if(get_post_meta(get_the_ID(), "qode_carousel-item-target", true) != ""){
					$target = get_post_meta(get_the_ID(), "qode_carousel-item-target", true);
				} else {
					$target = "_self";
				}

				$title = get_the_title();

				//is current item not on even position in array and two rows option is chosen?
				if($post_count % 2 !== 0 && $show_in_two_rows == 'yes') {
					$html .= "<li class='item'>";
				} elseif($show_in_two_rows == '') {
					$html .= "<li class='item'>";
				}

				$html .= '<div class="carousel_item_holder">';

				if($link != ""){
					$html .= "<a href='".$link."' target='".$target."'>";
				}

				if($image != ""){
					$html .= "<span class='first_image_holder ".$has_hover_image."'><img src='".$image."' alt='".$title."'></span>";
				}

				if($hover_image != ""){
					$html .= "<span class='second_image_holder ".$has_hover_image."'><img src='".$hover_image."' alt='".$title."'></span>";
				}

				if($link != ""){
					$html .= "</a>";
				}

				$html .= '</div>';

				//is current item on even position in array and two rows option is chosen?
				if($post_count % 2 == 0 && $show_in_two_rows == 'yes') {
					$html .= "</li>";
				} elseif($show_in_two_rows == '') {
					$html .= "</li>";
				}

				$post_count++;

			endwhile;

			else:
				$html .= __('Sorry, no posts matched your criteria.','qode');
			endif;

			wp_reset_query();

			$html .= "</ul>";

			if($show_navigation != 'no' && $have_posts) {
				//generate navigation html
				$html .= '<ul class="caroufredsel-direction-nav">';

				$html .= '<li class="caroufredsel-prev-holder">';

				$html .= '<a id="caroufredsel-prev" class="qode_carousel_prev caroufredsel-navigation-item caroufredsel-prev" href="javascript: void(0)">';

				$html .= '<span class="arrow_carrot-left"></span>';

				$html .= '</a>';

				$html .= '</li>'; //close li.caroufredsel-prev-holder

				$html .= '<li class="caroufredsel-next-holder">';
				$html .= '<a class="qode_carousel_next caroufredsel-next caroufredsel-navigation-item" id="caroufredsel-next" href="javascript: void(0)">';

				$html .= '<span class="arrow_carrot-right"></span>';

				$html .= '</a>';

				$html .= '</li>'; //close li.caroufredsel-next-holder

				$html .= '</ul>'; //close ul.caroufredsel-direction-nav
			}
			$html .= "</div></div>";

		}

		return $html;
	}
}
add_shortcode('qode_carousel', 'qode_carousel');


/* Select Image Slider with no space shortcode */

if (!function_exists('image_slider_no_space')) {
    function image_slider_no_space($atts, $content = null) {
        global $qode_options;
        $args = array(
            "images"    				=> "",
            "height"    				=> "",
			"on_click"  				=> "",
			"custom_links" 				=> "",
			"custom_links_target" 		=> "",
			"navigation_style"			=> "",
			"highlight_active_image" 	=> ""
        );

        extract(shortcode_atts($args, $atts));

        //init variables
        $html = "";
		$image_gallery_holder_styles 	= '';
		$image_gallery_holder_classes 	= '';
		$image_gallery_item_styles   	= '';
		$custom_links_array			 	= array();
		$using_custom_links			 	= false;

		//is height for the slider set?
		if($height !== '') {
			$image_gallery_holder_styles .= 'height: '.$height.'px;';
			$image_gallery_item_styles .= 'height: '.$height.'px;';
		}

		//are we using custom links and is custom links field filled?
		if($on_click == 'use_custom_links' && $custom_links !== '') {
			//create custom links array
			$custom_links_array = explode(',', strip_tags($custom_links));
		}

		if($navigation_style !== '') {
			$image_gallery_holder_classes = $navigation_style;
		}

		if($highlight_active_image == 'yes') {
			$image_gallery_holder_classes .= ' highlight_active';
		}

        $html .= "<div class='qode_image_gallery_no_space ".$image_gallery_holder_classes."'><div class='qode_image_gallery_holder' style='".$image_gallery_holder_styles."'><ul>";



        if($images != '' ) {
            $images_gallery_array = explode(',',$images);
        }

		//are we using prettyphoto?
		if($on_click == 'prettyphoto') {
			//generate random rel attribute
			$pretty_photo_rel = 'prettyPhoto[rel-'.rand().']';
		}


		//are we using custom links and is target for those elements chosen?
		if($on_click == 'use_custom_links' && in_array($custom_links_target, array('_self', '_blank'))) {
			//generate target attribute
			$custom_links_target = 'target="'.$custom_links_target.'"';
		}

        if(isset($images_gallery_array) && count($images_gallery_array) != 0) {
			$i = 0;
            foreach($images_gallery_array as $gimg_id) {
				$current_item_custom_link = '';

                $gimage_src = wp_get_attachment_image_src($gimg_id,'full',true);
                $gimage_alt = get_post_meta($gimg_id, '_wp_attachment_image_alt', true);

				$image_src    = $gimage_src[0];
				$image_width  = $gimage_src[1];
				$image_height = $gimage_src[2];

				//is height set for the slider?
				if($height !== '') {
					//get image proportion that will be used to calculate image width
					$proportion = $height / $image_height;

					//get proper image widht based on slider height and proportion
					$image_width = ceil($image_width * $proportion);
				}

                $html .= '<li><div style="'.$image_gallery_item_styles.' width:'.$image_width.'px;">';

				//is on click event chosen?
				if($on_click !== '') {
					switch($on_click) {
						case 'prettyphoto':
							$html .= '<a class="prettyphoto" rel="'.$pretty_photo_rel.'" href="'.$image_src.'">';
							break;
						case 'use_custom_links':
							//does current image has custom link set?
							if(isset($custom_links_array[$i])) {
								//get custom link for current image
								$current_item_custom_link = $custom_links_array[$i];

								if($current_item_custom_link !== '') {
									$html .= '<a '.$custom_links_target.' href="'.$current_item_custom_link.'">';
								}
							}
							break;
						case 'new_tab':
							$html .= '<a href="'.$image_src.'" target="_blank">';
							break;
						default:
							break;
					}
				}

				$html .= '<img src="'.$image_src.'" alt="'.$gimage_alt.'" />';

				//are we using prettyphoto or new tab click event or is custom link for current image set?
				if(in_array($on_click, array('prettyphoto', 'new_tab')) || ($on_click == 'use_custom_links' && $current_item_custom_link !== '')) {
					//if so close opened link
					$html .= '</a>';
				}

				$html .= '</div></li>';

				$i++;
            }
        }

        $html .= "</ul>";
        $html .= '</div>';
        $html .= '<div class="controls">';
        $html .= '<a class="prev-slide" href="#"><span class="arrow_carrot-left"></span></a>';
        $html .= '<a class="next-slide" href="#"><span class="arrow_carrot-right"></span></a>';
        $html .= '</div></div>';

        return $html;
    }

	add_shortcode('image_slider_no_space', 'image_slider_no_space');
}