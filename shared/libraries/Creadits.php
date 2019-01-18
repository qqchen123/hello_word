<?php

/**
 * Class Creadits
 * 失信行为获取
 */
class Creadits
{
    private $apiHost="http://qianzhan3.market.alicloudapi.com/";
    #private $apiHost="http://market.alicloudapi.com/";
    private $appCode="43563e327fb14df287723695e09555b0";
    /**
     * @var array
     * executor 被执行人查询
     * dishonest 失信查询
     * judge 判决书查询
     */
    private $apiPath=[
        'executor' =>"ExecutorVague",
        'dishonest'=>"DishonestVague",
        'judge'    =>"JudgeVague"
    ];
    private $postArgs = [];

    /**
     * 接口参数
     * @param $parmName 查询参数的名称
     * @param $paramVal 查询参数的值
     * @return array    code:0正常，1有错误发生；msg 错误信息
     */
    public function setParam($keyWors,$idCard='',$page=1){
        $validRule = [
            'cardNum' => function() use ($idCard){
                if ($idCard && !preg_match('/\d{8,17}[\d|X]/',$idCard)){
                    return ['code'=>1,'msg'=>'Invalid idNumber:'.$idCard];
                }
                return ['code'=>0,'msg'=>$idCard];
            },
            'input' => function() use ($keyWors){
                if(!trim($keyWors) || filter_var($keyWors,FILTER_SANITIZE_STRING)!=$keyWors){
                    return ['code'=>1,'msg'=>'Invalid keyWords:'.$keyWors];
                }
                return ['code'=>0,'msg'=>$keyWors];
            },
            'page' => function() use ($page){
                if (!filter_var($page,FILTER_VALIDATE_INT)){
                    return ['code'=>1,'msg'=>'Invalid page:'.$page];
                }
                return ['code'=>0,'msg'=>$page];
            }
        ];

        $valRes = [];
        foreach ($validRule as $_key=>$_item){
            $_itemCheckRet = $_item();
            if( $_itemCheckRet['code'] == 1 )
                $valRes[] = $_itemCheckRet['msg'];
            else
                $this->postArgs[$_key] = $_itemCheckRet['msg'];
        }

        if($valRes !== true){
            return [
                'code'=>1,
                'msg'=>$valRes
            ];
        }
        return [
            'code'=>0,
            'msg'=>''
        ];
    }

    /**
     * 查询信息
     * @param $requestType 见上表
     * @return array   code:0正常，1有错误发生；msg 错误信息
     */
    public function queryInfo($requestType,$constomizedHeader=array())
    {
        if (!in_array($requestType,array_keys($this->apiPath))){
            return [
                'code'=>1,
                'msg'=>'invalide query type'
            ];
        }

        if($requestType == 'judge' && isset($this->postArgs['cardNum']))
            unset($this->postArgs['cardNum']);

        if(sizeof(array_intersect(array_keys($this->postArgs),['input','page']))!=2){
            return [
                'code'=>1,
                'msg' => 'Both input and page are needed!'
            ];
        }
        $url = $this->apiHost . $this->apiPath[$requestType] . '?' . http_build_query($this->postArgs);

        $queryResult = self::get_by_curl($url);
        return $queryResult;
    }

    /**
     * 执行查询
     * @param $url
     * @param array $constomizedHeader
     * @return array
     */
    private function get_by_curl($url,$constomizedHeader=array()){
        $authHeader = 'Authorization:APPCODE '.$this->appCode;
        array_push(
            $constomizedHeader,
            $authHeader
        );


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $constomizedHeader);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$this->apiHost, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        curl_close($curl);

        $body = substr($response, $headerSize);

        if($httpCode != '200'){
            return [
                'code'=>1,
                'msg'=>'aliApi returns http code:'.$httpCode
            ];
        }

        $respData = json_decode($body, JSON_UNESCAPED_UNICODE);
        return [
            'code'=>0,
            'msg' =>'Query Success',
            'data'=>[
                'total'=>$respData['totalCount'],
                'list' =>$respData['result']
            ]
        ];
    }

}
