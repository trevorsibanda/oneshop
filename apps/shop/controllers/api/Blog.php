<?php
/**
 * OneSHOP Admin Blog API
 *
 * This is the api used for the admin interface to manage files
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @packages	Controllers/Admin_API
 *
 * @todo 	Check user permissions
 */

require_once( APPPATH . 'core/OS_AdminController.php');


class Blog extends OS_AdminController
{

	/** Constructor **/
	public function __construct()
 	{
 		parent::__construct();
 		$this->set_app_mode('json');
 		$this->data = $this->load_shop();
 		$this->shop->logger->shop_id( $this->data['shop']['shop_id'] );
 		$this->shop->logger->user_id( isset($_SESSION['user']['user_id'])? $_SESSION['user']['user_id'] : Null );

		$this->load->model('blogpost'); 		
 	}

 	/** Get a blogpost given the blog post id , with max number of items, offset and ordering **/
 	public function get( $post_id = Null )
 	{
 		if( empty($post_id) or is_null($post_id))
 			$this->forbidden();

 		$post = $this->blogpost->get(  $post_id , $this->data['shop']['shop_id'] );

 		if( empty($post))
 			$this->forbidden();
 		$post['tags'] = explode(',', $post['tags']);
 		$post['image'] = $this->shop->image->get(  $post['image_id'] );
 		$this->render('blogpost' , $post );
 	}

 	/** Get all blogposts within a specified constraints **/
 	public function all()
 	{
 		$posts = $this->blogpost->get_shop_blog( $this->data['shop']['shop_id']);
 		foreach ($posts as $key => $post) 
 		{
 			$posts[ $key ]['image'] = $this->shop->image->get( $post['image_id']);
 			$posts[ $key]['tags'] = explode(',', $post['tags']); 
 		}
 		$this->render('blog_posts' , $posts);
 	}

 	/** Update a blogpost **/
 	public function update(  )
 	{

 		$json = $this->read_input();
 		
 		if( $json == False || empty($json ) )
 			$this->forbidden();
 		$expected = array('post_id' , 'title' ,'author' , 'tags' , 'image_id'  ,'html');

 		foreach( $expected as $key )
 		{
 			if( ! isset($json[$key]))
 				$this->error('Expected key ' . $key . ' is not set ');
 			if( $key != 'tags' and (! is_string($json[$key])))
 				$this->error('Invalid data type for key ' . $key );

 		}


 		$article = $this->blogpost->get( $json['post_id'] );
 		if( empty($article) or $article['shop_id'] != $this->data['shop']['shop_id'] )
 		{
 			$this->forbiden();
 		}

 		//@todo sanitize html
 		$article['html'] = $this->system->safe_html( $json['html'] );
 		$article['title'] = xss_clean( $json['title'] );
 		$article['author'] = xss_clean( $json['author'] );
 		$article['date_published'] = date('Y-m-d');

 		$tags = $json['tags'];
 		if( is_array($json['tags']))
 		{
 			$tags = array();
 			foreach ($json['tags'] as $tag) 
	 		{
	 			if( is_string($tag) )
	 				$tag = array('text' => $tag);
	 			if( isset($tag['text']) )
	 				array_push($tags, xss_clean($tag['text']) );
	 		}
 		}
 		elseif( ! is_string($tags) )
 		{
 			return $this->error('Invalid data type for tags');
 		}

 		$article['tags'] = $tags;
 		
 		$image_id = ( isset( $json['image_id'] ) ? $json['image_id'] : 0  );
 		$image = $this->shop->image->get( $image_id );
 		if( empty($image) or ($image['shop_id'] != $this->data['shop']['shop_id'] ) )
 		{
 			return $this->error('At least one image required per blog post');
 		}
 		$article['image_id'] =   $image['image_id'];
 		unset($json['image']);

 		$this->blogpost->update( $json['post_id'] , $article );

 		$article = $this->blogpost->get(  $article['post_id']  , $this->data['shop']['shop_id'] );
 		$this->shop->logger->action('Editted blog post - ' . $article['title'] . ' by ' . $article['author'] );
 		$this->render('article' , $article );
 		
 	}

 	/** Add a new blogpost **/
 	public function add( )
 	{
 		
 		$json = $this->read_input();
 		
 		if( $json == False || empty($json ) )
 			$this->forbidden();
 		$expected = array('title' ,'author' , 'tags' , 'image_id'  ,'html');

 		foreach( $expected as $key )
 		{
 			if( ! isset($json[$key]))
 				$this->error('Expected key ' . $key . ' is not set ');
 			if( $key != 'tags' and (! is_string($json[$key])))
 				$this->error('Invalid data type for key ' . $key );

 		}
 		$article = array();
 		$article['title'] = clean( $json['title'] );
 		$article['author'] = clean( $json['author'] );
 		//@todo santize html
 		$article['html'] = $this->system->safe_html(  $json['html'] );

 		//fix tags
 		$tags = $json['tags'];
 		if( is_array($json['tags']))
 		{
 			$tags = array();
 			foreach ($json['tags'] as $tag) 
	 		{
	 			if( is_string($tag) )
	 				$tag = array('text' => $tag);
	 			if( isset($tag['text']) )
	 				array_push($tags, xss_clean($tag['text']) );
	 		}
 		}
 		elseif( ! is_string($tags) )
 		{
 			return $this->error('Invalid data type for tags');
 		}
 		$article['tags'] = $tags;

 		//check image
 		$image_id = ( isset( $json['image_id'] ) ? $json['image_id'] : 0  );
 		$image = $this->shop->image->get( $image_id );
 		if( empty($image) or ($image['shop_id'] != $this->data['shop']['shop_id'] ) )
 		{
 			return $this->error('At least one image required per blog post');
 		}
 		$article['image_id'] = $image_id;

 		//add article
 		$article_id = $this->blogpost->add( $this->data['shop']['shop_id'] , $article['title'] , $article['html'] , $article['author'] , $tags , $article['image_id'] );
 		if( $article_id === False )
 		{
 			$this->forbidden();
 		}

 		$article = $this->blogpost->get(  $article_id  , $this->data['shop']['shop_id'] );
 		$this->shop->logger->action('Created blog post - ' . $article['title'] . ' by ' . $article['author'] );
 		$this->render('new_article' , $article );
 	}

 	/** Delete a blogpost **/
 	public function remove( $post_id )
 	{	
 		$post = $this->blogpost->get( $post_id , $this->data['shop']['shop_id'] );
 		if( empty($post) or $post == False )
 			$this->forbidden();
 		//remove it
 		$this->blogpost->remove($post['post_id']);
 		$this->shop->logger->action('Deleted blog post -  ' . $post['title']  );
 		$this->render('remove_blogpost' , array('status' => 'success'));
 	}

 }