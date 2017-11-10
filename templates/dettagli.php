<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$id_post = 0;
if ( isset( $_GET['post'] ) ) {
    $id_post = $_GET['post'];
}
$query = new WP_Query( array( 'post_type' => $this->atts['post_type'], 'p'=>$id_post, 'posts_per_page' => '-1' ) );
if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>

<h1>Dettagli</h1>
<div class="breadcrumb">
  <div id="breadcrumbs">
    <a href="<?php echo add_query_arg( array('post'=> null, $this->action_name=> 'list') );?>">Lista</a> /
    <span class="current">Dettagli</span>
  </div>
</div>

<?php endwhile; endif; ?>
