<?php
class codereg
{
    private $imgHandler = null;
    private $imgString = "";
    private $charWidth = 25;
    private $imgWidth = 0;
    private $imgHeight= 0;
    public function setImgString($imgString)
    {
        $this->imgString = $imgString;
    }

    public function recognize($fileName="")
    {
        $this->getImgSize();
        $this->binaryReading($fileName);
    }

    protected function binaryReading($fileName="")
    {
        $gray = array_fill(0, $this->imgHeight,
            array_fill(0, $this->imgWidth, 0)
        );

        // 转为灰阶图像
        foreach($gray as $y => &$row){
            foreach($row as $x => &$Y){
                $rgb = imagecolorat($this->imgHandler, $x, $y);
                // 根据颜色求亮度
                $B = $rgb & 255;
                $G = ($rgb >> 8) & 255;
                $R = ($rgb >> 16) & 255;
                $Y = ($R * 19595 + $G * 38469 + $B * 7472) >> 16;
            }
        }
        unset($row, $Y);

        // 自动求域值
        $back = 127;
        do{
            $crux = $back;
            $s = $b = $l = $I = 0;
            foreach($gray as $row){
                foreach($row as $Y){
                    if($Y < $crux){
                        $s += $Y;
                        $l++;
                    }else{
                        $b += $Y;
                        $I++;
                    }
                }
            }
            $s = $l ? floor($s / $l) : 0;
            $b = $I ? floor($b / $I) : 0;
            $back = ($s + $b) >> 1;
        }while($crux != $back);

        // 二值化
        $bin = $gray;
        foreach($bin as &$row){
            foreach($row as &$Y){
                $Y = $Y < $crux ? 0 : 1;
            }
        }

        self::filter($bin,1);

        $img = imagecreate($this->imgWidth, $this->imgHeight);
        $rgb = array(
            imagecolorallocate($img, 0, 0, 0),
            imagecolorallocate($img, 255, 255, 255),
        );

        $x = $y = 0;

        foreach($bin as $row){
            do{
                imagesetpixel($img, $x, $y, $rgb[$row[$x]]);
            }while(isset($row[++$x]));
            $x = 0;
            $y++;
        }

        //header("Content-Type: image/jpg");
        imagejpeg($img,'/usr/local/nginx/html/assets/verify/'.($fileName=="" ? time() : $fileName).'.jpg');
    }

    protected function filter(&$bin,$repeat=1)
    {
        for($i=0;$i<$repeat;$i++)
            foreach($bin as $_hIdx=>&$row){
                foreach($row as $_wIdx=>&$Y){
                    if($Y==1) continue;
                    $pixCount = 0;
                    //右侧
                    if($_wIdx < $this->imgWidth-1 && $bin[$_hIdx][$_wIdx+1]==0) $pixCount++;
                    //下面
                    if($_hIdx < $this->imgHeight-1 && $bin[$_hIdx+1][$_wIdx]==0) $pixCount++;
                    //上面
                    if($_hIdx > 1 && $bin[$_hIdx-1][$_wIdx]==0) $pixCount++;
                    //右上
                    if($_hIdx > 1 && $_wIdx < $this->imgWidth-1 &&$bin[$_hIdx-1][$_wIdx+1]==0) $pixCount++;
                    //右下
                    if($_hIdx < $this->imgHeight-1 && $_wIdx < $this->imgWidth-1 && $bin[$_hIdx+1][$_wIdx+1]==0) $pixCount++;

                    if($pixCount<3) {
                        $lCount = 0;
                        //左侧
                        if($_wIdx >1 && $bin[$_hIdx][$_wIdx-1]==0) $lCount++;
                        //左下
                        if($_hIdx < $this->imgHeight-1 && $_wIdx >1 && $bin[$_hIdx+1][$_wIdx-1]==0) $lCount++;
                        //左上
                        if($_hIdx > 1 && $_wIdx >1 && $bin[$_hIdx-1][$_wIdx-1]==0) $lCount++;
                        //上面
                        if($_hIdx > 1 && $bin[$_hIdx-1][$_wIdx]==0) $lCount++;
                        //下面
                        if($_hIdx < $this->imgHeight-1 && $bin[$_hIdx+1][$_wIdx]==0) $lCount++;
                        if($lCount<2) $Y = 1;
                    };
                }
            }
    }

    protected function getImgSize()
    {
        $this->imgHandler = imagecreatefromstring($this->imgString);
        list($this->imgWidth,$this->imgHeight) = getimagesizefromstring($this->imgString);
        $this->imgWidth-=5;
    }
}