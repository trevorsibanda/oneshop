<?php
/**
 * Product Image CRUD operations.
 *
 * Product images
 *
 * @author		Trevor Sibanda	<trevorsibb@gmail.com>
 * @module		Model/Product/Product_Image
 * 
 * @date		6 May 2015
 */
 
 class Product_Image extends CI_Model
 {
	private $empty_image =
	array( 'image_id' => array() ,
	'shop_id' => array() ,
	'image_hash' => array() ,
	'meta' => array() ,
	'created' => array() ,
	'bytes' => array() ,
	'filename' => array()
	);
	 
	/** Ctor */
	public function __construct( )
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('files');
	}
	
	/**
	 * Get a shop image given its ID
	 *
	 * @param		Int		$	Image ID
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function  get(  $image_id )
	{
		$this->db->select(  '*' )
				 ->from  ( 'product_image')
				 ->where ( 'image_id' , $image_id );
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * Get multiple images  given an array of IDS
	 *
	 * @param		Array	$	Image IDs
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function  get_images( $image_ids )
	{
		if( is_string($image_ids) )
		{
			$tmp = explode(',', $image_ids );
			$image_ids = $tmp;
		}
		if( ! is_array($image_ids) or empty($image_ids))
			return array();
		$this->db->select(  '*' )
				 ->from  ( 'product_image')
				 ->order_by('image_id' , 'DESC');
		foreach( $image_ids as $id )
		{
				if( is_array($id) and isset($id['image_id']))
					$this->db->or_where ( 'image_id' , $id['image_id'] );
				else if(is_numeric($id))
					$this->db->or_where ( 'image_id' , $id );
		}
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/**
	 * Get a shop image given the image hash
	 *
	 * @param		String	$	MD5 Image Hash
	 * @param		Int		$	Shop ID, if not given first image with that hash is returned
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function get_by_hash( $image_hash , $shop_id = Null )
	{
		$this->db->select(  '*' )
				 ->from  ( 'product_image')
				 ->where ( 'image_hash' , $image_hash );
		if( ! is_null($shop_id) )
			$this->db->where('shop_id' , $shop_id );
		$query = $this->db->get();
		return $query->row_array();
	}
	
	

	/**
	 * Get all product images for a particular shop
	 *
	 * @param		Int		$	Shop ID
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function shop_images( $shop_id )
	{
		$this->db->select(  '*' )
				 ->from  ( 'product_image')
				 ->order_by('image_id' , 'DESC')
				 ->where ( 'shop_id' , $shop_id );
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/**
	 * Update an image's details
	 *
	 * NB: You cannot change an images hash once set
	 * 
	 * @param		Int		$	Image ID
	 * @param		Array	$	Image
	 *
	 * @return		Bool
	 */
	public function update( $image_id , $image )
	{

		//unset dangerous keys
		if( isset($image['image_id'] ) )
			unset( $image['image_id'] );
		
		$this->db->where ('image_id' , $image_id )
				 ->update( 'product_image'  , $image );
		
		return True;
	}
	
	/**
	 * Add a new image
	 *
	 * @param		Int		$	Shop ID
	 * @param		String	$	Image Meta data
	 *
	 * @todo		Check if valid image
	 *
	 * @return		Int		$	Image ID or False on fail
	 */
	public function add( $shop_id , $meta_data )
	{
		$keys = array();
		$data = array();

		//upload file
		$config['upload_path']          = $this->config->item('product_images_path');
        $config['allowed_types']        = 'gif|jpg|csv|png|jpeg';
        $config['max_size']             = 4096; //4MB
        $config['max_width']            = 0;
        $config['max_height']           = 0;
        $config['encrypt_name']          = True;
        $config['file_ext_lower']       = True;

        

        $this->load->library('upload' , $config );

        $this->upload->initialize(  $config );
        $this->upload->set_allowed_types('*');

        if ( ! $this->upload->do_upload('file') )
        {
        	
        	return False; //upload failed
        }
        else
        {
            //successful upload
            $data = $this->upload->data();
            //must be image
            if(  ! $data['is_image'] )
            {
            	
            	@unlink($data['full_path']);
            	return False;
            }
            
        	$keys['filename'] = $data['file_name'];
        	$keys['hash'] =  md5_file( $data['full_path'] );
        	$keys['hash'] = md5_file( $data['full_path'] );
			$keys['size_kb'] = $data['file_size'];
			$keys['meta'] = $meta_data . "\n Resolution: " . $data['image_width'] . 'x' . $data['image_height'];
        }

		
		$keys['shop_id'] = $shop_id;
		
		
	
		$this->db->insert('product_image' , $keys );
		$db_id = $this->db->insert_id();	
		if( $db_id > 0 )
		{
			$config['source_image'] = $data['full_path'];
			$config['new_image'] =  $this->config->item('product_images_path') . 'thumb/' . $data['file_name'];
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width']         = 255;
			$config['height']       = 255;

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
			return $db_id;
		}
		else
		{
			//failed to add to db, remove file
			@unlink( $data['full_path'] );
		}
		return False;
	}
	
	/**
	 * Delete an image
	 *
	 * @param		Int		$	Image ID
	 *
	 * @return		Bool
	 */
	public function delete( $image_id  )
	{
		$image = $this->get( $image_id );
		if( empty($image ) )
			return False;
		$this->db->where('image_id' , $image_id )
				 ->delete('product_image');

		//delete file
		@unlink( $this->image_path($image) );		 

		return True;		 
	}
	
	
	
	/**
	 * Checks if an image is valid
	 *
	 * Checks data structure but can be used to test the file
	 *
	 * @param		Array	$	Image
	 * @param		Bool	$	Test file
	 *
	 * @return		Bool
	 */
	public function is_valid( $image , $test_file = False )
	{
		foreach( $this->empty_image as $key => $value )
			if( ! isset( $settings[ $key ]  ) )
				return False;
		if( $test_file )
			if( ! file_exists(  $this->image_path( $image ) ))
				return False;
		return True; 
	}
	
	
	public function image_path(  $image )
	{
		if( is_array($image) and isset( $image['filename'] ) )
			return $this->config->item('product_images_path') . $image['filename'];
	}
	
	/**
	 * Return a watermarked version of an image.
	 *
	 * The file contents are returned
	 *
	 * @param		Array	$	Image
	 * @param		String	$	Watermark type
	 *
	 * @return		Bytes
	 */
	public function watermark( $image )
	{
		
	}
	
	
	
 };
 