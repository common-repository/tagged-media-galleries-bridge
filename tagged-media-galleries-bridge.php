<?php
/*
Plugin Name: Tagged Media Galleries Bridge
Plugin URI: http://www.buildyourwebsite.co.nz/kayakingcoder/tagged-media-galleries-bridge/
Description: Enable the Media-tags plugin to work with Jetpack Gallery
Version: 1.0.1
Author: Kayaking Coder
Author URI: http://www.buildyourwebsite.co.nz/kayakingcoder/
License: GPL2
*/

class TaggedMediaGalleriesBridge {
    public function init()
    {
        add_shortcode('Mediatags_gallery', array($this, 'Mediatags_gallery')); 
    }
    

    function Mediatags_gallery($atts, $content=null, $tableid=null){
        global $post, $mediatags;
	
	$atts['return_type'] = "raw";

	if (!isset($atts['before_list']))
		$atts['before_list'] = "<ul>";

	if (!isset($atts['after_list']))
		$atts['after_list'] = "</ul>";


	if ((!isset($atts['display_item_callback'])) || (strlen($atts['display_item_callback']) == 0))
		$atts['display_item_callback'] = 'default_item_callback';

	if ((isset($atts['post_parent'])) && ($atts['post_parent'] == "this"))
		$atts['post_parent'] = $post->ID;
		
	$atts['call_source'] = "shortcode";
   
	if (!is_object($mediatags)) 
        {
		$mediatags = new MediaTags();
        }
	
        $out = "";
        
	$attachment_posts = $mediatags->get_attachments_by_media_tags($atts);
        
        $out = count($attachment_posts);
        
        
        $ids = "";
        
        foreach($attachment_posts as $attachment_idx => $attachment_post)
        {
                    $ids .= $attachment_post->ID . ",";
        }
                
        if (!empty($ids))
        {
            $val = "";
            if (!isset($atts['type']))
		$atts['type'] = "rectangular";
            if (!isset($atts['columns']))
		$atts['type'] = "2";
            $out = do_shortcode('[gallery columns="'.$atts["columns"].'" type="'.$atts["type"].'" ids="'.$ids.'"]');
        }
       
        return $out;
       
    }
    
}

//
function init_mt2j() {
    $Mediatags2Jetpack = new TaggedMediaGalleriesBridge();
    $Mediatags2Jetpack->init();
}

add_action( 'jetpack_modules_loaded', 'init_mt2j' );
