<div id="submit-dlg" style="width:1200px;{{boxwidth}}max-height: 700px;padding:10px;top: 10px;" class="easyui-dialog" closed="true" buttons="submit-dlg-buttons" data-options="modal:true">
    <form id="submitForm" method="post" novalidate enctype="multipart/form-data"> 
        <div id="edit-box-content"></div>
        <div class="fitem dn">
            <label id="info_label">流程附加信息:</label>
            <input name="status_info" id="status_info" class="easyui-textbox" style="width:346px;height:140px;" validType="length[0,240]" data-options="multiline:true" novalidate="true">
        </div>
        <div id="submit-dlg-buttons" class="sub-btn" style="text-align: center;border: #ccc solid;border-width: 1px 0 0 0;margin-top: 10px;padding-top: 10px;">

        </div>
    </form>
</div>

<script type="text/javascript">
    var box_config = globalData['edit_box_config'];
    var value = {};
    var div_box = '';
    var data_length = box_config.length;
    var flag = 0;
    var border_flag = 1;
    if (undefined != globalData['web_config'] && undefined != globalData['web_config']['nav']) {
        flag = 1;
    }
    //导航
    if (flag) {
        for (var i = 0; i < globalData['web_config']['nav'].length; i++) {
            div_box += '<span class="span-nav color-c" id="'+ globalData['web_config']['nav'][i]['id'] +'">'+globalData['web_config']['nav'][i]['title']+'</span> | ';
        }
    }

    //元素
    for (var i = 0; i < box_config.length; i++) {
        if (data_length != (i+1)) {
            div_box += '<div style="margin-bottom:5px;border:0 #ccc solid;border-width:0 1px 1px 1px;"><div style="border:0 #ccc solid;border-width:1px 0 0px 0;margin-bottom:2px;">';
            if (flag) {
                div_box += globalData['web_config']['title'][i];
            }
            div_box += '</div> ';
            for (var j = 0; j < box_config[i].length; j++) {
                value = box_config[i][j];
                if (undefined != value['border_class']) {
                    console.log('border:' + value['border_class']);
                }
                if (undefined != value['border_class'] && '1' == value['border_class']) {
                    //需要加虚线
                    if (border_flag) {
                        div_box += '<div class="border-dotted">';
                    }
                }
                div_box += '<div class="' + value['box_class'] + '">'
                if (value['lable_textbox'].length > 5) {
                    //长度超长 分割换行处理
                    $str_lable_textbox = value['lable_textbox'].substring(0, Math.floor(value['lable_textbox'].length/2));
                    $str_lable_textbox += '<br/>';
                    $str_lable_textbox += value['lable_textbox'].substring(Math.floor(value['lable_textbox'].length/2), 10);
                    value['lable_textbox'] = $str_lable_textbox;
                }
                div_box += nunjucks.renderString(
                    globalData['tpl']['lable_tpl'], 
                    {class:value['lable_class'], textbox: value['lable_textbox']}
                );
                div_box += nunjucks.renderString(
                    globalData['tpl'][value['input_tpl']], 
                    {data:value}
                );
                div_box += '</div>';
                if (undefined != value['border_class'] && '1' == value['border_class']) {
                    if (border_flag) {
                        border_flag = 0;
                    } else {
                        div_box += '</div>';
                        border_flag = 1;
                    }
                }
            }
            div_box += '</div>';
        } else {
            //加载 按钮
            var btn_box = '';
            for (var j = 0; j < box_config[i].length; j++) {
                value = box_config[i][j];
                if (!value['btn_type']) {
                    value['btn_type'] = '';
                }
                btn_box += nunjucks.renderString(
                    globalData['tpl']['button'], 
                    {class:value['btn_class'], title: value['btn_title'], id:value['btn_id'], type:value['btn_type']}
                );
            }
        }
    }
    $('#edit-box-content').append(div_box);
    $('#submit-dlg-buttons').append(btn_box);
</script>