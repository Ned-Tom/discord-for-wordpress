<?php
/**
 * Plugin Name: Discord for Wordpress
 * Plugin URI: #
 * Description: Simple Discord widgets for Wordpress
 * Version: 0.0.1
 * Text Domain: dfwp
 * Author: Ned_Tpm
 * Author URI: https://craftblock.one
*/

// add styles
// > stylesheets
function dfwpStyles() {
    wp_enqueue_style( 'dfwpMainStyle', plugins_url( 'css/dfwp-style.css', __FILE__ ));
}

// > register styleheets
add_action('wp_enqueue_scripts','dfwpStyles');

// add shorcodes
// > Loder functions
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
    $dcWidget = NULL;

    try {
        $dcWidget = json_decode(
            file_get_contents('https://discord.com/api/guilds/'.$widged_atts['id'].'/widget.json')
        );
    } catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        $dcWidget = "nodata";
    }

    if($dcWidget != NULL){
        if($dcWidget == "nodata"){
            $Content = "Can't Read widget data";
        }else{
            //$Content = '<a href="'.$dcWidget->instant_invite.'">Join '.$dcWidget->name.'</a>';

            /*function makeList(){
                $userList = $dcWidget->members;
                $output = "<ul>"
                foreach ($userList as $value) {
                    $output .= '<li>'.$value->username.'</li>';
                }
                $output .= '</ul>';

                return $output;
            }*/


            switch ($dcWidget->type) {
                case 'button':
                    $Content = '<a href="'.$dcWidget->instant_invite.'">Join '.$dcWidget->name.'</a>';
                    break;
                case 'oplayers':
                    $Content = $dcWidget->presence_count;
                    break;
                /*case 'list':
                    makeList();
                    break;
                case 'joinlist':
                    $Content = '<a href="'.$dcWidget->instant_invite.'">Join '.$dcWidget->name.'</a>'.makeList();
                    break;*/
                default:
                    $Content = "Not type Defined!";
            }

        }
    }else{
        $Content = "Error, no data Set!!";
    }

    /*$Content = "
    <div class=\"twe_Discord\" srv-id=\"".$widged_atts['id']."\" t=\"".$widged_atts['t']."\">
        <p>Loading..</p>
    </div>
    ";*/

    return $Content;
}

// > register shorcodes
add_shortcode('dfwp', 'dfwpShortCode');


?>