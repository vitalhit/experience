<?php

namespace app\models;

use Yii;
use VK\CallbackApi\Server\VKCallbackApiServerHandler; 

class Vkcallback extends VKCallbackApiServerHandler { 
	const SECRET = 'dafd315da086010133'; 
	const GROUP_ID = 136527321; 
	const CONFIRMATION = 'e50214b8';

	function confirmation(int $group_id, ?string $secret) { 
		if ($secret === static::SECRET && $group_id === static::GROUP_ID) { 
			return 'e50214b8'; 
		} 
	} 

	// public function messageNew(int $group_id, ?string $secret, array $object) {
	// 	file_put_contents('test.txt', PHP_EOL . Date('d.m.Y H:i:s - вк: ') . json_encode($object), FILE_APPEND);
	// 	if ($object['body'] == 'Вкл') {
	// 		Vk::Send('444 Уведомления включены!', [$object['user_id']]);
	// 	}
	// 	header("HTTP/1.1 200 OK");
	// 	return 'ok';
	// }

} 