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

$devMode = true;

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
    //$userList = $dcWidget->members;
    $output = "<ul>";
    foreach ($data as $value) {
        $output .= '<li>'.$value->username.'</li>';
    }

    return $output.'</ul>';
}

function dfwpShortCode($atts = [], $content = null, $tag = '') {
    // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    // override default attributes with user attributes
    $widged_atts = shortcode_atts([
        'id' => '000000000000000000',
        'type' => 'button',
        'skin' => 'basic'
    ], $atts, $tag);

    // proces discord data
    $apisrv = 'https://discord.com'; // for development purpes, change to proxy.

    try {
        $dcWidget = json_decode(
            file_get_contents($apisrv.'/api/guilds/'.$widged_atts['id'].'/widget.json')
        );        
    } catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        $dcWidget = "nodata";
    }

    if($dcWidget != NULL){
        if($dcWidget == "nodata"){
            $Content = "Can't Read widget data";
        }else{
            $Content = 'Type: '.$widged_atts['type'].'</br>';

            switch ($widged_atts['type']) {

                case 'button':
                    $Content .= '<a href="'.$dcWidget->instant_invite.'">Join '.$dcWidget->name.'</a>';
                    break;
                case 'oplayers':
                    $Content .= $dcWidget->presence_count;
                    break;
                case 'list':
                    $Content .= dfwpmakeList($dcWidget->members); 
                    //$Content .= 'test';
                    break;
                case 'joinlist':
                    $Content .= '<a href="'.$dcWidget->instant_invite.'">Join '.$dcWidget->name.'</a>'.dfwpmakeList($dcWidget->members);
                    //$Content .= testmeeeee();
                    break;
                default:
                    $Content .= "No type Defined!";
            }


        }
    }else{
        $Content = "Error, no data Set!!";
    }

    return '<div class="dfwp">'.$Content.'</div>';
}

// > register shorcodes
add_shortcode('dfwp', 'dfwpShortCode');

?>
