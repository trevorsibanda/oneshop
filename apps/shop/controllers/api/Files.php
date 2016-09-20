<?php
/**
 * OneSHOP Admin File API
 *
 * This is the api used for the admin interface to manage files
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @packages	Controllers/Admin_API
 *
 * @todo 	Check user permissions
 */

require_once( APPPATH . 'core/OS_AdminController.php');


class Files extends OS_AdminController
{
	/** Constructor **/
	public function __construct()
 	{
 		parent::__construct();
 		$this->set_app_mode('json');
 		$this->data = $this->load_shop();
 		$this->shop->logger->shop_id( $this->data['shop']['shop_id'] );
 		$this->shop->logger->user_id( isset($_SESSION['user']['user_id'])? $_SESSION['user']['user_id'] : Null );

 		
 	}

 	/** Get a file given the file type and file id **/
 	public function get( $type = 'product_image' , $file_id = Null )
 	{
 		if( is_null($file_id))
 			$this->forbidden();
 		$file = array();
 		switch( $type )
 		{
 			case 'product_image':
 			{
 				$file =$this->product->image->get( $file_id );
 			}
 			break;
 			case 'shop_image':
 			{
 				$file =$this->shop->image->get( $file_id );
 			}
 			break;
 			case 'product_file':
 			{
 				$file =$this->product->file->get( $file_id );
 			}
 			break;
 			case 'user_image':
 			{
 				$file = array();
 			}
 			break;
 		}

 		if( ( ! empty($file) ) and $file['shop_id'] == $this->data['shop']['shop_id'] )
 			$this->render('file' , $file );
 		else
 			$this->forbidden();
 	}

 	/** Get all images of a given type within specified constraints **/
 	public function all($type = Null  )
 	{
 		$images = array();
 		switch($type )
 		{
 			case 'shop_images':
 			{
 				$images = $this->shop->image->shop_images( $this->data['shop']['shop_id'] );
 			}
 			break;
 			case 'product_images':
 			{
 				$images = $this->product->image->shop_images(  $this->data['shop']['shop_id'] );

 			}
 			break;
 			case 'product_files':
 			{
 				//yes i know
 				$images = $this->product->file->shop_files( $this->data['shop']['shop_id'] );
 			}
 			break;
 			case 'user_images':
 			{
 				//@todo get user images
 				$images = array();
 			}
 			break;
 		}
 		$this->render('images' , $images );
 	}

 	/** Update a file's meta data given the file id  **/
 	public function update( $file_type )
 	{
 		$json = $this->read_input();
        
        if( ! isset($json['file'] ) or ! isset( $json['file']['meta']) )
            $this->error('Invalid request');
        
        if( ! isset( $json['file']['file_id'] ) and ! isset($json['file']['image_id'] ) )
            $this->error('Invalid request');
        
        $file = array('meta' => xss_clean( $json['file']['meta'] ) );
        $file_id = intval( isset($json['file']['image_id']) ? $json['file']['image_id'] : $json['file']['file_id'] ) ;
        
        switch($file_type )
 		{
 			case 'shop_image':
 			{
 				$image = $this->shop->image->get( $file_id );
                if( empty($image) or ( $image['shop_id'] != $this->data['shop']['shop_id'] ) )
                {
                    $this->error('Image does not exist');
                }
                $this->shop->image->update( $file_id , $file );
                $this->shop->logger->action('Updated shop image ' .$file_id .' meta details  ');
 			
 			}
 			break;
 			case 'product_image':
 			{
 				$image = $this->product->image->get( $file_id );
                if( empty($image) or ( $image['shop_id'] != $this->data['shop']['shop_id'] ) )
                {
                    $this->error('Image does not exist');
                }
                $this->product->image->update( $file_id , $file );
                $this->shop->logger->action('Updated product image ' .$file_id .' meta details  ');
 			
                
 			}
 			break;
 			case 'product_file':
 			{
 				$this->error('This does not work :(');
                //yes i know
 				//$images = $this->product->file->shop_files( $this->data['shop']['shop_id'] );
 			}
 			break;
 			case 'user_image':
 			{
 				//@todo 
 				
 			}
 			break;
 		}
        $this->render('status',array('status'=>'ok'));
        
 	}

 	/** Upload file given the file type **/
 	//@todo Check file types before uploading
 	public function upload( $type = 'product_image' )
 	{
 		
 		if($type  == 'product_image')
 		{
 			
 			$meta = $this->input->post('meta' , true );
 			$meta = 'Product image uploaded at ' . date('r') ;
 			$image_id = $this->product->image->add( $this->data['shop']['shop_id'] ,  $meta );
 			if( $image_id === False)
 			{
 				//failed
 				return $this->error('Failed to save image on server, make sure selected file is a valid image.');
 			}
 			$this->shop->logger->action('Uploaded product image file ' );
 			$this->render_json( $this->product->image->get(  $image_id ) ); 
 		}
 		else if( $type == 'shop_image')
 		{

 			$meta = 'Shop image uploaded at ' . date('r') ;
 			$image_id = $this->shop->image->add( $this->data['shop']['shop_id'] ,  $meta );
 			if( $image_id === False)
 			{
 				//failed
 				
 				return $this->error('Failed to save image on server, make sure selected file is a valid image.');
 			}
 			$this->shop->logger->action('Uploaded shop image ');
 			$this->render_json( $this->shop->image->get( $image_id ) ); 	
 		}
 		else if( $type == 'product_file' )
 		{
 			$file = isset( $_FILES['file']) ? $_FILES['file'] : null;
 			if( is_null($file) or $file['error'])
 				$this->error('Failed to upload file because the file was too large.');

 			$file['name'] = xss_clean($file['name']);

 			$meta = 'Uploaded file at '. date('r');
 			$file_id = $this->product->file->add( $this->data['shop']['shop_id'] , $file['name'] , $file['tmp_name'] , False );
 			$this->shop->logger->action('Uploaded product file ' . $file['name'] );
 			$this->render_json( $this->product->file->get($file_id) );
 		}
 		else if( $type == 'user_image')
 		{
 			$this->error('Not supported');
 		}
 	}

 	/** Delete a file given the file id **/
 	public function delete(  $type = 'product_image' , $file_id = Null )
 	{
 		if( $file_id == Null)
 			$this->forbidden();

 		$file = array();
 		$all = array();

 		//first get the file
 		switch( $type )
 		{
 			case 'product_image':
 			{
 				$file =$this->product->image->get( $file_id );
 			}
 			break;
 			case 'shop_image':
 			{
 				$file =$this->shop->image->get( $file_id );
 			}
 			break;
 			case 'product_file':
 			{
 				$file =$this->product->file->get_by_id( $file_id );
 			}
 			break;
 			case 'user_image':
 			{
 				$file = array();
 			}
 			break;
 		}

 		//check is specified image is owned by shop
 		if( !empty($file) and $file['shop_id'] == $this->data['shop']['shop_id'] )
 		{
 			
	 		//get type of object which will be affected by the change
	 		$object_type = 'product';
	 		if( in_array($type , array('product_image' , 'product_file')))
	 		{
	 			$all = $this->product->shop_products(  $this->data['shop']['shop_id'] );
	 			//get images and files
	 			foreach($all as $key => $product )
		 		{
		 			$all[ $key ]['images'] = $this->product->image->get_images( $product['images'] );
		 			
		 		}
		 		$object_type = 'product';
	 		}
	 		else if( in_array($type , array('shop_image')) )
	 		{
	 			$object_type = 'blog';
	 			$all = array(); //@todo get all blogposts
	 		}

 			//now check for any products or blogposts using this image
 			foreach( $all as $entry )
 			{

 				$anything_changed = False;
				foreach( $entry['images'] as $key => $image )
				{
					if(  $image['image_id']  == $file_id )
					{
						//need to update this product/blogpost to indicate that this file is no longer part of this lists
						unset( $entry['images'][$key] );
						$anything_changed = True;
					}
				}

				//one or more object will be affected by us deleting the image
				if( $anything_changed )
				{
					if( $object_type == 'product' )
					{
						//@todo update product and set to default product image if none exists
						$this->product->update( $entry['product_id'] , $entry );
					}
					else if( $object_type == 'blog')
					{
						//@todo, update blog post and set to default image
					}
					else
					{
						//user image
					}
				}
				
 			}

 			//check if there are any theme pages using this image and delete that entry -> system will automatically
 			//apply the default value for this option
 			$theme_pages = $this->data['theme']['page'];
 			foreach( $theme_pages as $page )
 			{
 				//browse through all page properties
 				foreach( $page as $key => $item )
 				{
 					$anything_changed = False;
 					if( $item['type'] == $type  )
 					{
 						//this is the same type we are interested in
 						//unset this value because it no longer applies
 						$anything_changed = True;
 					}
 					if( $anything_changed )
 					{
 						//@todo unset stored theme data
 						//@todo save new theme data to database
 					}
 				}
 			}

 			//actually delete the file when we are sure we wont cause any problems on our side
 			
 			switch( $type )
	 		{
	 			case 'product_image':
	 			{
	 				$this->shop->logger->action('Deleted product image (' . $file_id . ') ');
                    $this->product->image->delete( $file_id );
	 			}
	 			break;
	 			case 'shop_image':
	 			{
                    $this->shop->logger->action('Deleted shop image (' . $file_id . ') ');
	 				$this->shop->image->delete( $file_id );
	 			}
	 			break;
	 			case 'product_file':
	 			{
                    $this->shop->logger->action('Deleted product file (' . $file_id . ') ');
	 				
	 				$this->product->file->delete( $file_id );
	 			}
	 			break;
	 			case 'user_image':
	 			{
	 				//@todo actually delete user ima
	 			}
	 			break;
	 		}
 			$this->render('file' , array('status'=>'ok') );
 		}	
 		else
 		{

 			$this->forbidden();
 		}
 	}


 }

