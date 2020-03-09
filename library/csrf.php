<?php


namespace CSRF;

use phpDocumentor\Reflection\Types\Object_;

/**
 * singleton design pattern
 * Class Csrf
 * @package CSRF
 */

class Csrf
{

    private static $_instance   ;
    private $_session = null    ;

    /**
     * Prevent call outside class instancation
     * Csrf constructor.
     */
    private function __construct(Object $session = null )
    {
        if ($session !== null ){
            $this->_session = $session ;
        }
    }

    /**
     * Prevent clonning class
     */
    private function __clone()
    {

    }

    /**
     * @return Csrf
     */
    public static function getInstance()
    {
        if(self::$_instance === null ){
            self::$_instance = new self($session = null ) ;
        }
        return self::$_instance ;
    }

    public function gToken( string $key)
    {
        $userAgent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : null;
        var_dump($userAgent);
        var_dump(self::getRealIpAddr());


    }
    
    public function check($token)
    {
        
    }

    /**
     * TODO : Get ip from ip library
     * @return mixed
     */
    private static function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            //check ip from share internet
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_CF_CONNECTING_IP']))
        {
            //to check ip is pass from Cloudflare
            $ip=$_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            //to check ip is pass from proxy
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED']))
        {
            //to check ip is pass from proxy
            $ip=$_SERVER['HTTP_X_FORWARDED'];
        }
        elseif (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        {
            //to check ip is pass from proxy
            $ip=$_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_FORWARDED_FOR']))
        {
            //to check ip is pass from proxy
            $ip=$_SERVER['HTTP_FORWARDED_FOR'];
        }
        elseif (!empty($_SERVER['HTTP_FORWARDED']))
        {
            //to check ip is pass from proxy
            $ip=$_SERVER['HTTP_FORWARDED'];
        }
        else
        {
            $ip[]=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

