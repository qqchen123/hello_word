<?php
include_once SHAREPATH . "libraries/simple_html_dom.php";
class Domparse
{
    private $htmlDom;
    public function initDom($domSrc)
    {
        if(filter_var($domSrc,FILTER_VALIDATE_URL)){
            $this->htmlDom = file_get_html($domSrc);
        }else{
            $this->htmlDom = str_get_html($domSrc);
        }
    }

    public function __call($name, $arguments)
    {
        if(method_exists($this->htmlDom,$name)){
            return call_user_func_array(array($this->htmlDom,$name),$arguments);
        }
        return array();
    }
}