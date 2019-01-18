<style type="text/css">
    .yespage {
        width: 40px;
        height: 20px;
        border-radius:10px;
       background: -webkit-linear-gradient(#ffffff,#0d52b1);  
       background: -o-linear-gradient(#ffffff,#0d52b1);  
       background: -moz-linear-gradient(#ffffff,#0d52b1); 
       background: -mos-linear-gradient(#ffffff,#0d52b1); 
       background: linear-gradient(#ffffff,#0d52b1); 
      border-color: #d2d0d0;

    }
    img {
        margin-left: 10px;
    }

    .yespage:active{
       background: -webkit-linear-gradient(#dac4fa,#074190);  
       background: -o-linear-gradient(#dac4fa,#074190);
       background: -moz-linear-gradient(#dac4fa,#074190);
       background: -mos-linear-gradient(#dac4fa,#074190);
       background: linear-gradient(#dac4fa,#074190);
    }

    .a1 {
            background: -webkit-linear-gradient(#dac4fa,#074190);  
            background: -o-linear-gradient(#dac4fa,#074190);
            background: -moz-linear-gradient(#dac4fa,#074190);
            background: -mos-linear-gradient(#dac4fa,#074190);
            background: linear-gradient(#dac4fa,#074190);
    }

</style>
<div class="yespage" id="img1">
      <img src="/assets/images/web/head/no.png" > 
</div>
<div class="redyes"> 
      <img src="/assets/images/web/head/no1.png" > 
</div>

<script type="text/javascript">
    $('#img1').click(function(){
        console.log(1);
        var class_name = $('#img1').attr('class');
        console.log(class_name);
        if (-1 != class_name.indexOf('a1')) {
            $('#img1').removeClass('a1');
            $('#img1 img').attr('src', "/assets/images/web/head/no.png");
        } else {
            $('#img1 img').attr('src', "/assets/images/web/head/no1.png");
            $('#img1').addClass('a1');
        }
    });
</script>