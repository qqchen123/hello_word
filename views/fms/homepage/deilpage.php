<?php //tpl("admin_applying") ?>
<style type="text/css">
   .infos {
      width:1400px;
      height:3000px;
      border: 2px #d2d2d2 solid;
   }
   #deilifo {
    display:inline-block;
    float:left;
   }
   .cared_info {
    width:1350px;
    margin-left:1.9%;
    margin-top:0.7%;

   }
   .but {
    width:143px;
    height:18px;
    font-size:12px;
    float:left;
    margin-top:1px;
    float:right;
    line-height:13px;
    border-radius:10px;
    border:1px #898989 solid;
    background: -webkit-linear-gradient(#efefe9,#77775e);  
    background: -o-linear-gradient(#efefe9,#77775e);   
    background: -moz-linear-gradient(#efefe9,#77775e);   
    background: -mos-linear-gradient(#efefe9,#77775e);   
    background: linear-gradient(#efefe9,#77775e);   
  }
 .evidence_pool {
   display:inline-block;
   width:130px;
   height:25px;
   border:1px #d2d2d2 solid;
   text-align:center;
   background:#f8f8f8;float:left;
 } 
 .div_btns {
    display:inline-block;
    border:1px #d2d2d2 solid; 
    width:71.1%;
    height:25px; 
    background:#f8f8f8;
    float:left;
 }
.divsss {
    width:152px;
    height:25px;
    border:1px #d2d2d2 solid;
    text-indent:1em;
    background:#f8f8f8;
}
.basis_info {
  width:130px;
  height:74px;
  border:1px black solid;
  display:inline-block;
  float:left;
  margin-left:2%;
  border: 1px #d2d2d2 solid;
  background:#f8f8f8;
}
.pool_evidence {
  border:1px #d2d2d2 solid;
  height:410px;
}
 #evidencepool {
   width:1350px;
   height:400px;
   border:1px #d2d2d2 solid;
   display: none;
 }
 .order_list {
   border:1px #d2d2d2 solid;
   height:250px;
 }
 .order_info {
   width:1350; 
   height:220px;
   background:#f8f8f8;
 }
.str_head {
 
   margin-top:0.7%;
   background:#f8f8f8;
 
}
.str_div3 {
    display:inline-block;
    float:left;
 }
.str_body {
   width:138px;
   height:25px;
   border:1px #d2d2d2 solid;
   text-indent:1em;
   background:#f8f8f8;
}
.str_btn {
   border:1px #d2d2d2 solid;
    width:658px;
    height:25px;
    background:#f8f8f8;
    display: inline-block;
    float:left;
}
.str_span {
   display:inline-block;
   width:30px;
   text-align:center;
}
.str_div4 {
     display: inline-block;
     width:250px;
     height: 137px;
     border:1px dashed #a0a0a0;
     background:#f8f8f8;
     border-radius:6px;
     margin-top:0.5%;
    margin-left: 2%;
}
.str_img1 {
    position:absolute;
    margin-top: 1%;
    margin-left:1%;
  
}
.str_img2 {
     position:absolute;
     margin-left:3.5%;
     margin-top:1%;
    
}
.pool_head {
   display:inline-block;
   margin-left:1.5%;
}
.pool_boby {
   width:134px;
   height:80px;
   border:1px #d2d2d2 text-indent:1em;
   background:#f8f8f8;
   margin-top:10%;
   margin-left:5%;
}
.pool_img {
   display:inline-block;
   float:left;
}
.pool_span1 {
   display:inline-block;
   padding-top:5%;
}
.pool_span2 {
    display:inline-block;
    line-height:8%;
    margin-left:30%;
    text-decoration:underline;
}
.pool_span4 {
   text-decoration: underline;
}
.bg_picture {
   display:inline-block;
   float:left;
   width:250px;
   height:173px;
   background:url(/assets/images/web/homedeail/中国电信1.png)no-repeat;
}
 .phone_no {
   display:inline-block;
   margin-top:3%;
   margin-left:4%;
 }

 .div_city {
   display:block;
   margin-left:5%;
 }
 .div_city2 {
   display:inline-block;
   width:50px;
 }
 .div_img {
   display:inline-block; 
   margin-top:21%;
   margin-left:2%;
 }
 .cared_pcj {
    border:1px #d2d2d2 solid;
    height:165px;
    background: #f8f8f8;
 }
 #dfew {
   border:1px #d2d2d2 solid;
   display: none;
 }
 .div_logoadd {
    background:url(/assets/images/web/homedeail/add.png)center 17px no-repeat,url(/assets/images/web/homedeail/LOGO.png)center 17px no-repeat;
 }
</style>
<div id="data-id" class="dn">{{id}}</div>

<div class="infos">
     <!-- 基本信息 -->
    <div class="homepagehead" style="margin-top:0.7%;">
        <div class="basis_info">
            <p style="text-align:center;margin-top:20px;">用户基础信息</p>
        </div>
        <div id="deilifo" ></div>
        <div style="clear:both;"></div>
    </div>
   <!-- 手机卡 -->
    <div id="mobile_card" class="cared_info"></div>
        
   <!-- 证据池 -->
    <div class="cared_info" class="pool_evidence">
         <div class="evidence_pool">证据池</div>
         <div class="evidence_pool">证据完成度</div>
         <div class="evidence_pool">66%</div>
         <div class="div_btns">
            <button class="but zjczhkasq">
              <span class="str_span ">展开</span>|<span>收起</span>
            </button>
         </div>

        <div style="clear:both;"></div>
        <div id="evidencepool"></div>
 
   </div> 

    <!-- 订单列表 -->
    <div class="cared_info" class="order_list">
         <div  class="evidence_pool">订单列表</div>
         <div  class="evidence_pool">订单数</div>
         <div  class="evidence_pool">5</div>
             <div class="div_btns">
                <button class="but zksqaniu">
                    <span class="str_span span_str">展开</span>|<span class="str_span1">收起</span>
                </button>
             </div>
         <div style="clear: both;"></div>
         <div class="order_info" id="dfew">
           <!--订单列表  -->
             
       </div>
    </div> 
</div>

 <!-- 首页头 -->
 <script type="text/javascript">
    if (undefined == globalData) {
        var globalData = [];
    }
    //data-id
    function aaa(){
        var id = $('#data-id').text();
        var aa = [];
        //ajax 获取数据
        $.ajax({ 
            type : "get", 
            url : AJAXBASEURL + 'Home/homepage?id=' + id, 
            success : function(data){ 
                data = JSON.parse(data);
                aa['basic'] = data['basic'];
                console.log(data);
                //基础信息加载
                basic_data(data['basic']);
                //卡信息加载
                card_data(data['card'], data['card_detail']);
            } 
        });
    }
    aaa();


    //基础信息加载
    function basic_data(data) {
        var str = '';
      
        for (var j = 0; j < data.length; j++) {//列
            str += '<div style="display:inline-block;float:left;">'
            for (var i = 0; i < data[j].length; i++) {//行
                str += '<div class="divsss">'+ data[j][i] + '</div>';
            }
            str += '</div>';
        }
        $("#deilifo").append(str);
    }

    //卡片信息
    function phonecrad(bb, detail,cc){
        var str ='<div class="str_head">'
            str+= '<div>';
            for (var j = 0; j < bb.length; j++) {//列
                str += '<div class="str_div3">'
                for (var i = 0; i < bb[j].length; i++) {//行
                    str += '<div class="str_body">'+bb[j][i]+'</div>';
                }
                str += '</div>'
            }
            str += '<div  class="str_btn"><button class="but sjkzhsq" data-aa="'+cc+'"><span class="str_span" >展开</span>|<span  ">收起</span></button></div></div>';
            str += '<div style="clear:both;"></div></div>';
            str +='<div style="display:none" class="cared_pcj '+cc+'" >'
            str += card_detail_data(detail, bb[0][0]);
             
            str += '<div class="str_div4 div_logoadd"></div>';
            str +='</div>'
         return str
    }

      var namecared=['dsfe','drtrg','kjie']; 
      function sdew(namecared){
             for (var j = 0; j < namecared.length; j++) {
                  $("#deilifo").on('click', ".zk_butn", function () {
                     console.log(12);
                    $('.'+namecared[j]).slideDown();
                    });
                 $(".sq_butn").on('click', function(){
                   $('.'+namecared[j]).slideUp();
                 });
                 console.log(2);
            }    
       }
       // sdew(namecared);

           

    //卡片详细内容
    function card_detail_data(data, type) {
        var str = '';
        var group = '';
        switch(type) {
            case '手机卡' : group = 'mobile_info';break;
            case '银行卡' : group = 'bank_info';break;
            case '机构管理' : group = 'inst_info';break;
        }
        if (data[group]) {
            for (var i = 0; i < data[group].length; i++) {
                console.log(data[group][i]);
                if (data[group][i] != null) {
                    str = '<div class="bg_picture">';
                    str += '<div class="phone_no">' + 123 + '</div><div class="div_city"><span class="div_city2">上海</span><span>上海市</span></div><div class="div_img"><img src="/assets/images/web/homedeail/是否存管.png"><img src="/assets/images/web/homedeail/猫池.png"><img src="/assets/images/web/homedeail/充值.png"><img src="/assets/images/web/homedeail/常用手机.png"></div>';
                    str += '</div>';
                }
            }
        }
        
        return str;
    }

    function card_data(data, detail) {
        for (var i = 0; i < data.length; i++) {
            $("#mobile_card").append(phonecrad(data[i], detail,namecared[i]));  
                      
           }

     }


</script>

<!--证据池  -->
<script type="text/javascript">
    function evidencepools(){
      var aa = [
           [
               ['用户姓名','/assets/images/web/homedeail/个人.png','1','2'],
               ['企业流水','/assets/images/web/homedeail/企业.png','1','3'],
               ['签发机关','','1','4'],
               ['12','','1','2']
           ],
           [
               ['户口本信息','/assets/images/web/homedeail/3人.png','1','2'],
               ['房产证','/assets/images/web/homedeail/房子.png','1','1'],
               ['签发机关','','1','2'],
               ['12','','1','2']
           ],
           [
               ['征信信息','/assets/images/web/homedeail/个人.png','1','2'],
               ['房产评估','/assets/images/web/homedeail/房子.png','1','1'],
               ['签发机关','','1','2'],
               ['12','','1','2']
           ],
           [
               ['婚姻证明','/assets/images/web/homedeail/个人.png','1','2'],
               ['省份证','/assets/images/web/homedeail/房子.png','1','1'],
               ['签发机关','','1','2'],
               ['12','','1','2']
           ],
           [
               ['个人流水','/assets/images/web/homedeail/个人.png','1','2'],
               ['详情照片','/assets/images/web/homedeail/房子.png','1','3'],
               ['签发机关','','1','2'],
               ['12','','1','2']
           ],
           [
               ['个人大数据','/assets/images/web/homedeail/个人.png','1','2'],
               ['省份证','','1','1'],
               ['签发机关','','1','1'],
               ['12','','1','2']
           ],
           [
               ['企业证件','/assets/images/web/homedeail/企业.png','1','2'],
               ['省份证','','1','3'],
               ['签发机关','','2','4'],
               ['12','','1','2']
           ],
           [
               ['企业征信','/assets/images/web/homedeail/企业.png','1','2'],
               ['省份证','','1','4'],
               ['签发机关','','1','3'],
               ['12','','1','2']
            ],
         ]
     
        var str = '';
        for (var j = 0; j < aa.length; j++) {//列
           str += '<div class="pool_head">'
             for (var i = 0; i < aa[j].length; i++) {//行
               str += '<div class="pool_boby"><img src="'+aa[j][i][1]+'" class="pool_img"><span class="pool_span1">'+aa[j][i][0]+'</span><br><span class="pool_span2">'+aa[j][i][2]+'</span><span class="pool_span4">/</span><span class="pool_span4">'+aa[j][i][3]+'</span></div>';
             }
            str += '</div>';
        }
        return str;
    }
    $("#evidencepool").append(evidencepools());

</script>

<script type="text/javascript">
  
  $(document).ready(function(){
      $(".zjczhkasq").click(function () {
              $("#evidencepool").slideToggle();
          });
      $(".zksqaniu").click(function () {
             $("#dfew").slideToggle();
         });
         //手机卡
     $("#mobile_card").on('click', ".sjkzhsq", function () {
            var name = $(this).attr('data-aa');
            $('.'+name).slideToggle();
        });
    });
  
</script>
