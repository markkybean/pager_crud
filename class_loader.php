<?php
	
	define('class_path','class/');
   
	Class ClassLoader{

		public function __construct(){

			$this->loadClass();
		}

		public function loadClass(){

			function init_class($class){

				$filename = strtolower($class).".php";
				$file = class_path.$filename;

			    if(file_exists($file) && is_readable($file))
                {
                    include $file;
                }
			}
			
			spl_autoload_register('init_class');
		}
	}

?>
