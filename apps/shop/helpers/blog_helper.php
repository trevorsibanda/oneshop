<?php
/**
 * Blog helper
 *
 *
 *
 *
 */

function blog_url( $page = 1)
{
	return '/blog/' . intval($page);
}

function blog_post_url( $post )
{
	return '/blog/post/' . $post['post_id'] . '/' . urlify_text( $post['title'] );
}

function blog_browse_by_url(  $by = 'all' )
{

}