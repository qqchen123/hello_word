<?php

if (!function_exists('tpl')) {
    /**
     * 模版搜索调用方法
     * 根据传入的文件名称，从调用的文件上级开始逐层往下查找模版文件的位置
     * @author Ding
     * @param string $viewFile 加载的模版文件
     * @param string $tplfolder 放置模版的文件夹
     */
    function tpl($viewFile, $vars = array() , $tplfolder = 'template')
    {
        $fileInfo = pathinfo($viewFile);//文件分析
        $viewpath = realpath(VIEWPATH) . DS . $tplfolder.DS;//路径
        if (isset($fileInfo['extension'])) {
            $ext = '.' . $fileInfo['extension'];
        } else {
            $ext = '.php';
        }

        if (!is_dir($viewpath)) {
            show_error('模版目录不存在！', 200, '错误');
        }

        $viewFile = $viewpath . $viewFile . $ext;
        if (!file_exists($viewFile)) {
            show_error('模版不存在！', 404, '错误');
        }
        //加载模版文件
        $CI = &get_instance();
        $CI->load->view(substr($viewFile, strpos($viewFile, 'views') + 6),$vars);
    }
}

/**
 * 控制台页面左侧导航栏生成
 */
if(!function_exists('menuShow')){
    function menuShow($menu,$selectedItem='',$sub=false) {
        if(!is_array($menu)) return;
        if($sub){
            printf('<ul class="submenu" style="overflow:auto; max-height:400px;overflow-x:hidden;">');
        }
        foreach($menu as $_menuItem){
            if(!$_menuItem['show']) continue;
            $hasChild = is_array($_menuItem['children'])&&$_menuItem['children'];
            $linktar = $_menuItem['target'] ? $_menuItem['target'] : 'home/error';
            printf('<li class="%s">',$selectedItem ==$_menuItem['text'] ? 'active' : '');
//            printf('<a href="%s" class="%s">',site_url($linktar),$hasChild?'dropdown-toggle':'');
            printf('<a _href="%s" class="%s" style="cursor: pointer">',$linktar,$hasChild?'dropdown-toggle':'ace_tabs');
            printf('<i class="%s"></i>',$_menuItem['icon']);
            printf('<span class="menu-text"> %s </span>',$_menuItem['text']);
            if($hasChild){
                printf('<b class="arrow icon-angle-down"></b>');
            }
            printf('</a>');
            if($hasChild){
                menuShow($_menuItem['children'],'',true);
            }
            printf('</li>');
        }
        if($sub){
            printf('</ul>');
        }
    };
}

/**
 * dataTable
 */
if(!function_exists('tableHeadRender')){
    function tableHeadRender(array $headers,$trAttrs='',$tdAttrs=''){
        printf('<thead><tr %s>'.PHP_EOL,strip_tags($trAttrs));
        if($headers){
            foreach ($headers as $_key=>$hItem){
                printf('<th _key="%s" %s>%s</th>'.PHP_EOL,strip_tags($_key),strip_tags($tdAttrs),strip_tags($hItem));
            }
        }else{
            printf('<th>未指定表头字段</th>'.PHP_EOL);
        }
        printf('</tr></thead>'.PHP_EOL);
    }
}

/**
 * dataTable
 */
if(!function_exists('tableBodyRender')){
    function tableBodyRender(array $body,$trAttrs='',$tdAttrs=''){
        $CI = &get_instance();
        $primaryKey = $CI->getPrimaryKey();
        printf('<tbody><tr %s>'.PHP_EOL,strip_tags($trAttrs));
        if($body){
            foreach ($body as $_key=>$hItem){
                printf('<td _key="%s" %s>%s</td>'.PHP_EOL,strip_tags($_key),strip_tags($tdAttrs),strip_tags($hItem));
            }
        }else{
            printf('<td colspan="%d" class="text-center">未获取到数据</td>'.PHP_EOL,$CI->getHeaderLength());
        }
        printf('</tr></tbody>'.PHP_EOL);
    }
}

/**
 * 格式化操作连接
 * @param $recid
 * @param array $btns
 * @param array $extInfo
 * @return string
 */
function actLink($recid,array $extInfo=array(),$showdelete=true)
{
    $dinfo = json_encode($extInfo);
    $class = $showdelete ? "":"hidden";
    return <<<EOD
    <div class="btn-group" data-info='$dinfo'>
        <button class="btn btn-xs btn-info editAction" _tagId="$recid">
            <i class="icon-edit bigger-120"></i>
        </button>

        <button class="btn btn-xs btn-danger deleteAction $class" _tagId="$recid">
            <i class="icon-trash bigger-120"></i>
        </button>
    </div>
EOD;
;
}

/**
 * 分页
 * @param $uriPath
 * @param $currentPage
 * @param $totalPage
 */
function pagenation($uriPath,$currentPage,$totalPage)
{
    if($totalPage <=1) return;
    $start = $currentPage - 5 < 0 ? 1 : $currentPage - 5;
    $end = $currentPage + 5 > $totalPage ? $totalPage : $currentPage + 5;
    printf('<div class="center"><ul class="pagination">');
    printf('<li class="%s"><a href="%s"><i class="icon-double-angle-left"></i></a></li>',$start == $currentPage ? 'disabled' : '',$start == $currentPage ? 'javascript:void(0)' : site_url($uriPath.'/1'));
    for ($i=$start;$i<=$end;$i++)
        printf('<li class="%s"><a href="%s">%s</a></li>',$i == $currentPage ? 'active' : '',$i == $currentPage ? 'javascript:void(0)':site_url($uriPath.'/'.$i),$i,$currentPage);
    printf('<li class="%s"><a href="%s"><i class="icon-double-angle-right"></i></a></li>',$end == $currentPage ? 'disabled' : '',$end == $currentPage?'javascript:void(0)':site_url($uriPath.'/'.$end));
    printf('</ul></div>');
}

/**
 * 过滤空格
 * @param $title
 * @return string
 */
function trimTitle($title)
{
    return htmlentities(strip_tags($title));
}

/**
 * 保存模板
 * @param $tplString
 * @param $filename
 */
function saveTpl($tplString,$filename)
{
    if(strlen(trim($tplString))==0) return;
    $DS = DIRECTORY_SEPARATOR;
    $viewpath = realpath(APPPATH) . $DS . "views" . $DS . 'tpl' . $DS . 'prods' .$DS;//路径
    file_put_contents($viewpath . $filename.'.php',$tplString);
}

function gtu($string){
    echo mb_convert_encoding($string,'utf8','gbk');
}