<?php

namespace Tests\Ilovepdf\Tools;

use Ilovepdf\Tools\ServerAnalytics;

class ServerAnalitycsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testCallEvent(){
        $ga = new ServerAnalytics('UA-xxxxxxxx-1', '1');

        for($i=0; $i<5; $i++){
            $ga->event('server', 'test', 'test', $i);
        }

        $ga->send();
    }
    /**
     * @test
     */
    public function testCallExceptiont(){
        $ga = new ServerAnalytics('UA-xxxxxxxx-1', '1');

        for($i=0; $i<4; $i++){
            $ga->exception('error en bd');
        }

        $ga->send();
    }
}