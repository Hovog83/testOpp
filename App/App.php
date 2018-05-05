<?php

namespace App;

use App\helper\Password;
use App\helper\Http;

class App{

    protected static $instance = null;
    
    public static function instance(){
        if (is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function run(){

        $options = [
            'start'    => 1000,
            'httpsPer' => 100,
            'url'      => 'http://www.rollshop.co.il/test.php'
        ];

        $http = new Http($options['url'], $options['httpsPer']);

        $password = new Password($options['start']);

        $http->setpassword_alternator($password);

        $result = $http->start();

        $this->result($result);
    }
    protected function result($result){
        if (!empty($result)){
            echo "password:" . $result['password'] . ' | '. $result['urlWiki']." | ";
        }else{
            echo "false";
        }
    }
}
