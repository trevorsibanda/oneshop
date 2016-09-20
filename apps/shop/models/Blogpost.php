<?php
/**
 * OneShop BlogPost.
 *
 * Simple blogpost. 
 *
 * @author 		Trevor Sibanda <trevorsibb@gmail.com> 
 * @package 	Models/Blog
 * @date 		5 July 2015
 *
 */

class BlogPost extends CI_model
{
	/** DB table **/
	private $_table = 'shop_blogpost';

	/** Shop id **/
	private $_shop_id;

	/** ctor **/
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Cleans html by removing dangerous tags, preventing xss
	 * attacks and fixing broken tags.
	 *
	 * @param 	String 		$	Html Data 
	 *
	 * @return 	String 		$	Fixed html
	 */
	public function clean_html( $html )
	{
		return $this->system->safe_html( $html );
	}

	public function shop_id(  $shop_id = Null )
	{
		if( ! is_null($shop_id) )
			$this->_shop_id = $shop_id;
		return $this->_shop_id;
	}


	/**
	 * Get a blogpost given the post id and shop id.
	 *
	 * @param 		Int 	$	Post ID
	 * @param 		Int 	$	Shop ID
	 *
	 * @return 		Array 	$	Empty on fail
	 */
	public function get(  $post_id , $shop_id = Null )
	{
		$this->db->where('post_id' , $post_id )
						  ->from( $this->_table );
		if( $shop_id != Null )
			$this->db->where('shop_id' , $shop_id );
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * Get multiple blog posts given the shop id and post id's
	 *
	 * @param 		Array 	$	Post IDs
	 * @param 		Int 	$	Shop ID
	 *
	 * @return 		Array
	 */
	public function get_multiple(  $post_ids  , $shop_id )
	{
		if( ! is_array($post_ids))
			return array();
		$this->db->where( 'shop_id' , $shop_id )
				 ->from(  $this->_table );
		foreach(  $post_ids as $post_id )
		{
			$this->db->or_where('post_id' , $post_id );
		}				  
		$query = $this->db->order_by('post_id' , 'DESC')->get();
		return $query->result_array();
	}

	/**
	 * Get all shop blog posts
	 *
	 * @param 		Int 	$	Shop ID
	 *
	 * @return 		Array 
	 */
	public function get_shop_blog( $shop_id  , $offset = 0 , $limit = 5 )
	{
		$this->db->order_by('post_id' , 'DESC');

		if( $offset != 0 and ! is_null($offset) )
			$this->db->offset( $offset );
		if( $limit != null)
			$this->db->limit( $limit );
		$this->db->where( 'shop_id' , $shop_id  );
		$query = $this->db->get( $this->_table );
		return $query->result_array();

	}

	/**
	 * Get top blog posts.
	 *
	 * @param 		Int 	$	Limit, Default 3
	 * @param 		String 	$	DB table column to use for sorting.
	 * 
	 * @return 		Array
	 */
	public function get_top_posts(  $limit = 3 , $parameter = 'views'  )
	{
		$query = $this->db->order_by($parameter , 'DESC')
						  ->limit( $limit )
						  ->get_where( $this->_table , array( 'shop_id' => $this->_shop_id ) );
		return $query->result_array();
	}

	/**
	 * Get top blog posts.
	 *
	 * @param 		Int 	$	Limit, Default 3
	 * @param 		String 	$	Order DESC, ASC.
	 * 
	 * @return 		Array
	 */
	public function get_latest_posts(   $limit = 3 , $order = 'DESC' )
	{
		$query = $this->db->order_by($parameter , $order )
						  ->limit( $limit )
						  ->get_where( $this->_table , array( 'shop_id' => $this->_shop_id ) );
		return $query->result_array();
	}

	public function get_next_post( $post )
	{
		$query = $this->db->order_by('post_id' , 'ASC')
				 ->where('post_id > ' , $post['post_id'] )
				 ->limit( 1 )
				 ->get( $this->_table );

		return $query->row_array();		 
	}

	public function get_prev_post( $post )
	{
		$query = $this->db->order_by('post_id' , 'DESC')
				 ->where('post_id < ' , $post['post_id'] )
				 ->limit( 1 )
				 ->get( $this->_table );

		return $query->row_array();
	}

	/**
	 * Update the post
	 *
	 * @param 		Int 	$	Post ID 	
	 * @param 		Array 	$	Post
	 *
	 * @return 		Bool 
	 */
	public function update( $post_id , $post )
	{
		if( ! is_array($post))
			return False;
		unset($post['post_id']);
		if(is_array($post['tags']))
			$post['tags'] = implode(',', $post['tags']);

		$post['date_published'] = date('Y-m-d');
		$post['extract'] = strip_tags($post['html']);
		//limit extract size
		$post['extract'] = substr($post['extract'], 0 , 255 ) . '...'; //255 characters and ellipsis

		$this->db->where('post_id' , $post_id )
				 ->update($this->_table , $post );
		return True;		 
	}

	/**
	 * Add a new blogpost to the database.
	 *
	 * @param 		Int 	$	Shop ID
	 * @param 		String 	$	Blogpost title
	 * @param 		String 	$	Post html from editor
	 * @param 		String 	$	Post author
	 * @param 		Mixed 	$	String or array of tags. comma seperated
	 * @param 		Int 	$	Image id
	 *
	 * @return 		Int 	$	Post id or false on fail 
	 */
	public function add( $shop_id , $title , $html , $author , $tags , $image_id )
	{
		$post = array();
		$post['shop_id'] = $shop_id;
		$post['title'] = $title;
		$post['html'] = $this->clean_html( $html );
		$post['author'] = $author;
		$post['tags'] = ( is_array($tags) ? implode(',', $tags ) : $tags );
		$post['image_id'] = $image_id;
		$post['date_published'] = date('Y-m-d');
		$post['extract'] = strip_tags($html);
		//limit extract size
		$post['extract'] = substr($post['extract'], 0 , 255 ) . '...'; //255 characters and ellipsis
		$this->db->insert($this->_table , $post );
		$id = $this->db->insert_id();
		return (  $id <= 0 ? False : $id );
	}

	/**
	 * Delete a database post.
	 *
	 * @param 		Int 	$	Post ID
	 *
	 * @return 		Bool
	 */
	public function remove( $post_id )
	{
		$this->db->where('post_id' , $post_id )
				 ->delete( $this->_table );
		return True;		 
	}

	/**
	 *Count number of blogposts in the shop
	 *
	 * @param 		Int 		$	Shop ID
	 *
	 * @return 		Int
	 */
	public function count( $shop_id  )
	{
		$q =$this->db->where('shop_id' , $shop_id )
				 ->from( $this->_table )
				 ->count_all_results();
		return $q;		 
	}


}