<?php
/**
 * Files Config
 *
 * Contains a config of all files and paths used by OneShop
 *
 *
 */

 /** Allowed Image Types **/
 $config['allowed_image_types'] = array('jpg' , 'png' , 'bmp' );

 /** Allowed Shop Video Types **/
 $config['allowed_video_types'] = array('avi' , 'mp4' , 'flv');

/*** NB: Maximum file sizes can be overriden bythe shop account **/

 /** Maximum image size **/
 $config['max_image_size'] = 1048576; //1 MB

 /** Maximum video size **/
 $config['max_video_size'] = 104857600; //100 MB

 /** Maximum file ize **/
 $config['max_file_size'] = 104857600; //100 Mb

 /** Product Images **/
 $config['product_images_path'] = 'assets/files/product_images/';

 /** Product Videos **/
 $config['product_videos_path'] = 'assets/files/product_videos/';
 
 /** Product Files **/
 $config['product_files_path'] = 'assets/files/product_files/';

 /** Allowed product file types **/
 $config['allowed_product_file_types'] = array('zip' , 'rar'  , 'pdf' , 'doc' , 'txt' , 'mp4' , 'mp3' , 'exe' );

 /** Shop Images **/
 $config['shop_images_path'] = 'assets/files/shop_images/';

 /** Shop Videos **/
 $config['shop_videos_path'] = 'assets/files/shop_videos/';

 





