<?php
namespace Front\OfficeBundle\Services;
// Instance
use Front\OfficeBundle\Services\GdApi\PhpThumb as PhpThumb;

use Front\OfficeBundle\Services\GdApi\ThumbBase as ThumbBase;
use Front\OfficeBundle\Services\GdApi\GdThumb as GdThumb;


define('THUMBLIB_BASE_PATH', dirname(__FILE__));
define('THUMBLIB_PLUGIN_PATH', THUMBLIB_BASE_PATH . '/GdApi/thumb_plugins/');
define('DEFAULT_THUMBLIB_IMPLEMENTATION', 'gd');


class GdApi{
	private $oKernel;
	
	public function __construct()	{
		global $kernel;
		if ('AppCache' == get_class($kernel)) {
			$kernel = $kernel->getKernel();
		}
		$this->oKernel = $kernel;
	}

	/**
	 * Which implemenation of the class should be used by default
	 * 
	 * Currently, valid options are:
	 *  - imagick
	 *  - gd
	 *  
	 * These are defined in the implementation map variable, inside the create function
	 * 
	 * @var string
	 */
	public static $defaultImplemenation = DEFAULT_THUMBLIB_IMPLEMENTATION;
	/**
	 * Where the plugins can be loaded from
	 * 
	 * Note, it's important that this path is properly defined.  It is very likely that you'll 
	 * have to change this, as the assumption here is based on a relative path.
	 * 
	 * @var string
	 */
	public static $pluginPath = THUMBLIB_PLUGIN_PATH;
	
	/**
	 * Factory Function
	 * 
	 * This function returns the correct thumbnail object, augmented with any appropriate plugins.  
	 * It does so by doing the following:
	 *  - Getting an instance of PhpThumb
	 *  - Loading plugins
	 *  - Validating the default implemenation
	 *  - Returning the desired default implementation if possible
	 *  - Returning the GD implemenation if the default isn't available
	 *  - Throwing an exception if no required libraries are present
	 * 
	 * @return GdThumb
	 * @uses PhpThumb
	 * @param string $filename The path and file to load [optional]
	 */
	public static function create ($filename = null, $options = array(), $isDataStream = false)
	{
		// map our implementation to their class names
		$implementationMap = array
		(
			'imagick'	=> 'ImagickThumb',
			'gd' 		=> 'GdThumb'
		);
		
		// grab an instance of PhpThumb
		$pt = PhpThumb::getInstance();
		// load the plugins

		$pt->loadPlugins(self::$pluginPath);
		
		
		$toReturn = null;
		$implementation = self::$defaultImplemenation;
		
		// attempt to load the default implementation
		if ($pt->isValidImplementation(self::$defaultImplemenation))
		{
			$imp = $implementationMap[self::$defaultImplemenation];
			if ( $imp == "GdThumb" ) 
			{
				$toReturn = new GdThumb($filename, $options, $isDataStream);
			}

		}
		// load the gd implementation if default failed
		else if ($pt->isValidImplementation('gd'))
		{
			$imp = $implementationMap['gd'];
			$implementation = 'gd';
			$toReturn = new $imp($filename, $options, $isDataStream);
		}
		// throw an exception if we can't load
		else
		{
			throw new Exception('You must have either the GD or iMagick extension loaded to use this library');
		}
		
		$registry = $pt->getPluginRegistry($implementation);
		$toReturn->importPlugins($registry);
		return $toReturn;
	}
	
}

?>