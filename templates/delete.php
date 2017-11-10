<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$id_post = 0;
if ( isset( $_GET['post'] ) ) {
    $id_post = $_GET['post'];
}
$query = new WP_Query( array( 'post_type' => $this->atts['post_type'], 'p'=>$id_post, 'posts_per_page' => '-1' ) ); ?>

<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>

<?php

  $title = get_the_title();
  $content = get_the_content();
    /*
        We will be inserting all of our information inside of here
    */

?>

<?php endwhile; endif; ?>
<?php wp_reset_query(); ?>

<h1>Delete</h1>
<div class="breadcrumb">
  <div id="breadcrumbs">
    <a href="<?php echo add_query_arg( array('post'=> null, $this->action_name=> 'list') );?>">Lista</a> /
    <span class="current">Delete</span>
  </div>
</div>
Sei sicuro di eliminare questo: <b><?php echo $title?></b>?
<a href="#" onclick="return fab_ajax_delete('<?php echo $id_post ?>', 'fab_ajax_delete');">Elimina</a>


<div class="ajax_message">

</div>
