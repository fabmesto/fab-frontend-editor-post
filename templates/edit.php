<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$id_post = 0;
if ( isset( $_GET['post'] ) ) {
  $id_post = $_GET['post'];

	$query = new WP_Query( array( 'post_type' => $this->atts['post_type'], 'p'=>$id_post, 'posts_per_page' => '-1' ) );

	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();

		$type = get_post_type();
	  $title = get_the_title();
	  $content = get_the_content();

	 endwhile; endif;
	 wp_reset_query();
}else{
	$type = $this->atts['post_type'];
	$title = '';
	$content = '';
}?>

<h1>EDIT</h1>
<div class="breadcrumb">
  <div id="breadcrumbs">
    <a href="<?php echo add_query_arg( array('post'=> null, $this->action_name=> 'list') );?>">Lista</a> /
    <span class="current">Edit</span>
  </div>
</div>
<form id="fab_post_form" method="POST" onsubmit="return fab_ajax_submit(this)">

  <fieldset>

      <label for="post_title"><?php _e( 'Post\'s Title:', 'framework' ); ?></label>

      <input type="text" name="post_title" id="post_title" value="<?php echo $title; ?>" class="required" />

  </fieldset>

  <?php if ( $postTitleError != '' ) { ?>
      <span class="error"><?php echo $postTitleError; ?></span>
      <div class="clearfix"></div>
  <?php } ?>

  <fieldset>

      <label for="post_content"><?php _e( 'Post\'s Content:', 'framework' ); ?></label>


			<?php
			$editor_id = "post_content";
			$settings = array( 'media_buttons' => true, 'drag_drop_upload'=>true );

			wp_editor( $content, $editor_id, $settings );?>
  </fieldset>

  <fieldset>

      <?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
			<input type="hidden" name="post_type" value="<?php echo $type?>" />
			<input type="hidden" name="post_id" id="primary_key" value="<?php echo $id_post?>" />
			<input type="hidden" name="action"  value="fab_ajax_save" />
      <input type="hidden" name="submitted" id="submitted" value="true" />
      <button type="submit"><?php _e( 'Update Post', 'framework'); ?></button>

  </fieldset>

</form>

<div class="ajax_message">

</div>
