<?php

require __DIR__.'/../autoload.php';

/**
     * 生成二维码
     * @param $text 数据; $size 尺寸; $margin边距; $level = 'L';// 纠错级别：L、M、Q、H $saveandprint = true;// true直接输出屏幕  false 保存到文件中 $outfile 为false直接输出至屏幕;
     * $back_color = 0xFFFFFF;//白色底色 $fore_color = 0x000000;//黑色二维码色 若传参数要hexdec处理，如 $fore_color = str_replace('#','0x',$fore_color); $fore_color = hexdec('0xCCCCCC');
     */

LitePhp\qrCode::png('abc');


/*---

//用svg产生二维码
\LitePhp\qrCode::svg('abc');

---*/