<?php

namespace Ilovepdf\Tools;

class ServerAnalytics
{


    private $host = 'https://www.google-analytics.com/collect';
    private $calls = [];
    private $tid = null;
    private $cid = null;
    private $uid = null;

    // from
    // https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide
    // https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters
    private $data = [];

    public function __construct($UA=null, $cid=null, $uid=null)
    {
        $this->tid = $UA;
        $this->cid = $cid;
        $this->uid = $uid;
        $this->resetData();
    }

    public function eventTo($UA, $cid, $uid, $category, $action, $label = null, $value = null){
        $this->resetData();
        $this->data['tid'] = $UA;
        $this->data['cid'] = $UA;
        $this->data['uid'] = $UA;
        $this->data['t'] = "event";
        $this->data['ec'] = $category;
        $this->data['ea'] = $action;
        $this->data['el'] = $label;
        $this->data['ev'] = $value;
        $this->data['z'] = time()+rand(1,99);
        $this->buildCall();
    }

    public function event($category, $action, $label = null, $value = null)
    {
        $this->resetData();
        $this->data['t'] = "event";
        $this->data['ec'] = $category;
        $this->data['ea'] = $action;
        $this->data['el'] = $label;
        $this->data['ev'] = $value;
        $this->data['z'] = time()+rand(1,99);
        $this->buildCall();
    }


    public function exception($description, $fatal=false)
    {
        $this->resetData();
        $this->data['t'] = "exception";
        $this->data['exd'] = $description;
        $this->data['exf'] = $fatal;
        $this->buildCall();
    }

    private function resetData(){
        $this->data = [
            //general
            "v" => 1,                 // Required. Version.
            "tid" => $this->tid,    // Required. Tracking ID / Property ID.
            "cid" => $this->cid,            // Required. Anonymous Client ID. @todo should be a random UUID
            "uid" => $this->uid,            // Required. User Client ID.
            "t" => null,              // Required. Hit Type. ['pageview'|'event']
            "z" => null,              // Cache Buster

            //Hit
            "ni" => 1,               // Non-Interaction Hit
        ];
    }

    private function buildCall()
    {
        $params = [];
        foreach ($this->data as $key => $param) {
            if ($param && $param != "")
                $params[] = $key . '=' . urlencode($param);
        }

        $call = str_replace("?", '\?', str_replace("&", '\&', str_replace("=", '\=', 'curl https://www.google-analytics.com/collect?' . implode('&', $params)))) . " > /dev/null & ";
        $this->calls[] = $call;
    }

    public function send()
    {
        exec(implode($this->calls));
        $this->calls = [];
    }

}