<?php

class iVoiceAPI
{
    const LOG_FILE = 'var/logs/api.log';

    private $apiKey;
    private $host;

    private $scheduler;

    public function __construct($apiKeyIndex = 1)
    {
        $this->apiKey   = self::getSettings('api_key.'.$apiKeyIndex);
        $this->host     = self::getSettings('host');
    }

    public function getCountries()
    {
        return json_decode(file_get_contents(__DIR__.'/var/countries.json'));
    }

    public function getScheduler()
    {
        if (is_null($this->scheduler)) {
            $this->scheduler = $this->executeRequest(array(), $this->host . '/iclicktocallapi/scheduler/');
        }

        return json_decode($this->scheduler, true);
    }

    public function scheduleCallback($params)
    {
        $content = $this->executeRequest($params, $this->host. '/iclicktocallapi/process_callback/');
        return json_decode($content, true);
    }

    private function executeRequest($params, $address)
    {
        if (!isset($params['IP'])) $params['IP'] = $this->getIP();

        $fields = array_merge($params, array('api_key' => $this->apiKey));

        $fields_string = http_build_query($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $address);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $content = curl_exec($ch);
             
        $curl_errors = curl_error($ch);
        if ($curl_errors) {
            $this->log($curl_errors);
        }

        return $content;
    }

    private function getIP()
    {
        if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '' ) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }

    private function log($string)
    {
        if(!is_writable(__DIR__.'/'.self::LOG_FILE)) { return; }
        $myfile = fopen(__DIR__.'/'.self::LOG_FILE, 'a');
        fwrite($myfile, $string);
        fclose($myfile);
    }
    
    public static function getSettings($key) {
        if (self::$settings == null) {
            self::$settings = include_once(__DIR__.'/settings.ini.php');
        }
        
        return (isset(self::$settings[$key])) ? self::$settings[$key] : false;        
    }
    
    private static $settings = null;
}

?>