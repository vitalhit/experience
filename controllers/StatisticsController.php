<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use app\models\Abonements;
use app\models\Persons;
use app\models\Users;
use app\models\Smena;
use app\models\Visits;
use app\models\Tickets;
use app\models\Events;
use app\models\Goods;
use app\models\Sells;
use app\models\Rents;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

date_default_timezone_set('Europe/Moscow');


/**
 * PersonsController implements the CRUD actions for Persons model.
 */
class StatisticsController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
		'verbs' => [
			'class' => VerbFilter::className(),
			'actions' => [
					'delete' => ['POST'],
				],
			],
				'access' => [
				'class' => AccessControl::className(),
				'only' => ['index', 'create', 'view', 'update', 'delete', 'statistics'],
				'rules' => [
			[
				'allow' => false,
				'actions' => ['index', 'create', 'view', 'update', 'delete', 'statistics'],
				'roles' => ['?'],
			],
			[
				'allow' => true,
				'actions' => ['index', 'create', 'view', 'update', 'delete', 'statistics'],
				'roles' => ['@'],
			],
		],
		],
		];
	}


	public $enableCsrfValidation = false;

	public function actionIndex(){
		// ПО ДНЯМ
		$visitsall = Visits::findBySql('SELECT
			CAST(start AS DATE) as start, 
			SUM(CASE WHEN type = 1 THEN money ELSE 0 END) as type,
			SUM(CASE WHEN type = 2 THEN money ELSE 0 END) as user_id,
			SUM(CASE WHEN type = 3 THEN money ELSE 0 END) as event_id,
			SUM(CASE WHEN type = 4 THEN money ELSE 0 END) as discount_money,
			SUM(money) - SUM(CASE WHEN type = 4 THEN money ELSE 0 END) as money
			FROM visits GROUP BY CAST(start AS DATE) Order BY start DESC')->all();

		$ticketsall = Tickets::findBySql('SELECT
			CAST(date AS DATE) as date, 
			SUM(CASE WHEN type = 1 THEN summa ELSE 0 END) as type,
			SUM(CASE WHEN type = 2 THEN summa ELSE 0 END) as user_id,
			SUM(CASE WHEN type = 3 THEN summa ELSE 0 END) as event_id,
			SUM(CASE WHEN type = 4 THEN summa ELSE 0 END) as id,
			SUM(summa) - SUM(CASE WHEN type = 4 THEN summa ELSE 0 END) as money
			FROM tickets GROUP BY CAST(date AS DATE) Order BY date DESC')->all();


		$sellsall = Sells::findBySql('SELECT
			CAST(date AS DATE) as date, 
			SUM(CASE WHEN type = 1 THEN itogo ELSE 0 END) as type,
			SUM(CASE WHEN type = 2 THEN itogo ELSE 0 END) as price,
			SUM(CASE WHEN type = 3 THEN itogo ELSE 0 END) as count,
			SUM(CASE WHEN type = 4 THEN itogo ELSE 0 END) as good_id,
			SUM(itogo) - SUM(CASE WHEN type = 4 THEN itogo ELSE 0 END) as itogo
			FROM sells GROUP BY CAST(date AS DATE) Order BY date DESC')->all();

		$abonementsall = Abonements::findBySql('SELECT
			CAST(start AS DATE) as start, 
			SUM(CASE WHEN type = 1 THEN price ELSE 0 END) as user_id,
			SUM(CASE WHEN type = 2 THEN price ELSE 0 END) as countvis,
			SUM(CASE WHEN type = 3 THEN price ELSE 0 END) as price,
			SUM(CASE WHEN type = 4 THEN price ELSE 0 END) as balance,
			SUM(price) - SUM(CASE WHEN type = 4 THEN price ELSE 0 END) as info
			FROM abonements GROUP BY CAST(start AS DATE) Order BY start DESC')->all();

		$rentsall = Rents::findBySql('SELECT
			CAST(date AS DATE) as date, 
			SUM(CASE WHEN type = 1 THEN summa ELSE 0 END) as nal,
			SUM(CASE WHEN type = 2 THEN summa ELSE 0 END) as bez,
			SUM(CASE WHEN type = 4 THEN summa ELSE 0 END) as gr,
			SUM(summa) - SUM(CASE WHEN type = 4 THEN summa ELSE 0 END) as summa
			FROM rents GROUP BY CAST(date AS DATE) Order BY date DESC')->all();



		$result = array();
		foreach ($visitsall as $row) {
			
			$ticket_money = 0;
			foreach ($ticketsall as $tick) {
				if($tick['date'] == $row['start']){
					$ticket_money = $tick['money'];
					break;
				}
			}

			$sells_itogo = 0;
			foreach ($sellsall as $sell) {
				if($sell['date'] == $row['start']){
					$sells_itogo = $sell['itogo'];
					break;
				}
			}
			
			$abonemets_money = 0;
			foreach ($abonementsall as $abon) {
				if($abon['start'] == $row['start']){
					$abonemets_money = $abon['info'];
					break;
				}
			}
			
			$rents_money = 0;
			foreach ($rentsall as $rent) {
				if($rent['date'] == $row['start']){
					$rents_money = $rent['summa'];
					break;
				}
			}

			$result[] =
				[$row['start'],
				$row['money'],
				$ticket_money,
				$sells_itogo,
				$abonemets_money,
				$rents_money,
				$row['money'] + $ticket_money + $sells_itogo + $abonemets_money + $rents_money];
		}


		// КАЖДЫЙ МЕСЯЦ
		// Визиты
		$visitsallmonth = Visits::findBySql('SELECT
			Year(start) as type, Month(start) as start,
			Sum(money) - SUM(CASE WHEN type = 4 THEN money ELSE 0 END) as money
			FROM visits Group By Year(start), Month(start) Order BY type DESC, start DESC')->all();

		// Билеты
		$ticketsallmonth = Tickets::findBySql('SELECT
			Year(date) as type, Month(date) as date,
			Sum(summa) - SUM(CASE WHEN type = 4 THEN summa ELSE 0 END) as money
			FROM tickets Group By Year(date), Month(date) Order BY date DESC')->all();

		// Продажи
		$sellsallmonth = Sells::findBySql('SELECT
			Year(date) as type, Month(date) as date,
			Sum(itogo) - SUM(CASE WHEN type = 4 THEN itogo ELSE 0 END) as itogo
			FROM sells Group By Year(date), Month(date) Order BY date DESC')->all();

		// Абонементы
		$abonementsallmonth = Abonements::findBySql('SELECT
			Year(start) as type, Month(start) as start,
			Sum(price) - SUM(CASE WHEN type = 4 THEN price ELSE 0 END) as price
			FROM abonements Group By Year(start), Month(start) Order BY start DESC')->all();

		// Абонементы
		$rentssallmonth = Rents::findBySql('SELECT
			Year(start) as type, Month(start) as start,
			Sum(summa) - SUM(CASE WHEN type = 4 THEN summa ELSE 0 END) as summa
			FROM rents Group By Year(start), Month(start) Order BY start DESC')->all();


		// Доход по месяцам без GR
		$resultmonth = array();
		foreach ($visitsallmonth as $row) {
			
			$ticket_money = 0;
			foreach ($ticketsallmonth as $tick) {
				if($tick['type'] == $row['type'] && $tick['date'] == $row['start']){
					$ticket_money = $tick['money'];
					break;
				}
			}

			$sells_itogo = 0;
			foreach ($sellsallmonth as $sell) {
				if($sell['type'] == $row['type'] && $sell['date'] == $row['start']){
					$sells_itogo = $sell['itogo'];
					break;
				}
			}
			
			$abonements_money = 0;
			foreach ($abonementsallmonth as $abon) {
				if($abon['type'] == $row['type'] && $abon['start'] == $row['start']){
					$abonements_money = $abon['price'];
					break;
				}
			}
			
			$rents_money = 0;
			foreach ($rentssallmonth as $rent) {
				if($rent['type'] == $row['type'] && $rent['start'] == $row['start']){
					$rents_money = $rent['summa'];
					break;
				}
			}


			$resultmonth[] =
				[$row['start'],
				$row['type'],
				$row['money'],
				$ticket_money,
				$sells_itogo,
				$abonements_money,
				$rents_money,
				$row['money'] + $ticket_money + $sells_itogo + $abonements_money + $rents_money];
		}


		$sells = Sells::findBySql('SELECT
				sells.good_id,
				goods.name,
				SUM(sells.count) as count,
				SUM(sells.itogo) as itogo
				FROM sells 
				LEFT JOIN goods ON sells.good_id = goods.id
				GROUP BY good_id Order BY goods.name ASC')->all();


		return $this->render('index.twig', [
			'smenas_open' => $smenas_open ?? null,
			'smenas_done' => $smenas_done ?? null,
			'sells' => $sells,
			'sellsall' => $sellsall,
			'visitsall' => $visitsall,
			'ticketsall' => $ticketsall,
			'result' => $result,
			'resultmonth' => $resultmonth,
			'visitsallmonth' => $visitsallmonth,
			'ticketsallmonth' => $ticketsallmonth,
			'sellsallmonth' => $sellsallmonth
		] );
	}






	public function actionMystat(){

		// Получаем id текущего пользователя
		$uid = Yii::$app->user->id;

		// Определяем дату начала смен: 9 утра вчера, если сейчас меньше 9 и дата сегодня, если сейчас больше 9
		if (date("H") < 9) { // Если сейчас меньше чем 9 часов
			$thisdate = date('Y-m-d H:i:s', mktime(9, 0, 0, date('m'), date('d') - 1, date('Y'))); // Вчера 9 утра в Unix - проверить date('Y-m-d H:i:s', ...)
		} else {
			$thisdate = date('Y-m-d H:i:s', mktime(9, 0, 0, date('m'), date('d'), date('Y'))); // Сегодня 9 утра в Unix - проверить date('Y-m-d H:i:s', ...)
		}


		// ВСЕ ОТКРЫТЫЕ СМЕНЫ
		// ->andwhere('start > :a', ['a' => $thisdate]);
		$smenas_open = Smena::find()->where(['end' => null])->orderBy(['id' => SORT_DESC])->joinwith('users')->all();

		// Пересчитываем статистику по ОТКРЫТЫМ сменам
		foreach ($smenas_open as $smena) {

			$smena_id = $smena->id;
			$smena = Smena::findOne($smena_id);

			$smena->visits_n = Visits::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 1])->sum('money');
			$smena->visits_b = Visits::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 2])->sum('money');
			
			$smena->tickets_n = Tickets::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 1])->sum('summa');
			$smena->tickets_b = Tickets::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 2])->sum('summa');
			
			$smena->sells_n = Sells::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 1])->sum('itogo');
			$smena->sells_b = Sells::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 2])->sum('itogo');
			
			$smena->abonements_n = Abonements::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 1])->sum('price');
			$smena->abonements_b = Abonements::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 2])->sum('price');
			
			$smena->rents_n = Rents::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 1])->sum('summa');
			$smena->rents_b = Rents::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 2])->sum('summa');
	
			$smena->save();
		}

		// По-новой открываем пересчитанные цифры
		$smenas_open = Smena::find()->where(['end' => null])->orderBy(['id' => SORT_DESC])->joinwith('users')->all();

		
		// ЗАКРЫТЫЕ СМЕНЫ
		$query = Smena::find()->where(['not', ['end' => null]])->joinwith('users');
		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 20]);
		$smenas_done = $query->offset($pages->offset)->limit($pages->limit)->orderBy(['id' => SORT_DESC])->all();



		// Получить записи смен из базы и преобразовать в массив уникальных дат
		// у первой записи взять дату, установить время на 9:00
		// Перевести в startotime и сравнить с текущей записью, что больше чем 9:00 и меньше чем + 1day 8:59
		// Если да, то добавить в 
		
		$dates = array();
		foreach ($smenas_done as $smena) {
			$day = date('Y-m-d H:i:s', mktime(9, 01, 0, date('m', strtotime($smena->start)), date('d', strtotime($smena->start)), date('Y', strtotime($smena->start))));
			if (!in_array($day, $dates)) {
				$dates[] = $day;				
			}
		}

		$smenas = array();
		foreach ($dates as $date) {
			$tomorrow = date('Y-m-d H:i:s', mktime(9, 0, 0, date('m', strtotime($date)), date('d', strtotime($date)) + 1, date('Y', strtotime($date))));
			$visits_n = 0;
			$visits_b = 0;
			$tickets_n = 0;
			$tickets_b = 0;
			$sells_n = 0;
			$sells_b = 0;
			$abonements_n = 0;
			$abonements_b = 0;
			$rents_n = 0;
			$rents_b = 0; 

			foreach ($smenas_done as $smena) {
				if ((strtotime($smena->start) > strtotime($date)) and (strtotime($smena->end) < strtotime($tomorrow))) {
					// echo strtotime($smena->start)." > ".strtotime($yesterday)." and ".strtotime($smena->end)." < ".strtotime($day)."<br>";
					$smenas[] = $smena;

					$visits_n = $visits_n + $smena->visits_n;
					$visits_b = $visits_b + $smena->visits_b;
					$tickets_n = $tickets_n + $smena->tickets_n;
					$tickets_b = $tickets_b + $smena->tickets_b;
					$sells_n = $sells_n + $smena->sells_n;
					$sells_b = $sells_b + $smena->sells_b;
					$abonements_n = $abonements_n + $smena->abonements_n;
					$abonements_b = $abonements_b + $smena->abonements_b;
					$rents_n = $rents_n + $smena->rents_n;
					$rents_b = $rents_b + $smena->rents_b;
				}
			}

			$smenas[] = array('date' => $date, 'visits_n' => $visits_n, 'visits_b' =>  $visits_b, 'tickets_n' => $tickets_n, 'tickets_b' => $tickets_b, 'sells_n' => $sells_n, 'sells_b' => $sells_b, 'abonements_n' => $abonements_n, 'abonements_b' => $abonements_b, 'rents_n' => $rents_n, 'rents_b' => $rents_b);
		}


		// echo "<pre>";
		// print_r($smenas);
		// echo "</pre>";


		// Пересчитываем статистику по ЗАКРЫТЫМ сменам на странице
		foreach ($smenas_done as $smena) {

			$smena_id = $smena->id;
			$smena = Smena::findOne($smena_id);

			$smena->visits_n = Visits::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 1])->sum('money');
			$smena->visits_b = Visits::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 2])->sum('money');
			
			$smena->tickets_n = Tickets::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 1])->sum('summa');
			$smena->tickets_b = Tickets::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 2])->sum('summa');
			
			$smena->sells_n = Sells::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 1])->sum('itogo');
			$smena->sells_b = Sells::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 2])->sum('itogo');
			
			$smena->abonements_n = Abonements::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 1])->sum('price');
			$smena->abonements_b = Abonements::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 2])->sum('price');

			
			$smena->rents_n = Rents::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 1])->sum('summa');
			$smena->rents_b = Rents::find()->where(['smena_id' => $smena_id])->andwhere(['type' => 2])->sum('summa');

			$smena->save();
		}
		// По-новой открываем пересчитанные цифры
		$smenas_done = $query->offset($pages->offset)->limit($pages->limit)->orderBy(['id' => SORT_DESC])->all();


		// ДЛЯ СВЕРКИ !!! Считаем все деньги с 9 утра до текущего момента, игнорируя смены.
		// Чтобы знать всю сумму, даже не попавшую в смены.
		$summnal1 = Visits::find()->where(['type' => 1])->andwhere('end > :a', ['a' => $thisdate])->sum('money');
		$summbez1 = Visits::find()->where(['type' => 2])->andwhere('end > :a', ['a' => $thisdate])->sum('money');
		$summgr1 = Visits::find()->where(['type' => 4])->andwhere('end > :a', ['a' => $thisdate])->sum('money');

		$summnal2 = Sells::find()->where(['type' => 1])->andwhere('date > :a', ['a' => $thisdate])->sum('itogo');
		$summbez2 = Sells::find()->where(['type' => 2])->andwhere('date > :a', ['a' => $thisdate])->sum('itogo');
		$summgr2 = Sells::find()->where(['type' => 4])->andwhere('date > :a', ['a' => $thisdate])->sum('itogo');

		$summnal3 = Tickets::find()->where(['type' => 1])->andwhere('date > :a', ['a' => $thisdate])->sum('summa');
		$summbez3 = Tickets::find()->where(['type' => 2])->andwhere('date > :a', ['a' => $thisdate])->sum('summa');
		$summpad3 = Tickets::find()->where(['type' => 3, 'type' => 9])->andwhere('date > :a', ['a' => $thisdate])->sum('summa');
		$summgr3 = Tickets::find()->where(['type' => 4])->andwhere('date > :a', ['a' => $thisdate])->sum('summa');

		$summnal4 = Abonements::find()->where(['type' => 1])->andwhere('create_at > :a', ['a' => $thisdate])->sum('price');
		$summbez4 = Abonements::find()->where(['type' => 2])->andwhere('create_at > :a', ['a' => $thisdate])->sum('price');
		$summgr4 = Abonements::find()->where(['type' => 4])->andwhere('create_at > :a', ['a' => $thisdate])->sum('price');

		$summnal5 = Rents::find()->where(['type' => 1])->andwhere('date > :a', ['a' => $thisdate])->sum('summa');
		$summbez5 = Rents::find()->where(['type' => 2])->andwhere('date > :a', ['a' => $thisdate])->sum('summa');
		$summgr5 = Rents::find()->where(['type' => 4])->andwhere('date > :a', ['a' => $thisdate])->sum('summa');

		$summnal = $summnal1 + $summnal2 + $summnal3 + $summnal4 + $summnal5;
		$summbez = $summbez1 + $summbez2 + $summbez3 + $summbez4 + $summbez5;
		$summpad = $summpad3; //Билетник
		$summgr = $summgr1 + $summgr2 + $summgr3 + $summgr4 + $summgr5; //Goodrepublic


		return $this->render('mystat.twig', [
			'thisdate' => $thisdate,
			'smenas_open' => $smenas_open,
			'smenas_done' => $smenas_done,
			'pages' => $pages,
			'summnal' => $summnal,
			'summbez' => $summbez,
			'summpad' => $summpad,
			'summgr' => $summgr,
			'smenas' => $smenas
		] );
	}

}

