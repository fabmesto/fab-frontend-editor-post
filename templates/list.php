<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
$current_page = ((get_query_var('paged')) ? get_query_var('paged') : 1);

if($current_user->roles[0]=='administrator'):

$query = new WP_Query( array(
    'post_type' => $this->atts['post_type'],
    'posts_per_page' => $this->atts['posts_per_page'],
		'paged' => $current_page,
    'post_status' => array(
        'publish',
        'pending',
        'draft',
        'private',
        'trash'
    )
) );

$insert_post = add_query_arg( array('post'=> null, $this->action_name=> 'edit') );
?>

<h1>LIST</h1>

<table>
 <tr>
    <th>Post Title</th>
    <th>Post Excerpt</th>
    <th>Post Status</th>
    <th><a href="<?php echo $insert_post; ?>">Insert</a></th>
</tr>

<?php
if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
<?php
$edit_post = add_query_arg( array('post'=> get_the_ID(), $this->action_name=> 'edit') );
$dettagli_post = add_query_arg( array('post'=> get_the_ID(), $this->action_name=> 'dettagli') );
$delete_post = add_query_arg( array('post'=> get_the_ID(), $this->action_name=> 'delete') );
?>
<tr>
    <td><?php echo get_the_title(); ?></td>
    <td><?php the_excerpt(); ?></td>
    <td><?php echo get_post_status( get_the_ID() ) ?></td>
    <td>

      <a href="<?php echo $edit_post; ?>">Edit</a>
      <a href="<?php echo $dettagli_post; ?>">Dettagli</a>
      <a href="<?php echo $delete_post; ?>">Delete</a>

    </td>
</tr>

<?php endwhile; endif; ?>

</table>

<?php echo paginate_links( array('total'=>$query->max_num_pages) ); ?>
<?else:?>
<?php $this->render('accesso-negato')?>
<?endif;?>
