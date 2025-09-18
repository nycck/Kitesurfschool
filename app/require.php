<?php
    /**
     * We includen hier alle libraries die we nodig hebben
     * voor het mvc-framework
     */
    require_once 'libraries/Core.php';
    require_once 'libraries/BaseController.php';
    require_once 'libraries/Database.php';
    require_once 'libraries/EmailService.php';
    require_once 'config/config.php';
    require_once 'helpers/helpers.php';
    
    /**
     * Start session
     */
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    /**
     * Maak een instantie of object van de Core-Class
     */
    $init = new Core();
