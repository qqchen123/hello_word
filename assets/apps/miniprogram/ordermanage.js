$(function(){
	// $('#able_start').datebox({
 //        required:true,
 //        width:100,
 //    });

	// $('#able_end').datebox({
 //        required:true,
 //        width:100,
 //    });
    
	$('#recorddate').datebox({
        required:true,
        width:100,
    });

	$('#search-btn').click(function(){
		$('#condition').val($('#condition-input').val());
		submit();
	});

	$('#condition-input').bind('keyup', function(event) {
        if (event.keyCode == "13") {
            //回车执行查询
            $('#condition').val($('#condition-input').val());
			submit();
        }
    });

	//类型
	$('.type-btn-box').on('click', '.type-btn', function(){
		// $('#type').val($(this).attr('data-type'));
		$('#type').val($(this).attr('data-type'));
		$('.type-btn-box').children('div').each(function(){
			$(this).removeClass('type-btn-on-select');
		});
		console.log(1);
		$(this).addClass('type-btn-on-select');
		submit();
	});

/*表单类型显示*/
	//具体页面按钮 显示什么需要配合产证类型来显示
	$('#housepagebox').on('click', 'input[name="ishousenewold"]', function(){
		var pagetype = $(this).attr('id');
		var pagenum = $('#housepage_box span .page-btn-onselect').attr('data-page');
		pagetype = pagetype.substring(0, 3);
		change_form(pagetype, pagenum);
	});

	$('#housepage_box').on('click', '.page-btn', function(){
		var pagetype = $('input[name="ishousenewold"]:checked').attr('id');
		var pagenum = $(this).attr('data-page');
		pagetype = pagetype.substring(0, 3);
		change_form(pagetype, pagenum);
	});

	function change_form(pagetype, pagenum) {
		$('#housepage_box span').children('.page-btn').each(function(){
			$(this).removeClass('page-btn-onselect');
			if ($(this).attr('data-page') == pagenum) {
				$(this).addClass('page-btn-onselect');
			}
		});
		$('#houseform').children('form').each(function(){
			if (-1 != $(this).attr('id').indexOf(pagenum)) {
				if (pagenum < 3 && (-1 != $(this).attr('id').indexOf(pagetype)) || pagenum > 2) {
					$(this).removeClass('dn');
				} else {
					$(this).addClass('dn');
				}
			} else {
				$(this).addClass('dn');
			}
		});
	}

	//身份证正反面切换
	$('.idnumberpagetype').click(function(){
		var type = $(this).val();
		var check_id = '';
		if (-1 != $('#idnumber'+type+'form').attr('dn')) {
			//获取当前显示的表单id
			$('#idnumberform').children('form').each(function(){
				if (-1 != $(this).attr('dn')) {
					check_id = $(this).attr('id');
				}
			});
			$('#idnumberform').children('form').each(function(){
				$(this).addClass('dn');
			});
			$('#idnumber'+type+'form').removeClass('dn');
			var obj_map = {
				'name':'name',
				'idnumber':'idnumber',
				'qfjg':'qfjg',
				'able_start':'able_start',
				'able_end':'able_end',
				'birth_area':'birth_area',
				'sex':'sex',
				'birth':'birth'
			};
			if (match_diff(beforeeditdata.userinfo, check_id, obj_map)) {
				//点击保存
				console.log('保存');
				$('.edit-save-btn').click();
			} else {
				console.log('不用保存');
			}
		}
	});
/*表单类型显示*/

/*匹配不同*/
	function match_diff(data, id, obj_map, fn = ''){
		var form_data = $('#'+id).serialize();
		var formarray = serialize_to_obj(form_data);
		var flag = 0;
		for (var i in obj_map) {
			if (data.hasOwnProperty(i) && formarray.hasOwnProperty(i)) {
				console.log(i);
				console.log(data[i]);
				console.log(formarray[i]);
				console.log('-------');
				if ((data[i] || formarray[i]) && data[i] != formarray[i]) {
					flag = 1;
					break;
				}
			} else {
				console.log('fn');
				fn;
			}
		}
		return flag;
	}
/*匹配不同*/

    //列表加载
    $('#list-box').datagrid({
        url:'stay',
        width:'96%',
        rownumbers: true,
        pagination: true,
        queryParams: {
            condition:$('#condition').val(),//可空
			type:$('#type').val(),//必须
			page:$('#page').val(),//必须
			size:$('#size').val()//必须
        },
        loadFilter: function(data) {
        	var res = data;
        	console.log(res);
        	if (res.code != 0) {
				console.log(res.msg);
				return;
			}
			res = res.data;
			return {'total': res.info.total, 'rows':res.list};
		},
        columns:[[
            {field: 'bd_id',title:'订单编号',align:'center',halign:'center',sortable:true},
            {field: 'c_time',title:'申请时间',align:'center',halign:'center',sortable:true},
            {field: 'user_name',title:'申贷人',width:'8%',align:'center',halign:'center'},
            {field: 'get_money',title:'申贷金额(万)',width:'8%',align:'center',halign:'center'},
            {field: 'diYaZongJia',title:'系统评估金额(万)',width:'8%',align:'center',halign:'center'},
            {field: 'status',title:'状态',width:'8%',align:'center',halign:'center'},
            {field: 'op1',title:'操作',align:'center',halign:'center',width:'160px',
                formatter:function (value,row) {
                    var html = '';
                    html += '<span class="viewbtn" onclick="open_dialog(\'view\', '+row.bd_id+')">查看</span>';
                    html += '<span class="editbtn" onclick="open_dialog(\'edit\', '+row.bd_id+')">处理</span>';
                    return html;
                }
            },
        ]]
    });

    //查询请求 机制 所有参数变更传递到 指定input 后调用此方法 统一去请求
	function submit(){
		$('#list-box').datagrid('load',{
			condition:$('#condition').val(),//可空
			type:$('#type').val(),//必须
			page:$('#page').val(),//必须
			size:$('#size').val()//必须
		});
	}

	//图片选择类 动作
	//图片选择
    $('.pictypeselect').click(function(){
    	var type = $(this).val();
    	if (picontype != type) {
    		//切换 图片列表 切换 填表内容显示与隐藏
    		picontype = type;
    		console.log('切换');
    		$('#bigpic').attr('src', '');
    		//图片列表切换
    		$('.imgtype').each(function(){
    			$(this).addClass('dn');
    		});
    		$('#imgtype'+type).removeClass('dn');
    		//选中第一个图片
    		$('#imgtype'+type).children('img:eq(0)').click();

    		//切换表格
    		if (2 == type) {
    			$('#housepagebox').removeClass('dn');
    			$('#idnumberpagebox').addClass('dn');
    		} else {
    			$('#housepagebox').addClass('dn');
    			$('#idnumberpagebox').removeClass('dn');
    			$('.idnumberpagetype:eq(1)').click();
    		}
    	}
    });

    //向左换一张图 按钮
    $('.pic-select-left').click(function(){
    	console.log('向左');
    	$('.imgselectbox').each(function(){
    		if (-1 != $(this).attr('class').indexOf('imgonselect')) {
    			//获取位置信息
    			var id = $(this).attr('id');
    			if (0 == $(this).index()) {
    				return false;
    			}
    			$(this).prev('.imgselectbox').removeClass('dn');
    			$(this).next('.imgselectbox').next('.imgselectbox').next('.imgselectbox').addClass('dn');
    			$(this).prev('.imgselectbox').click();
    			return false
    		}
    	});
    });

    //向右换一张图 按钮
    $('.pic-select-right').click(function(){
    	console.log('向右');
    	$('.imgselectbox').each(function(){
    		if (-1 != $(this).attr('class').indexOf('imgonselect')) {
    			//获取位置信息
    			var id = $(this).attr('id');
    			if ($(this).parent().children().length == $(this).index()+1) {
    				return false;
    			}
    			$(this).next('.imgselectbox').removeClass('dn');
    			$(this).prev('.imgselectbox').prev('.imgselectbox').prev('.imgselectbox').addClass('dn');
    			$(this).next('.imgselectbox').click();
    			return false
    		}
    	});
    });

    //图片双击放大
    $("#bigpic").dblclick(function(){
    	$('#bigpicbox').html("<img src="+$(this).attr('src')+">");
    	$('#bigpicbox').dialog('open');
    });

    //图片双击放大
    $("#part4").on('dblclick', 'img', function(){
    	$('#bigpicbox').html("<img src="+$(this).attr('src')+">");
    	$('#bigpicbox').dialog('open');
    });

	$('.view-type-title').click(function(){
		console.log(1);
		var cnt = 0;
		$(this).parent().children('div').each(function(){
			if (0 != cnt) {
				
				$(this).addClass('dn');
			}
		});
	})

   	$('.type-btn-box').children('div:eq(0)').click();
});