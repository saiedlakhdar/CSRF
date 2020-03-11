<?php


namespace CSRF;

use mysql_xdevapi\Exception;

/**
 * TODO : set_error_handler to hanlde all errors
 * singleton design pattern
 * Class Csrf
 * @package CSRF
 */

class Csrf
{

    private static $_instance     ;
    private $_session   = null    ;
    private $_prefix    = 'csrf_' ;
    /**
     * default timeslap oneday
     * @var int
     */
    private $_timeslap = 0        ;
    private $_token               ;
    private $_passedToken         ;
    public $_tokenName            ;

    /**
     * Prevent call outside class instancation
     * Csrf constructor.
     */
    private function __construct(Object $session = null )
    {
        if ( session_status() == PHP_SESSION_NONE ){
            throw new \Exception("session isn't started ") ;
        }
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
     * @throws \Exception
     */
    public static function getInstance()
    {
        if(self::$_instance === null ){
            self::$_instance = new self($session = null ) ;
        }
        return self::$_instance ;
    }

    /**
     * TODO : Sanitaize key name to be string and _
     * @param string $key
     * @return string
     */
    public function _token( String $key = 'token',int $timeslap = 0)
    {
        $this->_tokenName = $this->_prefix .$key ;
        // check passed timeslap
        if ($timeslap != 0 ){
            $this->_timeslap = $timeslap ;
        }

        $userAgent = (isset($_SERVER['HTTP_USER_AGENT'])) ? md5($_SERVER['HTTP_USER_AGENT']) : md5(null);
        $ip = md5(self::getRealIpAddr()) ;
        $sessionid = md5(session_id()) ;
        $token = base64_encode(time() . $userAgent . $ip . $sessionid ) ;

        if (isset($_SESSION[$this->_tokenName])){
            // Regenerate token if timeslap end
//            if ($this->_passedToken){
//                if ($this->_timeslap !=0 && (intval($this->_timeslap) + substr(base64_decode($this->_passedToken),0, 10)) < time()){
//                    $token = base64_encode(time() . $userAgent . $ip . $sessionid ) ;
//                    return $_SESSION[$this->_tokenName] = $this->_token = $token ;
//                }
//            }
            return $this->_token = $_SESSION[$this->_tokenName] ;
        }
        $_SESSION[$this->_tokenName] = $this->_token = $token  ;
        return $this->_token  ;
    }

    /**
     * @param String $key
     * @param String $token
     * @return bool
     * @throws \Exception
     */
    public function check(String $key ,String $token  )
    {
        $this->_passedToken = $token ;
        $this->_token() ;
        if(!isset($_SESSION[$key] )) {
            throw new \Exception('Missing CSRF session token.') ;
        }

        if (!$token){
            throw new \Exception('Missing CSRF form token.');
        }
        $useragent = (isset($_SERVER['HTTP_USER_AGENT'])) ? md5($_SERVER['HTTP_USER_AGENT']) : md5(null) ;
        if ($useragent != substr(base64_decode($token),10,32)){
            throw  new \Exception('Be careful, You pass a data from outside.') ;
        }
        $ip = md5(self::getRealIpAddr()) ;
        if ($ip != substr(base64_decode($token),42,32)){
            throw  new \Exception('Your IP address was changed try agian.') ;
        }
        $sessid = md5(session_id()) ;
        if ($sessid != substr(base64_decode($token),74,32)){
            throw  new \Exception('Session Id was changed.') ;
        }
        if ($_SESSION[$key] != $token){
            throw  new \Exception('Invalid CSRF token.') ;
        }

        if ($this->_timeslap !=0 && (intval($this->_timeslap) + substr(base64_decode($token),0, 10)) < time()){
            throw new \Exception('CSRF token has Expired.') ;
        }

        return true ;
    }

    /**
     *
     */
    public function inputToken()
    {
        $output = '<input type="hidden" name="'.$this->_tokenName.'" value="'.$this->_token().'">' ;
        echo $output ;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function checkPost()
    {
        if (isset($_POST[$this->_tokenName])){
            return true ;
        }
        throw new \Exception('Invalid CSRF token') ;

    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function checkGet()
    {
        if (isset($_GET[$this->_tokenName])){
            return true ;
        }
        throw new \Exception('Invalid CSRF token') ;
    }


    /**
     * TODO : Get ip from ip library
     * @return mixed
     */
    public static function getRealIpAddr()
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
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return self::ipIsOffline($ip) == false ? $ip : self::ipIsOffline($ip) ;
    }

    /**
     * @param $ip
     * @return bool|string
     */
    private static function ipIsOffline($ip)
    {
        if (strlen($ip) > 6) {
            return false ;
        }
        return gethostbyname(gethostname()) ;
    }

}


