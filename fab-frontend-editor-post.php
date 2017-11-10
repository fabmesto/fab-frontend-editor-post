<?php
/*
Plugin Name: Fab Frontend Editor Post
Plugin URI: https://www.netedit.it/
Description: Plugin Fab Frontend Editor Post
Author: Fabrizio MESTO
Version: 0.0.1
Author URI: https://www.netedit.it/
Text Domain: fabtest
Domain Path: lang
*/

class Fab_Frontend_Editor_Post {
  public $action_name = 'action';
  public $shortcode_name = 'fabtest';
  public $nonce = "test secret parola";
  public $atts = array(
    'post_type' => 'post',
    'posts_per_page' => 20,
  );

  public $current_action = 'list';

  public function __construct() {
    add_shortcode( $this->shortcode_name, array( &$this, 'shortcode' ) );
    add_action( 'wp_ajax_nopriv_fab_ajax_save', array( &$this, 'ajax_save' ) );
    add_action( 'wp_ajax_fab_ajax_save', array( &$this, 'ajax_save' ) );
    add_action( 'wp_ajax_nopriv_fab_ajax_delete', array( &$this, 'ajax_delete' ) );
    add_action( 'wp_ajax_fab_ajax_delete', array( &$this, 'ajax_delete' ) );
    add_action( 'wp_enqueue_scripts', array( &$this,'enqueue_scripts' ) );
  }

  public function enqueue_scripts(){

    wp_enqueue_script( 'fab-script', plugins_url( 'js/script.js', __FILE__ ), array('jquery'), '1.0.7', true );
    wp_enqueue_style( 'fab-style', plugins_url( 'style.css', __FILE__ ), '1.1' );

    $array_js = array(
      'security' => wp_create_nonce( $this->nonce ),
      'ajax_url' => admin_url('admin-ajax.php'),
    );
    wp_localize_script( 'fab-script', 'fab_obj', $array_js );
  }

  public function shortcode($atts, $content = null){
    $this->atts = shortcode_atts( $this->atts, $atts );
    ob_start();
    if ( isset( $_GET[$this->action_name] ) &&  $_GET[$this->action_name]!='' ) :
      $this->current_action = $_GET[$this->action_name];
    endif;

    $this->render($this->current_action);
    return ob_get_clean();
  }

  public function render($action){
    $action_file = plugin_dir_path( __FILE__ ).'/templates/'.$action.'.php';
    if(file_exists ( $action_file )){
      require_once( $action_file );
    }else{
      echo "Nessuna azione trovata: ".$action;
    }
  }

  public function ajax_delete() {
    if ( wp_verify_nonce( $_POST['security'], $this->nonce ) ){
      if($_POST['post_id']>0){
        wp_delete_post( $_POST['post_id'], false );
        $url = home_url(add_query_arg( array('post'=> null, $this->action_name=> 'list') ));
        echo json_encode(array("id"=>$_POST['post_id'], "errors"=>false, "message"=>"Eliminato con successo!", "url"=>$url));
      }
    }else{
      echo json_encode(array("id"=>false, "errors"=>"nonce", "message"=>"error"));
    }
    wp_die();
  }

  public function ajax_save() {
    if ( wp_verify_nonce( $_POST['security'], $this->nonce ) ){

      if($_POST['post_id']>0){
        $my_post = array(
          'ID'           => $_POST['post_id'],
          'post_title'   => $_POST['post_title'],
          'post_content' => $_POST['post_content'],
        );
        $post_id = wp_update_post( $my_post, true );
        if (is_wp_error($post_id)) {
          $errors = $post_id->get_error_messages();
          echo json_encode(array("id"=>false, "errors"=>$errors, "message"=>"error"));
        }else{
          $url = home_url(add_query_arg( array('post'=> null, $this->action_name=> 'list') ));
          echo json_encode(array("id"=>$post_id, "errors"=>false, "message"=>"Aggiornato con successo!", "url"=>$url));
        }
      }else{
        $my_post = array(
          'post_title'    => wp_strip_all_tags( $_POST['post_title'] ),
          'post_content'  => $_POST['post_content'],
          'post_type'  => $_POST['post_type'],
          'post_status'   => 'publish',
          'post_author'   => 1
        );

        // Insert the post into the database
        $post_id = wp_insert_post( $my_post );
        if (is_wp_error($post_id)) {
          $errors = $post_id->get_error_messages();
          echo json_encode(array("id"=>false, "errors"=>$errors, "message"=>"error"));
        }else{
          $url = home_url(add_query_arg( array('post'=> null, $this->action_name=> 'list') ));
          echo json_encode(array("id"=>$post_id, "errors"=>false, "message"=>"Inserito con successo!", "url"=>$url));
        }
      }


    }else{
      echo json_encode(array("id"=>false, "errors"=>"nonce", "message"=>"error"));
    }
    wp_die();
  }
}

$fab_test = new Fab_Frontend_Editor_Post();
