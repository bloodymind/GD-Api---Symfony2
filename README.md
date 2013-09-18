GD-Api---Symfony2
=================

GD Api for Symfony2 [ Service ] ( Based on PHP Thumb Library )
GD Link : http://www.php.net/manual/en/ref.image.php
PHP Thumb Library Wiki : https://github.com/masterexploder/PHPThumb/wiki


PHP Thumb Overview

PHP Thumb is a light-weight image manipulation library aimed at thumbnail generation.
It features the ability to resize by width, height, and percentage, create custom crops, 
or square crops from the center, and rotate the image. You can also easily add custom functionality to the library through plugins. 
It also features the ability to perform multiple manipulations per instance (also known as chaining), 
without the need to save and re-initialize the class with every manipulation.

Available :

Available as Symfony2 Service ! 

Questions :

Q : Why not a Vendor ? Why not Bundle 
R : too simple , don't west your time by doing selly thing , pull GD Api at local Service direcetory and instanciate it , done !

How TO :

+ Pull the GD API on your Service Directrory 
ex : 
Src/vnd/BundleProject/Services

+ Add GD API Service name to your config.xml of your bundle
ex :
Src/vnd/BundleProject/Ressources/config/services.yml

services:
    GdApi.service:
        class: Front\OfficeBundle\Services\GdApi
		
+ Add you code to your Bundle

$oGdApi = $this->get( "GdApi.service" );
$thumb = $oGdApi::create( "Image Location" );
$thumb->adaptiveResize( 800 , 600 ); // wiki
$thumb->save( "Image New Location" );
