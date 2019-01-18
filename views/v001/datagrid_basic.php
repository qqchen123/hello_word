<div region="center" data-options="border:false" style="padding: 0px 20px;">
    <table  id="{{datagrid_id}}" class="easyui-datagrid" style="width:100%;min-height:500px"
        data-options="
            url: '{{target}}',
            rownumbers: true,
            method: 'post',
            lines: true,
            fit: true,
            border: false,
            columns:globalData['listconfig'],
            pagination:true,
            onSortColumn: function (sort,order) {
                $('#{{datagrid_id}}').datagrid('reload', {
                    sort: sort,
                    order: order
            　　});
            },
            onLoadSuccess:function(data){
                showBtn();
            }
            ">
    </table>
</div>