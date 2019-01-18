<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>慕课七夕主题</title>
    <link rel='stylesheet' href='/public/css/style.css' />
    <link rel='stylesheet' href='/public/css/pageA.css' />
    <link rel='stylesheet' href='/public/css/pageB.css' />
    <link rel='stylesheet' href='/public/css/pageC.css' />
    <script src="http://libs.baidu.com/jquery/1.9.1/jquery.js"></script>
    <script type="text/javascript" src="/public/js/55ac9ea30001ace700000000.js"></script>
</head>
<body>
    <div id='content'>
        <ul class='content-wrap'>
            <!-- 第一副画面 -->
            <li></li>
            <!-- 第二副画面 -->
            <li></li>
            <!-- 第三副画面 -->
            <li>
                <!-- 背景图 -->
                <div class="c_background">
                    <div class="c_background_top"></div>
                    <div class="c_background_middle"></div>
                    <div class="c_background_botton"></div>
                </div>
                <!-- 小女孩 -->
                <div class="girl"></div>
                <div class="bridge-bottom">
                    <div class="water">
                        <div id="water1" class="water_1"></div>
                        <div id="water2" class="water_2"></div>
                        <div id="water3" class="water_3"></div>
                        <div id="water4" class="water_4"></div>
                    </div>
                </div>
                <!-- 星星 -->
                <ul class="stars">
                    <li class="stars1"></li>
                    <li class="stars2"></li>
                    <li class="stars3"></li>
                    <li class="stars4"></li>
                    <li class="stars5"></li>
                    <li class="stars6"></li>
                </ul>
            </li>
        </ul>
        <!-- 飘花 -->
        <div id="snowflake"></div>
    </div>
</body>
<script type="text/javascript">
$(function() {
     console.log("方法一dfjhhuhfhfhf");
    var snowflakeURl = [
        'http://img.mukewang.com/55adde120001d34e00410041.png',
        'http://img.mukewang.com/55adde2a0001a91d00410041.png',
        'http://img.mukewang.com/55adde5500013b2500400041.png',
        'http://img.mukewang.com/55adde62000161c100410041.png',
        'http://img.mukewang.com/55adde7f0001433000410041.png',
        'http://img.mukewang.com/55addee7000117b500400041.png'
    ]
    //飘雪花 //
    function snowflake() {
        // 雪花容器
        var $flakeContainer = $('#snowflake');
        // 随机六张图
        function getImagesName() {
            return snowflakeURl[[Math.floor(Math.random() * 6)]];
        }
        // 创建一个雪花元素
        function createSnowBox() {
            var url = getImagesName();
            return $('<div class="snowbox" />').css({
                'width': 41,
                'height': 41,
                'position': 'absolute',
                'backgroundSize': 'cover',
                'zIndex': 100000,
                'top': '-41px',
                'backgroundImage': 'url(' + url + ')'
            }).addClass('snowRoll');
        }
        // 开始飘花
        setInterval(function() {
            // 运动的轨迹
            var startPositionLeft = Math.random() * visualWidth - 100,
                startOpacity    = 1,
                endPositionTop  = visualHeight - 40,
                endPositionLeft = startPositionLeft - 100 + Math.random() * 500,
                duration        = visualHeight * 10 + Math.random() * 5000;
            // 随机透明度，不小于0.5
            var randomStart = Math.random();
            randomStart = randomStart < 0.5 ? startOpacity : randomStart;
            // 创建一个雪花
            var $flake = createSnowBox();
            // 设计起点位置
            $flake.css({
                left: startPositionLeft,
                opacity : randomStart
            });
            // 加入到容器
            $flakeContainer.append($flake);
            // 开始执行动画
            $flake.transition({
                top: endPositionTop,
                left: endPositionLeft,
                opacity: 0.7
            }, duration, 'ease-out', function() {
                $(this).remove() //结束后删除
            });
            
        }, 200);
    }
    //开始
    $(document).ready(function(){
         snowflake()
    })
})
</script>
<script type="text/javascript" src="/public/js/Swipe.js"></script>
<script type="text/javascript" src="/public/js/Qixi.js"></script>
</html>