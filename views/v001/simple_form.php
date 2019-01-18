<div id="{{form_id}}" style="width:800px;padding:10px 20px;top: 50px;" class="easyui-dialog" closed="true" buttons="jigou-dlg-buttons" data-options="modal:true">
	<div id="check-form"></div>
    <div id="check-btn-box"></div>
</div>
<script type="text/javascript">
	var box_config = globalData['check_box_config'];
    var value = {};
    var div_box = '';
    var data_length = box_config.length;
    for (var i = 0; i < data_length; i++) {
        if (data_length != (i+1)) {
            for (var j = 0; j < box_config[i].length; j++) {
                value = box_config[i][j];
                div_box += '<div class="'+ value['box_class'] +'">'
                div_box += nunjucks.renderString(
                    globalData['tpl']['lable_tpl'], 
                    {class:value['lable_class'], textbox: value['lable_textbox']}
                );
                if (value['input_tpl'] == 'input_select_tpl') {
                    if (!value['input_options']) {
                        value['input_options'] = globalData['tmp'][value['input_name']];
                    }
                }
                div_box += nunjucks.renderString(
                    globalData['tpl'][value['input_tpl']], 
                    {data:value}
                );
                //追加提示用span
                div_box += '<span class="fred"></span>';
                div_box += '</div>';
            }
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
    $('#check-form').append(div_box);
    $('#check-btn-box').append(btn_box);
</script>