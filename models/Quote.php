<?php

namespace app\models;
use Yii;

/**
 * This is the model class for table "quote".
 *
 * @property int $id
 * @property string $text
 * @property string $author
 * @property int $status
 */
class Quote extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'quote';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['text'], 'required'],
			[['text'], 'string'],
			[['status'], 'integer'],
			[['author'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'text' => 'Text',
			'author' => 'Author',
			'status' => 'Status:  0 - delete; 1 - create; 2 - created; 3 - дубль',
		];
	}


	// Цитата
	public static function Quote()
	{
		$quotes = Quote::find()->where(['status' => 1])->limit(20)->all();
		$i = 1;
		foreach ($quotes as $quote) {
			$color_hex = Quote::hexToRgb( ($_POST['color']??"000" ));

			$im = imagecreatefromjpeg('https://igoevent.com/images/'.$_POST['fon'].'.jpg');

			$box = new Qbox($im);
			$box->setFontFace('/var/www/igoevent.com/html/web/fonts/myriad-set-pro_bold.ttf');
			$box->setFontColor(new Qcolor($color_hex['red'], $color_hex['green'], $color_hex['blue']));
			$box->setTextShadow(new Qcolor(0, 0, 0, 50), 2, 2);
			$box->setFontSize($_POST['size']*2);
			$box->setLineHeight(1.2);
			//$box->enableDebug();
			$box->setBox(100, 100, 800, 800);
			$box->setTextAlign('center', 'center');
			$text = str_replace('^', "\r\n", $quote->text); // заменяет крышечку на перенос строки
			$box->draw($text.PHP_EOL.$quote->author);

			$file = '/var/www/igoevent.com/html/web/quotes/'.$quote->id.'.png';
			$i++;

			$q = Quote::findOne($quote->id);
			$q->status = 2;
			$q->save();


			imagepng($im, $file, 9, PNG_ALL_FILTERS);
			//imagepng($img, 'tmp/generated.jpg'); //, 'tmp/generated.jpg'
			// // Контент-тип означающий скачивание
			// header("Content-Type: application/octet-stream");
			// // Размер в байтах
			// header("Accept-Ranges: bytes");
			// // Размер файла
			// header("Content-Length: ".filesize($file));
			// // Расположение скачиваемого файла
			// header("Content-Disposition: attachment; filename=".$file);  
			// // Прочитать файл
			// readfile($file);
			// //echo '<img src="tmp/generated.jpg" width=900>';
			// imagedestroy($im);
		}
		return $quote;
	}


	// Цитата
	public function Quotekudago()
	{
		$color_hex = Quote::hexToRgb($_POST['color']);

		$im = imagecreatefromjpeg('https://igoevent.com/images/'.$_POST['fon'].'.jpg');

		$box = new Qbox($im);
		$box->setFontFace('/var/www/igoevent.com/html/web/fonts/PSS65__W.ttf');
		$box->setFontColor(new Qcolor($color_hex['red'], $color_hex['green'], $color_hex['blue']));
		$box->setTextShadow(new Qcolor(0, 0, 0, 0), 0, 0);
		$box->setFontSize($_POST['size']*3.2);
		$box->setLineHeight(1.2);
			//$box->enableDebug();
		$box->setBox(100, 100, 800, 800);
		$box->setTextAlign('center', 'center');
		$box->draw($_POST['text']);

		$file = '/var/www/igoevent.com/html/web/quotes/generated.png';

		imagepng($im, $file, 9, PNG_ALL_FILTERS);
		// imagepng($img, 'tmp/generated.jpg'); //, 'tmp/generated.jpg'
		// Контент-тип означающий скачивание
		header("Content-Type: application/octet-stream");
		// Размер в байтах
		header("Accept-Ranges: bytes");
		// Размер файла
		header("Content-Length: ".filesize($file));
		// Расположение скачиваемого файла
		header("Content-Disposition: attachment; filename=".$file);  
		// Прочитать файл
		readfile($file);
		//echo '<img src="tmp/generated.jpg" width=900>';
		imagedestroy($im);
		return $quote;
	}


	public static function hexToRgb($color)
	{
	    // проверяем наличие # в начале, если есть, то отрезаем ее
	    if ($color[0] == '#') {
	        $color = substr($color, 1);
	    }
	   
	    // разбираем строку на массив
	    if (strlen($color) == 6) { // если hex цвет в полной форме - 6 символов
	        list($red, $green, $blue) = array(
	            $color[0] . $color[1],
	            $color[2] . $color[3],
	            $color[4] . $color[5]
	        );
	    } elseif (strlen($color) == 3) { // если hex цвет в сокращенной форме - 3 символа
	        list($red, $green, $blue) = array(
	            $color[0]. $color[0],
	            $color[1]. $color[1],
	            $color[2]. $color[2]
	        );
	    }else{
	        return false; 
	    }
	 
	    // переводим шестнадцатиричные числа в десятичные
	    $red = hexdec($red); 
	    $green = hexdec($green);
	    $blue = hexdec($blue);
	     
	    // вернем результат
	    return array(
	        'red' => $red, 
	        'green' => $green, 
	        'blue' => $blue
	    );
	}


}
