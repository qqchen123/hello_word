<?php

class Creadits
{
    private $apiUrl="http://qianzhan3.market.alicloudapi.com/";
    private $apiPath=[
        'executor' =>"ExecutorVague",
        'dishonest'=>"DishonestVague",
        'judge'    =>"JudgeVague"
    ];
    private $postArgs = [];

    public function __set($parmName,$paramVal){
        if (!in_array($parmName,['cardNum','input','page'])){
            return ['error'=>true,'msg'=>'invalid pram:'.$parmName];
        }
        $this->set();
        return $this;
    }

    public function get($requestType)
    {
        if (!in_array())
    }

}

$c = new Creadits();
$c->sdf="sdf";->get();