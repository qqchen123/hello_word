    //策略 开始------------------------------------- 
        var test_sample={};

        //查询按钮
        $('#likeBtn').on('click',function () {
            var like = $('#like ').val();
            $('#tt').datagrid('load',{like:like});
        });

        //策略页面添加结果信息input
        function addReturn(){
            $('#return_div').append($('#add_return').html());
            $('#return_div div:last').addClass('add_return');
            $('#return_div div:last input').addClass('easyui-textbox').textbox();/*.textbox({
                onChange:function(newValue,oldValue){
                    changeSample(newValue,oldValue,true);
                }
            });*/
        }

        //策略改变展示策略
        function changeRes(type='decision'){
            console.log('changeRes');
            $('#res #name,#res #res_action,#res #res_score,#res #res_info').empty();
            var o = {};
            $.each($("#ff").serializeArray(),function(k,v) {
                if (o[v.name] !== undefined) {
                    if (!o[v.name].push) o[v.name] = [o[v.name]];
                    o[v.name].push(v.value || '');
                }else{
                    o[v.name] = v.value || '';
                }
            });

            //派生值事件
            $('.derive').parent().find('a').click();
            evalDeriveFun(o,type);
        }
    //策略 结束------------------------------------- 

    //策略树 开始------------------------------------- 

        //获取单条策略 并添加策略值定义
        // var decisionData;
        // function changeDecision(newValue,oldValue){
        //     console.log('changeDecision');
        //     var data = decisionData.concat();
        //     $('#return_div').html('');
        //     $.each(data,function(k,v){
        //         if(v && v.id==newValue){
        //             data.splice(k,1); 
        //         }else{
        //             data[k] = $.extend(v,{group:'策略'});
        //         }
        //     });
        //     data.unshift(
        //         {group:'动作',id:'exit',text:'立即全部退出'},
        //         {group:'动作',id:'ret_info',text:'输出提示信息'},
        //         {group:'动作',id:'ret_false',text:'❌错误并退出当前分支'},
        //         {group:'动作',id:'ret_true',text:'✅当前分支正确'},
        //     );
        //     $.getJSON('get_decision',{id:newValue},function(row){
        //         var arr = JSON.parse(row.return);
        //         $.each(arr,function(k,v){
        //             $('#return_div').append($('#add_return').html());
        //             $('#return_div div:last').addClass('add_return');
        //             $("#return_div div:last .return_text").text(k+'('+v+')');
        //             $("#return_div div:last .return_action").attr('name','parent_return['+k+']');
                    
        //             $('#return_div div:last input').combobox({
        //                 valueField: 'id',
        //                 textField: 'text',
        //                 panelHeight:'auto',
        //                 lines:true,
        //                 panelHeight:400,
        //                 groupField:'group',
        //                 groupFormatter:function(group){
        //                     switch (group){
        //                         case '策略':
        //                             var str = '<span>'+group+'--------------------------</span>';
        //                             break;
        //                         case '动作':
        //                             var str = '<span style="color:red">'+group+'--------------------------</span>';
        //                             break;
        //                     }
        //                     return str;
        //                 },
        //                 onSelect:function(obj){
        //                     var div = $(this).parent();
        //                     div.find('.return_action_info').parent().remove();
        //                     if(obj.group!='动作') return false;
        //                     var name = 'parent_return_info['+k+']';
        //                     var add = $('#add_return_info').html();
        //                     div.append(add).find('.return_action_info').attr('name',name).textbox();
        //                 },
        //                 data:data,
        //             });
        //         });
        //     });
        // }

        //动作下拉框数据
        var actionData;
        var actionData2;

        $.getJSON('get_parent_return_actions',{},function(data){
            actionData = data[0];
            actionData2 = data[1];
        });

        //获取所有策略名称和id 并过滤策略树选取的策略 增加动作选项
        var decisionData = [];

        //父项变后获取父项详情和儿子 并组合父结果定义
        function changeDecisionTree(new_id,old_id){
            if(new_id){
                var id = new_id;
            }else{
                return false;
            }

            var o = $(this).combotree('tree').tree('find',id);
            //还原页面
            $('#return_div').html('');
            //当前策略树选取策略id
            var decision_id = o.decision_id;
            //当前策略树id
            var tree_id = o.id;

           
            $.getJSON('select_decision',{},function(data){
                $.each(data,function(k,v){
                    if(v && v.id==decision_id)
                        data.splice(k,1); 
                });
                data.unshift({id:"0",text:"无"});
                decisionData = data;
            });

            //获取当前选取策略树策略详情
            $.getJSON('get_one_group_by_treeid',{tree_id:tree_id},function(data){
                var decision = {};//父项策略
                var children = {};//子项策略
                if(tree_id!=0){
                    $.each(data,function(k,v){
                        if(v.tree_id==tree_id){
                            decision = v;
                        }else{
                            children[v.parent_return] = v;
                        }
                    });
                    decision.return = JSON.parse(decision.return);
                    $.each(children,function(k,v){
                        if(decision.return[k]==null){
                           decision.return[k] = '⚠️超出父项策略结果定义.';
                        } 
                    })
                }else{
                    decision.return = {};
                    var max_root_childrern = 0;
                    $.each(data,function(k,v){
                        children[v.parent_return] = v;
                        decision.return[v.parent_return] = v.parent_return;
                        max_root_childrern++;
                    });
                    if(is_add_root)
                        decision.return[max_root_childrern] = max_root_childrern;
                    // console.log(decision.return);
                    //伪造根节点数据
                    // decision.return = JSON.stringify(decision.return);
                }

                $('#ff #fun').textbox('setValue',decision.fun);
                $('#ff #name').val(decision.name);
                if(decision['return']) {
                    // var arr = JSON.parse(decision.return);
                    $.each(decision.return ,function(k,v){
                        $('#return_div').append($('#add_return').html());
                        $("#return_div .add_return:last .return_text").html('<label>结果值：'+k+'</label><span>结果信息：'+v+'</span>');
                        $("#return_div .add_return:last .decision_id").attr('name','return['+k+'][decision_id]');
                        $("#return_div .add_return:last .parent_return_action").attr('name','return['+k+'][parent_return_action]');
                        $("#return_div .add_return:last .parent_return_info").attr('name','return['+k+'][parent_return_info]');
                        
                        $('#return_div .add_return:last .decision_id').combobox({
                            valueField: 'id',
                            textField: 'text',
                            panelHeight:'auto',
                            lines:true,
                            panelHeight:400,
                            // groupField:'group',
                            // groupFormatter:function(group){
                            //     switch (group){
                            //         case '策略':
                            //             var str = '<span>'+group+'--------------------------</span>';
                            //             break;
                            //         case '动作':
                            //             var str = '<span style="color:red">'+group+'--------------------------</span>';
                            //             break;
                            //     }
                            //     return str;
                            // },
                            // onSelect:function(obj){
                            //     var div = $(this).parent();
                            //     div.find('.return_action_info').parent().remove();
                            //     if(obj.group!='动作') return false;
                            //     div.append($('#add_return_info').html()).find('.return_action_info').attr('name','parent_return_info['+k+']').textbox();
                            // },
                            data:decisionData,
                            value:(children[k])?children[k]['decision_id']:null,
                        });
                        $('#return_div .add_return:last .parent_return_action').combobox({
                            valueField: 'id',
                            textField: 'text',
                            panelHeight:'auto',
                            lines:true,
                            panelHeight:400,
                            data:actionData,
                            value:(children[k])?children[k]['parent_return_action']:null,
                        });
                        $('#return_div .add_return:last .parent_return_info').textbox({
                            value:(children[k])?children[k]['parent_return_info']:null,
                        });
                    });
                }
            });
        }

        //运行策略树
        function eval_decision_tree(user_id){
            $.getJSON('eval_decision_tree',{user_id:19},function(data){

            });
        }
    //策略树 结束------------------------------------- 

        //js公式测试 改为后端测试
        function evalFun(fun){
            var sample;
            var re = /{{(\d*)}}/g;
            var fun2 = fun;
            var funSample = true;
            var funBool;
            while(sample = re.exec(fun)) {
                // console.log(sample);
                if(sample==null) break;
                sample_val = $('#sample_'+sample[1]).val();
                if(sample_val!=null && sample_val!==''){
                    fun2 = fun2.replace(sample[0],sample_val);
                }else{
                    funSample = false;
                }
            }
            // console.log(funSample);
            if(funSample){
                funBool = eval(fun2);
                if(funBool){
                    var myClass = 'icon-ok';
                }else{
                    var myClass = 'icon-cancel';
                }
            }else{
                var myClass = 'icon-edit';
                funBool = null;
                fun2 += ' ⚠️ 请填写完整公式相关项的测试数据';
            }

            var a = '<span style="display:inline-block;width: 16px;background-size:100%;" class="'+myClass+'">&nbsp;</span><span>'+fun2+'</span>';
            $('#res_fun').html(a);
            $.parser.parse($('#res'));
            // console.log(funBool);
            return funBool;
        }