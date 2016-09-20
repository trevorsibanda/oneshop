/**
 * OneShop by Trevor Sibanda
 *
 *
 *
 */

/** Run App **/

 (function()
 	{
 		'use strict';
 		angular.module('app' , 
 			[ 'ngRoute' , 'app.directives', 'app.routes' ,
 			  'app.services' ,  'app.controllers'  ,
 			  'angular-loading-bar' , 'ngWig' ,
 			  'mgo-angular-wizard' , 'ngTagsInput'    ] );
 	}
 )();
 