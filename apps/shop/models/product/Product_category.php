<?php
/**
 * Product Category CRUD operations
 * 
 *
 *
 *
 * @author		Trevor Sibanda	<trevorsibb@gmail.com>
 * @date		11 May 2015
 */
 
 class Product_Category extends CI_Model
 {
	private $empty_category = array
	(
		'category_id' => array() ,
		'is_menu' => array() ,
		'shop_id' => array() ,
		'name' => array() ,
		'description' => array() ,
		'image_id' => array() ,
		'parent_id' => array()
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}	
	/**
	 * Get all categories for a particualr shop
	 *
	 * @param		Int		$	Shop ID
	 * @param 		Int 	$	Number of items to return
	 *
	 * @return		Array
	 */
	public function shop_categories( $shop_id  , $count = 0 )
	{
		if( $count > 0)
			$this->db->limit($count);
		$this->db->select(  $this->full_query_string() )
				 ->from( 'product_category' )
				 ->order_by('category_id' , 'DESC')
				 ->where(  'shop_id' , $shop_id );
		$query = $this->db->get();
		if( $query->num_rows() < 1)
			return array();
		$data = $query->result_array();
		foreach( $data as $cat )
		{
			if( $cat['parent_id'] > 0 )
			{
				//dont get children.
				$cat['parent'] = $this->get( $cat['parent_id'] , false );
			}
		}
		return $data;
	}
	
	/**
	 * Get a particular Product Category
	 *
	 * @param		Int		$	Category ID
	 * @param		Bool	$	Return product children in ['children'] key
	 *
	 * @return		Array
	 */
	public function get( $category_id , $get_children = False)
	{
		$this->db->select(  $this->full_query_string() )
				 ->from( 'product_category' )
				 ->where(  'category_id' , $category_id );
		$query = $this->db->get();
		if( $query->num_rows() < 1)
			return array();
		$data = $query->row_array();
		if( $get_children )
		{
			$data['children'] = $this->get_children(  $data['category_id'] );
		}
		return $data;
	}

	/**
	 * Get a category's children
	 *
	 * @param 		Int 	$	Category ID
	 *
	 * @return 		Array
	 */
	public function get_children( $category_id )
	{
		$query = $this->db->get_where('product_category' , array('parent_id' , $category_id ) );
		return $query->result_array();
	}
	
	/**
	 * Add a product category
	 *
	 * @param		Int		$	Shop ID
	 * @param		String	$	Category name
	 * @param		String	$	Category description
	 * @param		Int		$	Product category image
	 * @param		Bool	$	Is the category a menu entry
	 *
	 * @return		Int		$	Product category id or false
	 */
	public function add( $shop_id , $name , $description , $product_category_image = 0 , $is_menu = False , $parent_id = -1 )
	{
		$keys = array();
		foreach( $this->empty_category as $key => $value )
			$keys[ $key ] = '';
		
		$keys['shop_id'] = $shop_id;
		$keys['name'] = $name;
		$keys['description'] = $description;
		$keys['image_id'] = $product_category_image;
		$keys['is_menu'] = $is_menu;
		$keys['parent_id'] = $parent_id;
		
		$this->db->insert('product_category' , $keys );
		return ( $this->db->insert_id() <= 0 ) ? False : $this->db->insert_id();
	}
	
	/**
	 * Checks if a category is valid
	 *
	 * Checks data structure but can be used to test the file
	 *
	 * @param		Array	$	Category
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
	
	
	/**
	 * Update a product category
	 *
	 * @param		Int		$	Product category id
	 * @param		Array	$	Product category
	 *
	 * @return		Bool
	 */
	public function update( $category_id , $category )
	{
		if( ! $this->is_valid( $category ) ) 
			return False;
		//unset dangerous keys
		if( isset($category['category_id'] ) )
			unset( $category['category_id'] );
		
		$this->db->where ('category_id' , $category_id )
				 ->update( 'product_category'  , $category );
		
		return True;
	}
	
	/**
	 * Remove a product category
	 *
	 * Specifying the fix children option, removes all occurences
	 * of the now removed category id as 
	 *
	 * @param		Int		$	Category ID
	 * @param		Bool	$	Fix children
	 *
	 * @return		Bool
	 */
	public function remove( $category_id , $fix_children = False )
	{
		$this->db->where('category_id' , $category_id )
				 ->delete('product_category');
		if( $fix_children )
		{
			$this->db->where('parent_id' , $category_id )
					->update('product_category' , array('parent_id' , 0) );
		}
		return True;
	}
	
	/**
	 * Remove all properties by shop id
	 *
	 * @param		Int		$	Shop ID
	 *
	 * @return		Bool
	 */
	public function remove_shop_categories( $shop_id )
	{
			$this->db->where( 'shop_id' , $shop_id)
					 ->delete('product_category');
			return True;		 
	}
	
	
	private function full_query_string(  )
	 {
		$parts = array();
		foreach( $this->empty_category as $key => $val )
			array_push( $parts , $key ); 
		return implode( " , " , $parts );
	 }
	
 };
 
 