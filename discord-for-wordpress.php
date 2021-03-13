<?php
/**
 * Plugin Name: Discord for Wordpress
 * Plugin URI: #
 * Description: Simple Discord widgets for Wordpress
 * Version: 0.0.1
 * Text Domain: dfwp
 * Author: Ned_Tom
 * Author URI: https://craftblock.one
*/

require 'vendor/autoload.php';
Mustache_Autoloader::register();

function dfwpconf($funcdata){
    if(file_exists(
            dirname(__FILE__).'/dfwp-conf.php'
    )){
        $conf = include(dirname(__FILE__).'/dfwp-conf.php');
    }else{
        $conf = array('apisrv' => 'https://discord.com');
        //$conf['apisrv'] = 'https://discord.com';
    }

    //if($funcdata == 'apisrv'){
        return $conf[$funcdata];
    //}
}

// add styles
// > stylesheets
function dfwpStyles() {
    wp_enqueue_style( 'dfwpMainStyle', plugins_url( 'css/dfwp-style.css', __FILE__ ));
}

// > register styleheets
add_action('wp_enqueue_scripts','dfwpStyles');

// add shorcodes
// > Loder functions
function testmeeeee(){
    return "some test data";
};

function dfwpmakeList($data){
    $output = "<ul>";
    foreach ($data as $value) {
        $output .= '<li>'.$value->username.'</li>';
    }

    return $output.'</ul>';
}

function dfwpShortCode($atts = [], $content = null, $tag = '') {

    $m = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/tpl'),
    ));

    // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    // override default attributes with user attributes
    $widged_atts = shortcode_atts([
        'id' => '000000000000000000',
        'type' => 'button',
        'skin' => 'basic'
    ], $atts, $tag);

    // proces discord data
    try {
        $dcWidget = json_decode(
            file_get_contents(dfwpconf('apisrv').'/api/guilds/'.$widged_atts['id'].'/widget.json')
        );        
    } catch (Exception $e) {
        $dcWidget = "nodata";
        //die('no widget data!');
    }

    // generate selected widget
    if($dcWidget != NULL){
        if($dcWidget == "nodata"){
            //$Content = "Can't Read widget data";
            return "Can't Read widget data";
        }else{
            //$Content = 'Type: '.$widged_atts['type'].'</br>';
            //$widgetData = (array) $dcWidget; 
            //$widgetData['imgdr'] = dirname(__FILE__).'/logo';
            //$dcWidget->imgdr = dirname(__FILE__).'/logo';
            $dcWidget->imgdr = plugins_url('/img',__FILE__);
            switch ($widged_atts['type']) {

                case 'button':
                    //$Content .= '<a href="'.$dcWidget->instant_invite.'">Join '.$dcWidget->name.'</a>';
                    return $m->render('button', $dcWidget);
                    break;
                case 'oplayers':
                    //$Content .= $dcWidget->presence_count;
                    return $m->render('oplayers', $dcWidget);
                    break;
                case 'list':
                    //$Content .= dfwpmakeList($dcWidget->members); 
                    return $m->render('list', $dcWidget);
                    break;
                case 'joinlist':
                    //$Content .= '<a href="'.$dcWidget->instant_invite.'">Join '.$dcWidget->name.'</a>'.dfwpmakeList($dcWidget->members);
                    return $m->render('joinlist', $dcWidget);
                    break;
                default:
                    //$Content .= "No type Defined!";
                    return "No type Defined!";
            }
        }
    }else{
        //$Content .= "Error, no data Set!!";
        return "Error, no data Set!!";
    }
    //return '<div class="dfwp">'.$Content.'</div>';
}

// > register shorcodes
add_shortcode('dfwp', 'dfwpShortCode');
?>