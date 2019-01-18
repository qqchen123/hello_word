KindEditor.plugin('product', function(K) {
    var self = this, name = 'product';
    var htmlContent = self.html();
    var mapList = $(htmlContent).find("area[class]");
    var mapItemList = [];
    var iframdocument = frames[0].document;
    var iframbody;

    iframbody = iframdocument.body;
    $(iframdocument).find('head').append("<link rel='stylesheet' href='/assets/css/promotion.css' />")
    //imgmap查询，设置好产品的area对应的class生成相应的id，这种直接忽略掉
    mapList.each(function(idx,ele){
        var val = $(ele).attr('class');
        if($(iframbody).find('#'+val).length==0){
            mapItemList.push('<option value="'+val+'">'+val+'</option>');
        }
    });

    $('body').delegate("#getProductName","keyup",function(){
        var cateid = $('#listcat option:selected').val();
        if(!cateid) {
            alert("未选择分类");
            return;
        }
        $.get(
            PAGE_VARS.SITE_URL+"ajax/getproduct?key="+this.value+"&cateid="+cateid,
            function(responseData){
                if(responseData.responseCode!==200){
                    alert(responseData.responseMsg);
                    return;
                }
                var html = ['<ul style="position: absolute;top:0px;left: 74px;">'];
                var product;
                for(i=0,len=responseData.responseMsg.length;i<len;i++){
                    product=responseData.responseMsg[i];
                    html.push('<li onclick="$(\'#getProductName\').val(\''+product.name+'\').data(\'productid\','+product.id+'),$(\'#productListContainer\').empty()">'+product.name+'</li>');
                }
                html.push("</ul>");
                $('#productListContainer').html(html.join(''));
            },'json'
        )
    });

    //区域选择
    $('body').delegate("#mapSelector","change",function () {
        $(iframbody).find('.apbox').each(function(){
            if($(this).html().length==0){
                $(this).remove();
            }
        });
        if($("#"+this.value).length){
            if($("#"+this.value).is(":hidden")){
                $("#"+this.value).show();
                return;
            }
        }
        $(iframbody).find('.apdiv').append("<div class='apbox' id='"+this.value+"' style='display: block'></div>");
    });
    /**
     * 1、选择area
     * 2、选择产品
     * 3、插入数据
     */
    self.clickToolbar(name, function() {
        var lang = self.lang(name + '.'),
            //area列表
            html = ['<div style="padding:10px 20px;">',
                '<div class="ke-dialog-row">',
                '<label for="remoteUrl" style="width:60px;">关联区域</label>',
                '<select id="mapSelector">',
                '<option value="">--请选择--</option>',
                mapItemList,
                '</select>',
                '</div>',
                '<div class="ke-dialog-row">',
                '<label for="remoteUrl" style="width:60px;">产品名称</label>',
                '<input id="getProductName" type="text" class="ke-input-text" name="url" value="" style="width:200px;">',
                '<input id="productinfo" type="hidden" class="ke-input-text" name="url" value="" style="width:200px;">',
                '<div id="productListContainer" style="width:200px;line-height:28px;height: 80px;z-index:989;overflow-y:auto;position: relative"></div>',
                '</div>',
                '</div>',
               ].join(''),
            dialog = self.createDialog({
                name : name,
                width : 450,
                title : self.lang(name),
                body : html,
                yesBtn : {
                    name : self.lang('yes'),
                    click : function(e) {
                        var productid = $('#getProductName').data('productid');
                        var mapSelector = $('#mapSelector option:selected').val();
                        if(!productid) {
                            alert("无有效的产品数据");
                            return;
                        }

                        if(!mapSelector){
                            alert("无效的关联区域");
                            return;
                        }
                        $.get(
                            PAGE_VARS.SITE_URL+"ajax/getproduct?productid="+productid,
                            function(responseData){
                                var html = [];
                                var product = responseData.responseMsg[0];
                                html.push("名称："+product.name+"<br>");
                                html.push("尺寸："+product.size+"<br>");
                                html.push("规格："+product.spec+"<br>");
                                html.push("价格："+product.price+"元/"+product.lifeunit+"周");
                                $(iframbody).find('#'+mapSelector).html(html.join('')).attr('style',"display:none");
                            },'json'
                        );

                        dialog.remove();
                        dialog = null;
                    }
                },
                noBtn:{
                    name:self.lang('no'),
                    click:function (e) {
                        $(iframbody).find('.apbox').each(function(){
                            if($(this).html().length==0){
                                $(this).remove();
                            }
                        });
                        dialog.remove();
                        dialog = null;
                    }
                }
            });
            // textarea = K('textarea', dialog.div);
            // textarea[0].focus();
    });
});
