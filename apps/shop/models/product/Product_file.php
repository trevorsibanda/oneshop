<?php
/**
 * Product File
 *
 * Files that are linked to a product
 *
 *
 * @package 	Models/Product_File
 * @author 		Trevor Sibanda <trevorsibb@gmail.com>
 * @date 		27 May 2015
 *
 */
class Product_file extends CI_Model
{
	/** ctor */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('files');
	}

	/**
	 * Get a file by id
	 *
	 * @param 		Int 	$	Product File ID
	 *
	 * @return 		Array 	$	Empty on Fail
	 */
	public function get( $file_id )
	{
		$this->db->select('')
		         ->from('product_file')
		         ->where('file_id' , $file_id );
		$query = $this->db->get();
		return $query->row_array();         
	}

	

	/**
	 * Get all shop product files
	 *
	 * @param 		Int 	$	Shop ID
	 *
	 * @return 		Array 	$ 	Shop Files
	 */
	public function shop_files( $shop_id )
	{
		$this->db->select('')
		         ->from('product_file')
		         ->order_by('file_id' ,'DESC')
		         ->where('shop_id' , $shop_id );
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Update a shop file.
	 *
	 * NB: This does not kallow for the file hash, number of bytes to be changed.
	 *     This is done to ensure consistency of file->database records 
	 *
	 * @param 		Int 	$	File ID
	 * @param 		Array 	$	Product file
	 *
	 * @return 		Bool 	
	 */
	public function update( $file_id , $file )
	{
		if( isset($file['hash']) )
		{
			unset($file['hash']);
		}
		if( isset($file['bytes']) )
		{
			unset($file['bytes']);
		}
		$this->db->where('file_id' , $file_id)
				 ->update('product_file' , $file);
		return True;		 
	}

	/**
	 * Remove a product file from database and filesystem
	 *
	 * @param 		Int 	$	Product File ID
	 *
	 * @return 		Bool
	 */
	public function delete( $file_id )
	{
		$product_file = $this->get( $file_id );
		if( empty($product_file) )
		{
			return False;
		} 
		$this->db->where('file_id' , $file_id)
				 ->delete('product_file');
		//now unlink the file
		@unlink( $this->file_path($product_file) );
		return True;		 
	}

	/**
	 * Add a product file into the database.
	 * Note that all zip files are stored as zip files in the server
	 *
	 * @param 		Int 	$ 	Shop ID
	 * @param 		String 	$ 	Product filename e.g Product Specifications.pdf
	 * @param 		String 	$	*Valid* lOCAL/uPLOADED FILE PATH
	 * @param 		Bool 	$	Is the file publicly available on the product page *Default false*
	 *
	 * @return 		Int 	$ 	Product File ID or False
	 */
	public function add( $shop_id , $filename , $localFilePath , $is_public = False )
	{
		
		if( ! file_exists($localFilePath) )
		{
			return False;
		}
		//create zip file
		$this->load->library('zip');

		$file_data = file_get_contents($localFilePath );
		$file_name = strtolower( substr($filename, 0,255) );

		$this->zip->add_data($file_name , $file_data);

		$file_extension = explode('.', $file_name );
		$file_extension = is_array($file_extension ) ? $file_extension[count($file_extension)-1] : 'data';

		$data = array();
		$data['shop_id'] = $shop_id;
		$data['filename'] = $filename;
		$data['hash'] = md5_file($localFilePath);
		$data['bytes'] = filesize($localFilePath);
		$data['mime'] = mime_content_type($localFilePath);
		$data['ext']  = $file_extension;
		$data['is_public'] = $is_public;

		//save to server
		$server_path = ASSETS_FILES . 'product_files/' . $data['hash'] . '.zip';
		$this->zip->archive( $server_path );


		//ok now add file to db
		$this->db->insert('product_file' , $data );
		$p_id = $this->db->insert_id();
		if( $p_id == False or $p_id <= 0)
			return False;
		
		@unlink($localFilePath);
		return $p_id;
	}


}
