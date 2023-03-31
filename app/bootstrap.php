<?php
    //Load Config
    require_once 'config/config.php';

    //Load Helpers
    require_once 'helpers/url_helper.php';
    require_once 'helpers/time_between.php';
    require_once 'helpers/formats.php';

    //Autoload core libraries
    spl_autoload_register(function($className) {
        require_once 'libraries/' . $className . '.php';
    }) 
?>