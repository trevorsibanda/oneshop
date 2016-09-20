<?php
/**
 * Shop Blog
 *
 * Controller for shop blog functionality.
 *
 * @author		Trevor Sibanda <trevorsibb@gmailcom>
 * @package		Controllers/Blog
 * @date		30 May 2015
 *
 */

 class Blog extends OS_Controller
 {
 	public function __construct( )
 	{
 		parent::__construct();
 		
 	}

 	public function index()
 	{
 		//page 1
 		$this->browse(1);
 	}

 	public function post( $blog_entry_id  )
 	{
 		$page = array('title' => 'Blog post' , 'description' =>'Articles, posts, news updates and rare inside info from the best in the industry'  );
		$data = $this->load_shop(  $page );

		$data['post'] = $this->blogpost->get( $blog_entry_id , $data['shop']['shop_id'] );

		if( empty($data['post'])  or $data['post']['shop_id'] != $data['shop']['shop_id'] )
		{
			$this->error_404();
			exit;
		}
		//set page viewed
		$id = 'blogentry_' . $blog_entry_id;
		if( ! isset($_SESSION[$id]))
		{
			$data['post']['views'] += 1; 
			$this->blogpost->update(  $blog_entry_id , $data['post'] );
			$this->php_session->set( $id , time() );	
		}
		
		

		//get top posts
		$data['top_posts'] = $this->blogpost->get_top_posts( $data['shop']['shop_id'] , 5 , 'views');
		//most shared posts
		$data['top_shared_posts'] = $this->blogpost->get_top_posts( $data['shop']['shop_id'] , 5 , 'shares');
		//top tags
		$data['top_tags'] = array();

		$data['post_csrf_token'] = md5( rand() );
		$this->php_session->set('post_csrf_token' , $data['post_csrf_token'] );

		$data['post']['image'] = $this->shop->image->get( $data['post']['image_id'] );

		//set page
		$data['page'] = array('title' => $data['post']['title'] , 'description' => $data['post']['extract'] , 'keywords' => $data['post']['tags'] );
		$data['page']['canonical_url'] = $data['shop']['url'] . '/blog/post' . $data['post']['post_id'];
		$data['post']['tags'] = explode(',', $data['post']['tags']);

		$this->render('blog_entry' , $data);
		
 	}

 	public function page( $page_id , $page_title )
 	{
 		die('@todo');
 	}

 	public function browse( $page = 1 , $limit = null )
 	{
 		//get blogposts
 		$dpage = array('title' => 'Blog' , 'description' =>'Articles, posts, news updates and rare inside info from the best in the industry'  );
		$dpage['page'] = intval($page);
		$data = $this->load_shop(  $dpage );
		//get posts
		$page = intval($page);
		if( $page <= 0)
			$page = 1;
		if( is_null($limit))
			$limit = 5; //5 per page
		
		$offset = ($page-1)*$limit;
		
		$data['posts'] = $this->blogpost->get_shop_blog( $data['shop']['shop_id'] , $offset , $limit );
		foreach ($data['posts'] as $key => $post)
		{
			$data['posts'][$key]['image'] = $this->shop->image->get($post['image_id']);
			$data['posts'][$key]['tags'] = explode(',' , $post['tags']);
		}

		$data['total_posts'] = $this->blogpost->count(  $data['shop']['shop_id'] );

		//get top posts
		$data['top_posts'] = $this->blogpost->get_top_posts( $data['shop']['shop_id'] , 5 , 'views');
		//most shared posts
		$data['top_shared_posts'] = $this->blogpost->get_top_posts( $data['shop']['shop_id'] , 5 , 'shares');
		//top tags
		$data['top_tags'] = array();

		$data['has_next_page'] = False;
		
		$this->render('blog' , $data ); 
 	}

 	public function post_share_tracker( )
 	{
 		$post_csrf_token = $this->php_session->get('post_csrf_token');
 		$post_id = $this->input->post_get('post_id');

 		$data = $this->load_shop( array( ) );

 		if( $post_csrf_token !== $this->input->post_get('csrf_token'))
 		{
 			die('CSRF Mismatch');
 		}

 		$post = $this->blogpost->get( $post_id , $data['shop']['shop_id'] );

		if( empty($post)  or $post['shop_id'] != $data['shop']['shop_id'] )
		{
			die('Blog post does not exist');
		}

		//set page viewed
		$id = 'blogentry_share_lock_' . $blog_entry_id;
		if( ! isset($_SESSION[$id]))
		{
			$post['shares'] += 1; 
			$this->blogpost->update(  $post_id , $post );
			$this->php_session->set( $id , time() );	
		}

		die('Shared');

 	}





 }

