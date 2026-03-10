<!-- Что-то старое -->

<img src="/txtfon.png">

<?php


function wordWrapAnnotation($image, $draw, $text, $maxWidth)
{
    $words = preg_split('%\s%', $text, -1, PREG_SPLIT_NO_EMPTY);
    $lines = array();
    $i = 0;
    $lineHeight = 0;
    while (count($words) > 0)
    {
        $metrics = $image->queryFontMetrics($draw, implode(' ', array_slice($words, 0, ++$i)));
        $lineHeight = max($metrics['textHeight'], $lineHeight);

        if ($metrics['textWidth'] > $maxWidth or count($words) < $i)
        {
            $lines[] = implode(' ', array_slice($words, 0, --$i));
            $words = array_slice($words, $i);
            $i = 0;
        }
    }

    return array($lines, $lineHeight);
}

function createImageFromText($text){

    $maxWidth = 600;
    $font = 'fonts/myriad-set-pro_bold.ttf';
    $fontSize = 50;
    $filename = 'res.png';
    $padding = 50;

    /* Create a new Imagick object */
    $image = new Imagick();
            $image->newImage(1, 1, 'none'); // none = transparent
            $image->setImageFormat("png");

            /* Create an ImagickDraw object */
            $draw = new ImagickDraw();

            /* Set the font */
            $draw->setFont($font);
            $draw->setFontSize($fontSize);

            list($lines, $lineHeight) = wordWrapAnnotation($image, $draw, $text, $maxWidth);
            $image->newImage($maxWidth+$padding, $padding+ count($lines)*$lineHeight, 'none'); // none = transparent    

            for($i = 0; $i < count($lines); $i++)
                $image->annotateImage($draw, $padding, + ($i+1)*$lineHeight, 0, $lines[$i]);

            //$image->writeImage($filename);
            return $image;
            
        }
        createImageFromText('Новая')->writeImage('text.png');




		// шаблонное изображение
        $dest = imagecreatefrompng('text.png');

		// обложка
        $src = imagecreatefromjpeg('txtfon.jpg');

		// настройка прозрачности и фильтров
        imagealphablending($dest, false);
        imagesavealpha($dest, true);

		// объединение изображений
        imagecopymerge($dest, $src, 10, 9, 0, 0, 181, 180, 100);

		// отображаем изображение		
        $dest = imagecreatefrompng ('/web/test.png');
        imagepng ($dest);

		// очищаем память
        imagedestroy($dest);
        imagedestroy($src);


        ?>
