<?php

namespace app\Services\Eventcollection;

use Yii;

use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

class Api
{
	public function listevent()
	{

		$url = 'https://ec.igoevent.com/api/v1/events?from=2017-01-01&to=2020-12-31';

		// Инициализация cURL
		$ch = curl_init($url);

		// Настройки запроса
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // отключить проверку SSL (если нужно)

		// Выполнение запроса
		$response = curl_exec($ch);

		// Проверка на ошибки
		if (curl_errno($ch)) {
		    echo 'Ошибка запроса: ' . curl_error($ch);
		    exit;
		}

		// Закрытие cURL
		curl_close($ch);

		// Преобразование JSON в массив
		$data = json_decode($response, true);


		return $data;
		// Вывод результата
		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
	}
}

