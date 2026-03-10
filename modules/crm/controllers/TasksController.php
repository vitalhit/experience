<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Tasks;
use app\models\Chat;
use app\models\Biblioevents;
use app\models\Projects;
use app\models\Companies;
use app\models\Feedback;
use app\models\Users;
use app\models\TaskBand;
use app\models\TasksProjects;
use app\models\TasksStatus;
use app\models\TasksType;
use app\models\TaskClient;
use app\models\TaskBiblioevent;
use app\models\TaskLink;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * TasksController implements the CRUD actions for Tasks model.
 */
class TasksController extends Controller
{

	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	public $enableCsrfValidation = false;


	public function actionMy($projectid = false, $owner = false, $creator = false, $taskid = false, $status = false, $status2 = false, $type = false, $priority = false)
	{
		if (!empty($projectid)) {
			$project = Projects::find()
			->where('projects.id = :pid', [':pid' => $projectid])
			->andWhere(['company_id' => Companies::getCompanyId()])
			->joinWith('owners')
			->joinWith('status')
			->one();
			$pro_ids = $project->id;
		}else{
			$project = Projects::find()
			->andWhere(['company_id' => Companies::getCompanyId()])
			->joinWith('owners')
			->joinWith('status')
			->all();
			$pro_ids = ArrayHelper::getColumn($project, 'id');
		}

		// if (empty($project)) {
		// 	Yii::$app->getSession()->setFlash('danger', 'У вас нет такого проекта. Выберите компанию!');
		// 	return $this->redirect(['/crm/projects/my']);
		// }
		$url = Null;
		$get = Yii::$app->request->get();
		foreach ($get as $t => $l) {
			$url .=$t.'='.$l.'&';
		}

        // echo "<pre>";
        // print_r($pro_ids);
        // echo "</pre>";
		$thisdate = date('Y-m-d H:i:s');
		$this_user = Users::findOne(Yii::$app->user->id);
		
		$task = Tasks::find()->joinWith('type')->joinWith('status')->where(['project_id' => $pro_ids]);

		if (!empty($owner)) {
			$task->andWhere(['owner_id' => $owner]);
		}
		if (!empty($creator)) {
			$task->andWhere(['creator_id' => $creator]);
		}
		if (!empty($status)) {
			$task->andWhere(['tasks.status_id' => $status]);
			if ($status == 8) {
				$task->andWhere(['tasks.status_id' => $status])->andwhere('start < :a', ['a' => $thisdate]);
			}
		}
		if (!empty($type)) {
			$task->andWhere(['tasks.type_id' => $type]);
		}
		
		if ($priority == 'SORT_DESC') {
			$task->orderBy(['priority' => SORT_DESC]);
		}elseif ($priority == 'SORT_ASC') { 
			$task->orderBy(['priority' => SORT_ASC]);
		} else {
			$task->orderBy(['start' => SORT_DESC]);
		}

		$tasks = $task->all();



		if ($status == 5) {
			$tasks_comps = Tasks::find()->joinWith('projects')->where(['tasks.status_id' => 5])->andWhere(['tasks.creator_id' => Yii::$app->user->id])->groupBy('projects.company_id')->all();
		} else if  ($status == 8) {
			$tasks_comps = Tasks::find()->joinWith('projects')->where(['tasks.status_id' => [8]])->andWhere(['tasks.owner_id' => Yii::$app->user->id])->andwhere('start < :a', ['a' => $thisdate])->groupBy('projects.company_id')->all();
		} else if (($status == 1) or (empty($status))) {
			$tasks_comps = Tasks::find()->joinWith('projects')->where(['tasks.status_id' => [1,2]])->andWhere(['tasks.owner_id' => Yii::$app->user->id])->groupBy('projects.company_id')->all();
		}
		if (!empty($tasks_comps)) {
			$comp_ids = ArrayHelper::getColumn($tasks_comps, 'projects.company_id');
			$comps = Companies::find()->where(['id' => $comp_ids])->andWhere(['not in','id',$this_user->company_active])->all();
		} else {
			$comps = null;
		}

		// Вывести мои таски и таски на проверку из ВСЕХ проектов
		// if (!empty($creator) and $status == 5) {
		// 	$tasks = Tasks::find()->joinWith('type')->joinWith('status')->where(['status_id' => 5])->andWhere(['creator_id' => Yii::$app->user->id])->all();
		// }
		
		// if (!empty($owner) and $status == 1) {
		// 	$tasks = Tasks::find()->joinWith('type')->joinWith('status')->where(['status_id' => [1,2]])->andWhere(['owner_id' => Yii::$app->user->id])->all();
		// }

		$filtr_tasks = Tasks::find()->where(['project_id' => $pro_ids])->all();

		$owners_id = Null;
		// Выбирает только тех администраторов, у которых есть таски
		foreach ($filtr_tasks as $task) {
			$owners_id[] = $task->owner_id;
		}
		$creator_id = Null;
		// Выбирает создателей, у которых есть таски
		foreach ($filtr_tasks as $task) {
			$creator_id[] = $task->creator_id;
		}

		$filtr_owners = $owners = Users::find()->where(['status' => 10])->andWhere(['id' => $owners_id])->all();
		$filtr_creators = Users::find()->where(['status' => 10])->andWhere(['id' => $creator_id])->all();
		$filtr_statuses = TasksStatus::find()->all();
		$filtr_types = TasksType::find()->all();

		$pageurl = 'tasks/my?status='.$status; //'&owner='.$owner.'&cretor='.$creator.'&status='.$status2;

		return $this->render('my.twig', [
			'tasks' => $tasks,
			'owners' => $owners,
			'url' => $url,
			'get' => $get,
			'comps' => $comps,
			'project' => $project,
			'filtr_owners' => $filtr_owners,
			'filtr_statuses' => $filtr_statuses,
			'filtr_types' => $filtr_types,
			'filtr_creators' => $filtr_creators,
			'pageurl' => $pageurl
		]);
	}





	// Перенос базы
	// public function actionUp()
	// {
	// 	$tasks = Tasks::find()->all();
	// 	$i = 0;
	// 	foreach ($tasks as $task) {
	// 		$task_pro = TasksProjects::find()->where(['task_id' => $task->id])->one();
	// 		$my = Tasks::find()->where(['id' => $task->id])->one();
	// 		echo $my->id.": ".$task_pro->project_id."<br>";
	// 		$my->project_id = $task_pro->project_id;
	// 		$my->save();
	// 		$i++;
	// 	}
	// }



	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}



	public function actionCreate($projectid, $tasksid = false)
	{
		$model = new Tasks();

		$projects = Projects::find()
		->where(['projects.id' => $projectid])
		->andWhere(['company_id' => Companies::getCompanyId()])
		->one();

		// echo "<pre>";
        // print_r($projects );
        // echo "</pre>";

		if (empty($projects)) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет такого проекта.');
			return $this->redirect(['/projects']);
		}

		// Если у таска есть tasksid - нходим этот родительский таск
		$parent_task = Null;
		if ($tasksid) {
			$parent_task = Tasks::findOne($tasksid);
		}

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if($_POST['new'] == 'Сохранить и создать еще задачу'){
				Yii::$app->getSession()->setFlash('success', 'Задача создана.');
				return $this->redirect(['/crm/tasks/create?projectid='.$projects->id]);
			} else {
				Yii::$app->getSession()->setFlash('success', 'Задача создана.');
				return $this->redirect(['/crm/tasks/my?projectid='.$projects->id]);
			}
		} else {
			return $this->render('create.twig', [
				'model' => $model, 'projects' => $projects, 'parent_task' => $parent_task
			]);
		}
	}



	public function actionUpdate($id, $projectid = false, $status = false)
	{
		// Все проекты
		$pro_ids = Projects::ProjectIds();
		$model = Tasks::find()->where('tasks.id = :id', [':id' => $id])->andWhere(['project_id' => $pro_ids])->joinWith('docs')->one();
		$project = Projects::findOne($model->project_id);


		if (empty($model)) {
			// Проверим доступ к таску в других компаниях
			$pro_ids = Projects::ProjectIdsAll();
			$model = Tasks::find()->where('tasks.id = :id', [':id' => $id])->andWhere(['project_id' => $pro_ids])->joinWith('docs')->one();
			$project = Projects::findOne($model->project_id);

			if (!empty($model)) {
				Yii::$app->getSession()->setFlash('success', 'Этот таск в другой компании. <br>Телепортация завершена! 🏃🏼‍');
				Companies::setCompany($project->company_id);
				return $this->redirect(['/crm/tasks/my?projectid='.$project->id]);
			} else {
				Yii::$app->getSession()->setFlash('danger', 'У вас нет такого проекта и таска.');
				return $this->redirect('/crm/projects/my');
			}
		}


		// Получаем все статусы
		$statuses = TasksStatus::find()->all();

		$biblioevent = Biblioevents::find()->all();
		// echo "<pre>";
        // print_r($biblioevent );
        // echo "</pre>";

		// Находим все ПОДзадачи этой задачи
		if ($status) {
			$tasks = Tasks::find()->where(['parent_id' => $id ])->andwhere(['tasks.status_id' => $status])->orderBy(['id' => SORT_DESC])->all();
		} else {
			$tasks = Tasks::find()->where(['parent_id' => $id ])->orderBy(['id' => SORT_DESC])->all();
		}

		$chat = new Chat();
		$chats = Chat::find()->where(['task_id' => $id, 'chat.status' => 1 ])->joinWith('user')->joinWith('user.person')->orderBy('id asc')->all();

        // echo "<pre>";
        // print_r($model);
        // echo "</pre>";

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if($_POST['new'] == 'Сохранить и создать еще задачу'){
				Yii::$app->getSession()->setFlash('success', 'Задача сохранена.');
				return $this->redirect(['/crm/tasks/create?projectid='.$model->project_id]);
			} else {
				Yii::$app->getSession()->setFlash('success', 'Задача сохранена.');
				return $this->redirect(['/crm/tasks/my?projectid='.$model->project_id]);
			}
		} else {
			return $this->render('update.twig', [
				'model' => $model,
                'projects' => $projects ?? null,
				'tasks' => $tasks,
				'statuses' => $statuses,
				'project' => $project,
				'chat' => $chat,
				'chats' => $chats,
				'user_id' => Yii::$app->user->id,
				'biblioevent' => $biblioevent
			]);
		}
	}



	// Добавляем запись в Чат по ajax
	public function actionChat()
	{

		$chat = new Chat();
		$chat->user_id = $_POST['user_id'];
		$chat->task_id = $_POST['task_id'];
		$chat->for_user_id = $_POST['for_user_id'];
		$chat->text = nl2br($_POST['text']);

		if ($chat->save()) {
			$chats = Chat::find()->where(['task_id' => $_POST['task_id'], 'chat.status' => 1 ])->joinWith('user')->joinWith('user.person')->orderBy('id asc')->all();
			return $this->renderPartial('chat.twig', ['chats' => $chats]);
		} else {
			echo 'Ошибка!';
		}
	}
	
	// Добавляем запись промежуточную талицу!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	public function actionAddclient()
	{
		$tc = new TaskClient();
		$tc->task_id = $_POST['task_id'];
		$tc->client_id = $_POST['client_id'];
		$tc->info = $_POST['info'];
		if ($tc->save()) {
			return $this->redirect(['/crm/tasks/update', 'id' => $_POST['task_id']]);
		} else {
			echo 'Ошибка!';
		}
	}

	public function actionAddlink()
	{
		$tl = new TaskLink();
		$tl->task_id = $_POST['task_id'];
		$tl->link = $_POST['link'];
		$tl->link_text = $_POST['link_text'];
		$tl->message = $_POST['message'];
		if ($tl->save()) {
			return $this->redirect(['/crm/tasks/update', 'id' => $_POST['task_id']]);
		} else {
			echo 'Ошибка!';
		}
	}

	// Добавляем запись промежуточную талицу!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	public function actionAddband()
	{
		$tc = new TaskBand();
		$tc->task_id = $_POST['task_id'];
		$tc->band_id = $_POST['band_id'];
		$tc->info = $_POST['info'];
		if ($tc->save()) {
			return $this->redirect(['/crm/tasks/update', 'id' => $_POST['task_id']]);
		} else {
			echo 'Ошибка!';
		}
	}

	// Добавляем запись промежуточную талицу!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	public function actionAddtaskbiblioevent()
	{
		$tb = new TaskBiblioevent();
		$tb->task_id = $_POST['task_id'];
		$tb->biblioevent_id = $_POST['biblioevent_id'];
		$tb->info = $_POST['info'];
		if ($tb->save()) {
			return $this->redirect(['/crm/tasks/update', 'id' => $_POST['task_id']]);
		} else {
			echo 'Ошибка!';
		}
	}



	// Удаляем запись Чата по ajax
	public function actionDelchat($id)
	{
		$chat = Chat::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id ])->one();
		if (!empty($chat)) {
			$chat->status = 0;
			if ($chat->save()) {
				return $chat->id;
			} else {
				file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - чат ошибка ').json_encode($chat->getErrors(),JSON_UNESCAPED_UNICODE), FILE_APPEND);
			}
		}
	}





	// Обновить Веху
	public function actionUpdateveha($id)
	{
		$model = $this->findModel($id);

		$task = Tasks::findOne($model->parent_id);


		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['/crm/tasks/update', 'id' => $model->parent_id]);
		} else {
			return $this->render('updateveha', [
				'model' => $model, 'task' => $task,
			]);
		}
	}





	// Добавляем таск в проект по ajax
	public function actionAddtoproject()
	{
		$add = new Tasksprojects();
		$add->task_id = $_POST['taskid'];
		$add->project_id = $_POST['projectid'];

		if ($add->save()) {
			// По новой находим этот таск
			$model = $this->findModel($add->task_id);

			// Находим все проекты этого таска
			$projects = $model->projects;

			return $this->renderPartial('projects.twig', ['projects'=>$projects]);
		} else {
			echo 'Ошибка!';
		}
	}



	public function actionDelete($projectid = false, $taskid)
	{
		$projects = Projects::find()
		->joinWith('tasks')
		->where(['projects.id' => $projectid])
		->andWhere(['company_id' => Companies::getCompanyId()])
		->andWhere(['tasks.id' => $taskid])
		->one();

		if (empty($projects)) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет такого проекта.');
			return $this->redirect(['/projects']);
		}

		$task = Tasks::findOne($taskid);
		$task->delete();
		
		if (!empty($projectid)) {
			return $this->redirect(['/crm/tasks/my', 'projectid' => $projectid] );
		}else{
			return $this->redirect(['/crm/tasks/my'] );
		}
	}




	/**
	 * Finds the Tasks model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Tasks the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Tasks::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
