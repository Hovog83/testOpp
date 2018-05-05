<?php

namespace App\helper;

class Http {

    protected $password_alternator;
    protected $url;
    protected $numberPer;
    protected $mh;

    public function __construct($url, $numberPer){
        $this->url = $url;
        $this->numberPer = intval($numberPer);
        $this->mh = curl_multi_init();
    }

    public function setpassword_alternator(Password $password){
        $this->password_alternator = $password;
    }

    protected function init($data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "code=".$data);
        return $ch;
    }

    protected function sethttp(){
        $res = [];
        for ($i = 0; $i < $this->numberPer; $i++) {
            $current_password = $this->password_alternator->getNextPassword();
            $res[$i] = [
                'curl' => $this->init($current_password),
                'password' => $current_password
            ];
            curl_multi_add_handle($this->mh, $res[$i]['curl']);
        }
        return $res;
    }
    
    protected function runhttp(){
        $i = null;
        do {
            curl_multi_exec($this->mh, $i);
            curl_multi_select($this->mh);
        } while ($i > 0);
    }

    protected function httpResult($res){
        foreach ($res as $resource){
            $res = [
                'password' => $resource['password'],    
                'result'   => curl_multi_getcontent($resource['curl'])
            ];
            if (empty($res['result'])){
                $this->password_alternator->addFailedPassword($res['password']);
            }else{
                preg_match('/(https:\/\/.*)<\/body>/i', $res['result'], $urlWiki);

                if (!empty($urlWiki)){
                    return [
                        'urlWiki' => $urlWiki[1],
                        'password' => $res['password']
                    ];
                }
                $result[] = $res;
            }
        }
        return false;
    }
    public function start(){
        do{
            $res = $this->sethttp();
            $this->runhttp();
            $result = $this->httpResult($res);
            if ($result !== false){
                return $result;
            }
            sleep(10);
        } while (true);
        return false;
    }
}
