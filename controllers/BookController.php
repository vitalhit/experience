<?php

namespace app\controllers;

use app\Services\Book\Form;
use app\Services\Finance\Dohod;

use Yii;
use app\models\Ads;
use app\models\Band;
use app\models\Biblioevents;
use app\models\BiblioeventSection;
use app\models\Bookingapi;
use app\models\Booking;
use app\models\Cities;
use app\models\Companies;
use app\models\Categoryevents;
use app\models\Contragent;
use app\models\Events;
use app\models\EventFinance;
use app\models\Img;
use app\models\Feedback;
use app\models\Landing;
use app\models\LoginForm as Login;
use app\models\LogPage;
use app\models\LogCron;
use app\models\Messages;
use app\models\Newsmakers;
use app\models\NewsmakersEvents;
use app\models\Page;
use app\models\PasswordResetRequest;
use app\models\Persons;
use app\models\Places;
use app\models\Post;
use app\models\Rents;
use app\models\ResetPassword;
use app\models\Rooms;
use app\models\Seats;
use app\models\Seatings;
use app\models\Section;
use app\models\Signup;
use app\models\Smena;
use app\models\Tickets;
use app\models\Users;
use app\models\Vk;
use app\models\AuthHandler;
use app\models\Vitalhit;
use Da\QrCode\QrCode;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\base\Component;
use yii\helpers\FileHelper;

//use yii\data\ActiveDataProvider;
//use yii\web\Controller;
//use yii\web\NotFoundHttpException;
//use yii\filters\VerbFilter;

class BookController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }


    public function actionTobewithus()
    {
        $model = new Contragent();
        $company = Companies::getCompany();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tobewithusthanks']);
        }

        return $this->render('tobewithus.twig', ['model' => $model, 'company' => $company]);
    }

    public function actionTobewithyou()
    {
        $model = new Contragent();
        $company = Companies::getCompany();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tobewithusthanks']);
        }

        return $this->render('tobewithyou.twig', ['model' => $model, 'company' => $company]);
    }

    public function actionTobewithusthanks()
    {
        $model = new Contragent();
        $company = Companies::getCompany();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tobewithusthanks']);
        }

        return $this->render('tobewithusthanks.twig', ['model' => $model, 'company' => $company]);
    }


    public function actionDoc($id)
    {
        $model = EventFinance::findOne($id);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();

        $event = Events::findOne($model->event_id);

        //    if ($event->id == ''){
        //    return $this->redirect('/login');
        // }

        $biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();
        $place = Places::find()->where(['id' => $event->place_id])->one();

        $summa_p = Companies::Propis($model['summa']);
        $money = array(
            'summa_p' => $summa_p
        );

        // echo "<pre>";
        // print_r($place);              
        // echo "</pre>";

        if ($model->contract_template_id == 11) {
            $content = $this->renderPartial('contract/2021foursiz-refund.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent' => $biblioevent, 'place' => $place]);
        } elseif ($model->contract_template_id == 12) {
            $content = $this->renderPartial('contract/2021foursiz-refund-new-name.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent' => $biblioevent, 'place' => $place]);
        } elseif ($model->contract_template_id == 13) {
            $content = $this->renderPartial('contract/2022foursiz-03-partner.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent' => $biblioevent, 'place' => $place]);
        } elseif ($model->contract_template_id == 14) {
            $content = $this->renderPartial('contract/2022-note-for-tax-01.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent' => $biblioevent, 'place' => $place]);
        } else {
            $content = $this->renderPartial('contract/2022foursiz-03.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent' => $biblioevent, 'place' => $place]);
        }


        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/bootstrap.css',
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/akt.css',
            // set mPDF properties on the fly
            'options' => ['title' => 'Акт'],

        ]);
        $pdf->getApi()->addPage();

        // return the pdf output as per the destination setting
        return $pdf->render();
    }


    public function actionAddplace()
    {

        $model = new Bookingapi();

        $isimage = $model->image;


        if ($_POST) {
            $event_id = $_POST['event_id'] ?? Null;
        } else {
            $event_id = Null;
        }


        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 1;

            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = Yii::$app->storage->saveBookingFile($image, $model->company_id ?? 0);
            } else {
                $model->image = $isimage;
            }


            if ($model->save()) {

                Vk::Send('Add place <br>' . $model->brand . ' / '
                    . $model->second_name . ' ' . $model->name . ' ' . $model->thirdname . ' ' . $model->phone . ' ' . $model->mail .
                    ((empty($model->image)) ? ' не прикрепил(а) заявление на возврат. ' : '<br>Заявленние: https://igoevent.com/uploads/booking/' . $model->image) . '<br>Сообщение:' . $model->message . '<br>Меню: <br> igoe.ru/fs/booking — все заявки <br>igoe.ru/igo/company/ — выбор кабинета', [90794]);

                return $this->redirect(['foursizthanks']);
            } else {
                // echo "<pre>";
                // print_r($model);
                // echo "</pre>";
                // die;
                return $this->redirect(['foursizthanks']);
            }
        } else {
            return $this->render('/book/refund.twig', [
                'model' => $model, 'event_id' => $event_id
            ]);
        }
    }

    public function actionTepperjazzfest()
    {

        $model = new Bookingapi();

        $events = Events::find()
            ->where(['event_id' => 1138])->andwhere('DATE(date) >= DATE(NOW())')
            ->orderBy(['date' => SORT_DESC])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 1;
            if ($model->save()) {
                return $this->redirect(['thanks']);
            } else {

                // echo "<pre>";
                // print_r($model);
                // echo "</pre>";
                // die;
                return $this->redirect(['thanks']);
            }
        } else {
            return $this->render('/book/form/tepperjazzfest.twig', [
                'model' => $model, 'events' => $events
            ]);
        }
    }

    public function actionNeoclassicfest()
    {

        $model = new Bookingapi();

        $events = Events::find()
            ->where(['event_id' => 164])->andwhere('DATE(date) >= DATE(NOW())')
            ->orderBy(['date' => SORT_DESC])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 1;
            if ($model->save()) {
                return $this->redirect(['thanks']);
            } else {

                // echo "<pre>";
                // print_r($model);
                // echo "</pre>";
                // die;
                return $this->redirect(['thanks']);
            }
        } else {
            return $this->render('neoclassicfest.twig', [
                'model' => $model, 'events' => $events
            ]);
        }
    }

    public function actionTepperjazzfestthanks()
    {


        $model = new Bookingapi();
        $company = Companies::getCompany();

        // echo "<pre>";
        // print_r($model);
        // echo "</pre>";die;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tepperjazzfestthanks']);
        }

        return $this->render('tepperjazzfest_thanks.twig', ['model' => $model, 'company' => $company]);
    }


    public function actionCatsexh($event_id = false)
    {
        return $this->redirect(['exhcats']);
    }

    public function actionExhcats($event_id = false)
    {

        $model = new Bookingapi();
        $isimage = $model->image;
        $isimage1 = $model->image1;
        $isimage2 = $model->image2;
        $isimage3 = $model->image3;
        $isimage4 = $model->image4;
        $isimage5 = $model->image5;
        $isimage6 = $model->image6;
        $isimage7 = $model->image7;
        $isimage8 = $model->image8;
        $isimage9 = $model->image9;


        $events = Events::find()
            ->where(['event_id' => 1810])->andwhere('DATE(date) >= DATE(NOW())')
            ->orderBy(['date' => SORT_DESC])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 1;

            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = Yii::$app->storage->saveBookingFile($image, $model->company_id ?? 0);
            } else {
                $model->image = $isimage;
            }

            $image1 = UploadedFile::getInstance($model, 'image1');
            if ($image1) {
                $model->image1 = Yii::$app->storage->saveBookingFile($image1, $model->company_id ?? 0);
            } else {
                $model->image1 = $isimage1;
            }

            $image2 = UploadedFile::getInstance($model, 'image2');
            if ($image2) {
                $model->image2 = Yii::$app->storage->saveBookingFile($image2, $model->company_id ?? 0);
            } else {
                $model->image2 = $isimage2;
            }

            $image3 = UploadedFile::getInstance($model, 'image3');
            if ($image3) {
                $model->image3 = Yii::$app->storage->saveBookingFile($image3, $model->company_id ?? 0);
            } else {
                $model->image3 = $isimage3;
            }

            $image4 = UploadedFile::getInstance($model, 'image4');
            if ($image4) {
                $model->image4 = Yii::$app->storage->saveBookingFile($image4, $model->company_id ?? 0);
            } else {
                $model->image4 = $isimage4;
            }

            $image5 = UploadedFile::getInstance($model, 'image5');
            if ($image5) {
                $model->image5 = Yii::$app->storage->saveBookingFile($image5, $model->company_id ?? 0);
            } else {
                $model->image5 = $isimage5;
            }

            $image6 = UploadedFile::getInstance($model, 'image6');
            if ($image6) {
                $model->image6 = Yii::$app->storage->saveBookingFile($image6, $model->company_id ?? 0);
            } else {
                $model->image6 = $isimage6;
            }

            $image7 = UploadedFile::getInstance($model, 'image7');
            if ($image7) {
                $model->image7 = Yii::$app->storage->saveBookingFile($image7, $model->company_id ?? 0);
            } else {
                $model->image7 = $isimage7;
            }

            $image8 = UploadedFile::getInstance($model, 'image8');
            if ($image8) {
                $model->image8 = Yii::$app->storage->saveBookingFile($image8, $model->company_id ?? 0);
            } else {
                $model->image8 = $isimage8;
            }

            $image9 = UploadedFile::getInstance($model, 'image9');
            if ($image9) {
                $model->image9 = Yii::$app->storage->saveBookingFile($image9, $model->company_id ?? 0);
            } else {
                $model->image9 = $isimage9;
            }

            // echo "<pre>"; print_r($model); echo "</pre>"; die;

            if ($model->save()) {

                Vk::Send('Заявка на участие «[catsuniversemsk|Кошачая вселенная]».<br>' . $model->brand . ' / '
                    . $model->second_name . ' ' . $model->name . ' ' . $model->thirdname . ' ' . $model->phone . ' ' . $model->mail .
                    ((empty($model->image)) ? ' не прикрепил(а) фотографии. ' : '<br>Фото: https://igoevent.com/uploads/booking/' . $model->image) . '<br>Текст поста:<br>' . $model->message . '<br>' . $model->link_vk . '<br>' . $model->link_insta . '<br>' . $model->link_site, [90794]);
                return $this->redirect(['thanks']);
            } else {
                file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - Bookingapi test ') . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), FILE_APPEND);
                return $this->redirect(['foursizthanks']);
            }
        } else {

            $user = Users::findOne(Yii::$app->user->id) ?? Null;
            $person = Persons::findOne($user->person_id ?? Null);

            return $this->render('/book/form/exhcats.twig', [
                'model' => $model, 'events' => $events, 'event_id' => $event_id ?? '', 'person' => $person ?? ''
            ]);
        }
    }


    public function actionExhcatsspb($event_id = false)
    {
        $model = new Bookingapi();
        $isimage = $model->image;
        $isimage1 = $model->image1;
        $isimage2 = $model->image2;
        $isimage3 = $model->image3;
        $isimage4 = $model->image4;
        $isimage5 = $model->image5;
        $isimage6 = $model->image6;
        $isimage7 = $model->image7;
        $isimage8 = $model->image8;
        $isimage9 = $model->image9;

        $events = Events::find()
            ->where(['event_id' => 1883])->andwhere('DATE(date) >= DATE(NOW())')
            ->orderBy(['date' => SORT_DESC])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 1;

            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = Yii::$app->storage->saveBookingFile($image, $model->company_id ?? 0);
            } else {
                $model->image = $isimage;
            }

            $image1 = UploadedFile::getInstance($model, 'image1');
            if ($image1) {
                $model->image1 = Yii::$app->storage->saveBookingFile($image1, $model->company_id ?? 0);
            } else {
                $model->image1 = $isimage1;
            }

            $image2 = UploadedFile::getInstance($model, 'image2');
            if ($image2) {
                $model->image2 = Yii::$app->storage->saveBookingFile($image2, $model->company_id ?? 0);
            } else {
                $model->image2 = $isimage2;
            }

            $image3 = UploadedFile::getInstance($model, 'image3');
            if ($image3) {
                $model->image3 = Yii::$app->storage->saveBookingFile($image3, $model->company_id ?? 0);
            } else {
                $model->image3 = $isimage3;
            }

            $image4 = UploadedFile::getInstance($model, 'image4');
            if ($image4) {
                $model->image4 = Yii::$app->storage->saveBookingFile($image4, $model->company_id ?? 0);
            } else {
                $model->image4 = $isimage4;
            }

            $image5 = UploadedFile::getInstance($model, 'image5');
            if ($image5) {
                $model->image5 = Yii::$app->storage->saveBookingFile($image5, $model->company_id ?? 0);
            } else {
                $model->image5 = $isimage5;
            }

            $image6 = UploadedFile::getInstance($model, 'image6');
            if ($image6) {
                $model->image6 = Yii::$app->storage->saveBookingFile($image6, $model->company_id ?? 0);
            } else {
                $model->image6 = $isimage6;
            }

            $image7 = UploadedFile::getInstance($model, 'image7');
            if ($image7) {
                $model->image7 = Yii::$app->storage->saveBookingFile($image7, $model->company_id ?? 0);
            } else {
                $model->image7 = $isimage7;
            }

            $image8 = UploadedFile::getInstance($model, 'image8');
            if ($image8) {
                $model->image8 = Yii::$app->storage->saveBookingFile($image8, $model->company_id ?? 0);
            } else {
                $model->image8 = $isimage8;
            }

            $image9 = UploadedFile::getInstance($model, 'image9');
            if ($image9) {
                $model->image9 = Yii::$app->storage->saveBookingFile($image9, $model->company_id ?? 0);
            } else {
                $model->image9 = $isimage9;
            }

            // echo "<pre>"; print_r($model); echo "</pre>"; die;

            if ($model->save()) {

                Vk::Send('Заявка на участие «[catsuniversemsk|Кошачая вселенная]».<br>' . $model->brand . ' / '
                    . $model->second_name . ' ' . $model->name . ' ' . $model->thirdname . ' ' . $model->phone . ' ' . $model->mail .
                    ((empty($model->image)) ? ' не прикрепил(а) фотографии. ' : '<br>Фото: https://igoevent.com/uploads/booking/' . $model->image) . '<br>Текст поста:<br>' . $model->message . '<br>' . $model->link_vk . '<br>' . $model->link_insta . '<br>' . $model->link_site, [90794]);
                return $this->redirect(['thanks']);
            } else {
                file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - Bookingapi test ') . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), FILE_APPEND);
                return $this->redirect(['foursizthanks']);
            }
        } else {

            $user = Users::findOne(Yii::$app->user->id) ?? Null;
            $person = Persons::findOne($user->person_id ?? Null);

            return $this->render('/book/form/exhcats_spb.twig', [
                'model' => $model, 'events' => $events, 'event_id' => $event_id ?? '', 'person' => $person ?? ''
            ]);
        }
    }

    public function actionOpencalldj($event_id = false)
    {
        $model = new Bookingapi();
        $isimage = $model->image;
        $isimage1 = $model->image1;
        $isimage2 = $model->image2;
        $isimage3 = $model->image3;
        $isimage4 = $model->image4;
        $isimage5 = $model->image5;
        $isimage6 = $model->image6;
        $isimage7 = $model->image7;
        $isimage8 = $model->image8;
        $isimage9 = $model->image9;

        $events = Events::find()
            ->where(['event_id' => 1858])->andwhere('DATE(date) >= DATE(NOW())')
            ->orderBy(['date' => SORT_DESC])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 1;

            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = Yii::$app->storage->saveBookingFile($image, $model->company_id ?? 0);
            } else {
                $model->image = $isimage;
            }

            $image1 = UploadedFile::getInstance($model, 'image1');
            if ($image1) {
                $model->image1 = Yii::$app->storage->saveBookingFile($image1, $model->company_id ?? 0);
            } else {
                $model->image1 = $isimage1;
            }

            $image2 = UploadedFile::getInstance($model, 'image2');
            if ($image2) {
                $model->image2 = Yii::$app->storage->saveBookingFile($image2, $model->company_id ?? 0);
            } else {
                $model->image2 = $isimage2;
            }

            $image3 = UploadedFile::getInstance($model, 'image3');
            if ($image3) {
                $model->image3 = Yii::$app->storage->saveBookingFile($image3, $model->company_id ?? 0);
            } else {
                $model->image3 = $isimage3;
            }

            $image4 = UploadedFile::getInstance($model, 'image4');
            if ($image4) {
                $model->image4 = Yii::$app->storage->saveBookingFile($image4, $model->company_id ?? 0);
            } else {
                $model->image4 = $isimage4;
            }

            $image5 = UploadedFile::getInstance($model, 'image5');
            if ($image5) {
                $model->image5 = Yii::$app->storage->saveBookingFile($image5, $model->company_id ?? 0);
            } else {
                $model->image5 = $isimage5;
            }

            $image6 = UploadedFile::getInstance($model, 'image6');
            if ($image6) {
                $model->image6 = Yii::$app->storage->saveBookingFile($image6, $model->company_id ?? 0);
            } else {
                $model->image6 = $isimage6;
            }

            $image7 = UploadedFile::getInstance($model, 'image7');
            if ($image7) {
                $model->image7 = Yii::$app->storage->saveBookingFile($image7, $model->company_id ?? 0);
            } else {
                $model->image7 = $isimage7;
            }

            $image8 = UploadedFile::getInstance($model, 'image8');
            if ($image8) {
                $model->image8 = Yii::$app->storage->saveBookingFile($image8, $model->company_id ?? 0);
            } else {
                $model->image8 = $isimage8;
            }

            $image9 = UploadedFile::getInstance($model, 'image9');
            if ($image9) {
                $model->image9 = Yii::$app->storage->saveBookingFile($image9, $model->company_id ?? 0);
            } else {
                $model->image9 = $isimage9;
            }

            // echo "<pre>"; print_r($model); echo "</pre>"; die;

            if ($model->save()) {

                Vk::Send('Заявка на участие «[morningcofferravespb|утрений кофейный рейв]».<br>' . $model->brand . ' / '
                    . $model->second_name . ' ' . $model->name . ' ' . $model->thirdname . ' ' . $model->phone . ' ' . $model->mail .
                    ((empty($model->image)) ? ' не прикрепил(а) фотографии. ' : '<br>Фото: https://igoevent.com/uploads/booking/' . $model->image) . '<br>Текст поста:<br>' . $model->message . '<br>' . $model->link_vk . '<br>' . $model->link_insta . '<br>' . $model->link_site, [90794]);
                return $this->redirect(['thanks']);
            } else {
                file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - Bookingapi test ') . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), FILE_APPEND);
                return $this->redirect(['thanks']);
            }
        } else {
            $user = Users::findOne(Yii::$app->user->id) ?? Null;
            $person = Persons::findOne($user->person_id ?? Null);

            return $this->render('/book/form/opencalldj.twig', [
                'model' => $model, 'events' => $events, 'event_id' => $event_id ?? '', 'person' => $person ?? ''
            ]);
        }
    }

    public function actionVases()
    {
        $company = Companies::getCompany();
        $biblioevent_id = ['1810'];
        //$events = Form::addintable( $biblioevent_id );

        $model = new Bookingapi();
        $events = Events::find()
            ->where(['event_id' => $biblioevent_id])
            ->andwhere('DATE(date) >= DATE(NOW())')
            ->orderBy(['date' => SORT_DESC])
            ->all();

        $isimage = $model->image;
        $isimage1 = $model->image1;
        $isimage2 = $model->image2;
        $isimage3 = $model->image3;
        $isimage4 = $model->image4;
        $isimage5 = $model->image5;
        $isimage6 = $model->image6;
        $isimage7 = $model->image7;
        $isimage8 = $model->image8;
        $isimage9 = $model->image9;

        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 1;

            $events = Events::find()
                ->where(['event_id' => $biblioevent_id])
                ->andwhere('DATE(date) >= DATE(NOW())')
                ->orderBy(['date' => SORT_DESC])
                ->all();

            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = Yii::$app->storage->saveBookingFile($image, $model->company_id ?? 0);
            } else {
                $model->image = $isimage;
            }

            $image1 = UploadedFile::getInstance($model, 'image1');
            if ($image1) {
                $model->image1 = Yii::$app->storage->saveBookingFile($image1, $model->company_id ?? 0);
            } else {
                $model->image1 = $isimage1;
            }

            $image2 = UploadedFile::getInstance($model, 'image2');
            if ($image2) {
                $model->image2 = Yii::$app->storage->saveBookingFile($image2, $model->company_id ?? 0);
            } else {
                $model->image2 = $isimage2;
            }

            $image3 = UploadedFile::getInstance($model, 'image3');
            if ($image3) {
                $model->image3 = Yii::$app->storage->saveBookingFile($image3, $model->company_id ?? 0);
            } else {
                $model->image3 = $isimage3;
            }

            $image4 = UploadedFile::getInstance($model, 'image4');
            if ($image4) {
                $model->image4 = Yii::$app->storage->saveBookingFile($image4, $model->company_id ?? 0);
            } else {
                $model->image4 = $isimage4;
            }

            $image5 = UploadedFile::getInstance($model, 'image5');
            if ($image5) {
                $model->image5 = Yii::$app->storage->saveBookingFile($image5, $model->company_id ?? 0);
            } else {
                $model->image5 = $isimage5;
            }

            $image6 = UploadedFile::getInstance($model, 'image6');
            if ($image6) {
                $model->image6 = Yii::$app->storage->saveBookingFile($image6, $model->company_id ?? 0);
            } else {
                $model->image6 = $isimage6;
            }

            $image7 = UploadedFile::getInstance($model, 'image7');
            if ($image7) {
                $model->image7 = Yii::$app->storage->saveBookingFile($image7, $model->company_id ?? 0);
            } else {
                $model->image7 = $isimage7;
            }

            $image8 = UploadedFile::getInstance($model, 'image8');
            if ($image8) {
                $model->image8 = Yii::$app->storage->saveBookingFile($image8, $model->company_id ?? 0);
            } else {
                $model->image8 = $isimage8;
            }

            $image9 = UploadedFile::getInstance($model, 'image9');
            if ($image9) {
                $model->image9 = Yii::$app->storage->saveBookingFile($image9, $model->company_id ?? 0);
            } else {
                $model->image9 = $isimage9;
            }

            // echo "<pre>"; print_r($model); echo "</pre>"; die;

            if ($model->save()) {

                Vk::Send('Заявка на участие «[catsuniversemsk|Кошачая вселенная]».<br>' . $model->brand . ' / '
                    . $model->second_name . ' ' . $model->name . ' ' . $model->thirdname . ' ' . $model->phone . ' ' . $model->mail .
                    ((empty($model->image)) ? ' не прикрепил(а) фотографии. ' : '<br>Фото: https://igoevent.com/uploads/booking/' . $model->image) . '<br>Текст поста:<br>' . $model->message . '<br>' . $model->link_vk . '<br>' . $model->link_insta . '<br>' . $model->link_site, [90794]);
                return $this->redirect(['thanks']);
            } else {
                file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - Bookingapi test ') . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), FILE_APPEND);
                return $this->redirect(['foursizthanks']);
            }
        } else {

            $user = Users::findOne(Yii::$app->user->id) ?? Null;
            $person = Persons::findOne($user->person_id ?? Null);

            return $this->render('/book/form/exhcats.twig', [
                'model' => $model, 'events' => $events, 'event_id' => $event_id ?? '', 'person' => $person ?? ''
            ]);
        }


    }


    public function actionSteampunkmarket($event_id = false)
    {

        $model = new Bookingapi();
        $isimage = $model->image;
        $isimage1 = $model->image1;
        $isimage2 = $model->image2;
        $isimage3 = $model->image3;
        $isimage4 = $model->image4;
        $isimage5 = $model->image5;
        $isimage6 = $model->image6;
        $isimage7 = $model->image7;
        $isimage8 = $model->image8;
        $isimage9 = $model->image9;


        $events = Events::find()
            ->where(['event_id' => 1152])->andwhere('DATE(date) >= DATE(NOW())')
            ->orderBy(['date' => SORT_DESC])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 1;

            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = Yii::$app->storage->saveBookingFile($image, $model->company_id ?? 0);
            } else {
                $model->image = $isimage;
            }

            $image1 = UploadedFile::getInstance($model, 'image1');
            if ($image1) {
                $model->image1 = Yii::$app->storage->saveBookingFile($image1, $model->company_id ?? 0);
            } else {
                $model->image1 = $isimage1;
            }

            $image2 = UploadedFile::getInstance($model, 'image2');
            if ($image2) {
                $model->image2 = Yii::$app->storage->saveBookingFile($image2, $model->company_id ?? 0);
            } else {
                $model->image2 = $isimage2;
            }

            $image3 = UploadedFile::getInstance($model, 'image3');
            if ($image3) {
                $model->image3 = Yii::$app->storage->saveBookingFile($image3, $model->company_id ?? 0);
            } else {
                $model->image3 = $isimage3;
            }

            $image4 = UploadedFile::getInstance($model, 'image4');
            if ($image4) {
                $model->image4 = Yii::$app->storage->saveBookingFile($image4, $model->company_id ?? 0);
            } else {
                $model->image4 = $isimage4;
            }

            $image5 = UploadedFile::getInstance($model, 'image5');
            if ($image5) {
                $model->image5 = Yii::$app->storage->saveBookingFile($image5, $model->company_id ?? 0);
            } else {
                $model->image5 = $isimage5;
            }

            $image6 = UploadedFile::getInstance($model, 'image6');
            if ($image6) {
                $model->image6 = Yii::$app->storage->saveBookingFile($image6, $model->company_id ?? 0);
            } else {
                $model->image6 = $isimage6;
            }

            $image7 = UploadedFile::getInstance($model, 'image7');
            if ($image7) {
                $model->image7 = Yii::$app->storage->saveBookingFile($image7, $model->company_id ?? 0);
            } else {
                $model->image7 = $isimage7;
            }

            $image8 = UploadedFile::getInstance($model, 'image8');
            if ($image8) {
                $model->image8 = Yii::$app->storage->saveBookingFile($image8, $model->company_id ?? 0);
            } else {
                $model->image8 = $isimage8;
            }

            $image9 = UploadedFile::getInstance($model, 'image9');
            if ($image9) {
                $model->image9 = Yii::$app->storage->saveBookingFile($image9, $model->company_id ?? 0);
            } else {
                $model->image9 = $isimage9;
            }

            // echo "<pre>"; print_r($model); echo "</pre>"; die;

            if ($model->save()) {

                Vk::Send('Заявка на участие «[catsuniversemsk|Кошачая вселенная]».<br>' . $model->brand . ' / '
                    . $model->second_name . ' ' . $model->name . ' ' . $model->thirdname . ' ' . $model->phone . ' ' . $model->mail .
                    ((empty($model->image)) ? ' не прикрепил(а) фотографии. ' : '<br>Фото: https://igoevent.com/uploads/booking/' . $model->image) . '<br>Текст поста:<br>' . $model->message . '<br>' . $model->link_vk . '<br>' . $model->link_insta . '<br>' . $model->link_site, [90794]);
                return $this->redirect(['thanks']);
            } else {
                file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - Bookingapi test ') . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), FILE_APPEND);
                return $this->redirect(['foursizthanks']);
            }
        } else {

            $user = Users::findOne(Yii::$app->user->id) ?? Null;
            $person = Persons::findOne($user->person_id ?? Null);

            return $this->render('/book/form/steampunkmarket.twig', [
                'model' => $model, 'events' => $events, 'event_id' => $event_id ?? '', 'person' => $person ?? ''
            ]);
        }
    }

    public function actionExhdogs($event_id = false)
    {
        $model = new Bookingapi();
        $isimage = $model->image;
        $isimage1 = $model->image1;
        $isimage2 = $model->image2;
        $isimage3 = $model->image3;
        $isimage4 = $model->image4;
        $isimage5 = $model->image5;
        $isimage6 = $model->image6;
        $isimage7 = $model->image7;
        $isimage8 = $model->image8;
        $isimage9 = $model->image9;

        $events = Events::find()
            ->where(['event_id' => 1860])->andwhere('DATE(date) >= DATE(NOW())')
            ->orderBy(['date' => SORT_DESC])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 1;

            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = Yii::$app->storage->saveBookingFile($image, $model->company_id ?? 0);
            } else {
                $model->image = $isimage;
            }

            $image1 = UploadedFile::getInstance($model, 'image1');
            if ($image1) {
                $model->image1 = Yii::$app->storage->saveBookingFile($image1, $model->company_id ?? 0);
            } else {
                $model->image1 = $isimage1;
            }

            $image2 = UploadedFile::getInstance($model, 'image2');
            if ($image2) {
                $model->image2 = Yii::$app->storage->saveBookingFile($image2, $model->company_id ?? 0);
            } else {
                $model->image2 = $isimage2;
            }

            $image3 = UploadedFile::getInstance($model, 'image3');
            if ($image3) {
                $model->image3 = Yii::$app->storage->saveBookingFile($image3, $model->company_id ?? 0);
            } else {
                $model->image3 = $isimage3;
            }

            $image4 = UploadedFile::getInstance($model, 'image4');
            if ($image4) {
                $model->image4 = Yii::$app->storage->saveBookingFile($image4, $model->company_id ?? 0);
            } else {
                $model->image4 = $isimage4;
            }

            $image5 = UploadedFile::getInstance($model, 'image5');
            if ($image5) {
                $model->image5 = Yii::$app->storage->saveBookingFile($image5, $model->company_id ?? 0);
            } else {
                $model->image5 = $isimage5;
            }

            $image6 = UploadedFile::getInstance($model, 'image6');
            if ($image6) {
                $model->image6 = Yii::$app->storage->saveBookingFile($image6, $model->company_id ?? 0);
            } else {
                $model->image6 = $isimage6;
            }

            $image7 = UploadedFile::getInstance($model, 'image7');
            if ($image7) {
                $model->image7 = Yii::$app->storage->saveBookingFile($image7, $model->company_id ?? 0);
            } else {
                $model->image7 = $isimage7;
            }

            $image8 = UploadedFile::getInstance($model, 'image8');
            if ($image8) {
                $model->image8 = Yii::$app->storage->saveBookingFile($image8, $model->company_id ?? 0);
            } else {
                $model->image8 = $isimage8;
            }

            $image9 = UploadedFile::getInstance($model, 'image9');
            if ($image9) {
                $model->image9 = Yii::$app->storage->saveBookingFile($image9, $model->company_id ?? 0);
            } else {
                $model->image9 = $isimage9;
            }

            // echo "<pre>"; print_r($model); echo "</pre>"; die;

            if ($model->save()) {

                Vk::Send('Заявка на участие «[catsuniversemsk|Кошачая вселенная]».<br>' . $model->brand . ' / '
                    . $model->second_name . ' ' . $model->name . ' ' . $model->thirdname . ' ' . $model->phone . ' ' . $model->mail .
                    ((empty($model->image)) ? ' не прикрепил(а) фотографии. ' : '<br>Фото: https://igoevent.com/uploads/booking/' . $model->image) . '<br>Текст поста:<br>' . $model->message . '<br>' . $model->link_vk . '<br>' . $model->link_insta . '<br>' . $model->link_site, [90794]);
                return $this->redirect(['thanks']);
            } else {
                file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - Bookingapi test ') . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), FILE_APPEND);
                return $this->redirect(['foursizthanks']);
            }
        } else {

            $user = Users::findOne(Yii::$app->user->id) ?? Null;
            $person = Persons::findOne($user->person_id ?? Null);

            return $this->render('/book/form/exhdogs.twig', [
                'model' => $model, 'events' => $events, 'event_id' => $event_id ?? '', 'person' => $person ?? ''
            ]);
        }
    }

    public function actionExhflowers($event_id = false)
    {

        $model = new Bookingapi();
        $isimage = $model->image;
        $isimage1 = $model->image1;
        $isimage2 = $model->image2;
        $isimage3 = $model->image3;
        $isimage4 = $model->image4;
        $isimage5 = $model->image5;
        $isimage6 = $model->image6;
        $isimage7 = $model->image7;
        $isimage8 = $model->image8;
        $isimage9 = $model->image9;

        $events = Events::find()
            ->where(['event_id' => 1854])->andwhere('DATE(date) >= DATE(NOW())')
            ->orderBy(['date' => SORT_DESC])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 1;

            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = Yii::$app->storage->saveBookingFile($image, $model->company_id ?? 0);
            } else {
                $model->image = $isimage;
            }

            $image1 = UploadedFile::getInstance($model, 'image1');
            if ($image1) {
                $model->image1 = Yii::$app->storage->saveBookingFile($image1, $model->company_id ?? 0);
            } else {
                $model->image1 = $isimage1;
            }

            $image2 = UploadedFile::getInstance($model, 'image2');
            if ($image2) {
                $model->image2 = Yii::$app->storage->saveBookingFile($image2, $model->company_id ?? 0);
            } else {
                $model->image2 = $isimage2;
            }

            $image3 = UploadedFile::getInstance($model, 'image3');
            if ($image3) {
                $model->image3 = Yii::$app->storage->saveBookingFile($image3, $model->company_id ?? 0);
            } else {
                $model->image3 = $isimage3;
            }

            $image4 = UploadedFile::getInstance($model, 'image4');
            if ($image4) {
                $model->image4 = Yii::$app->storage->saveBookingFile($image4, $model->company_id ?? 0);
            } else {
                $model->image4 = $isimage4;
            }

            $image5 = UploadedFile::getInstance($model, 'image5');
            if ($image5) {
                $model->image5 = Yii::$app->storage->saveBookingFile($image5, $model->company_id ?? 0);
            } else {
                $model->image5 = $isimage5;
            }

            $image6 = UploadedFile::getInstance($model, 'image6');
            if ($image6) {
                $model->image6 = Yii::$app->storage->saveBookingFile($image6, $model->company_id ?? 0);
            } else {
                $model->image6 = $isimage6;
            }

            $image7 = UploadedFile::getInstance($model, 'image7');
            if ($image7) {
                $model->image7 = Yii::$app->storage->saveBookingFile($image7, $model->company_id ?? 0);
            } else {
                $model->image7 = $isimage7;
            }

            $image8 = UploadedFile::getInstance($model, 'image8');
            if ($image8) {
                $model->image8 = Yii::$app->storage->saveBookingFile($image8, $model->company_id ?? 0);
            } else {
                $model->image8 = $isimage8;
            }

            $image9 = UploadedFile::getInstance($model, 'image9');
            if ($image9) {
                $model->image9 = Yii::$app->storage->saveBookingFile($image9, $model->company_id ?? 0);
            } else {
                $model->image9 = $isimage9;
            }

            // echo "<pre>"; print_r($model); echo "</pre>"; die;

            if ($model->save()) {

                Vk::Send('Заявка на участие «[exhflowers|Выстава цветов]».<br>' . $model->brand . ' / '
                    . $model->second_name . ' ' . $model->name . ' ' . $model->thirdname . ' ' . $model->phone . ' ' . $model->mail .
                    ((empty($model->image)) ? ' не прикрепил(а) фотографии. ' : '<br>Фото: https://igoevent.com/uploads/booking/' . $model->image) . '<br>Текст поста:<br>' . $model->message . '<br>' . $model->link_vk . '<br>' . $model->link_insta . '<br>' . $model->link_site, [90794]);

                return $this->redirect(['thanks']);
            } else {
                file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - Bookingapi test ') . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), FILE_APPEND);
                return $this->redirect(['foursizthanks']);
            }
        } else {

            $user = Users::findOne(Yii::$app->user->id) ?? Null;
            $person = Persons::findOne($user->person_id ?? Null);

            return $this->render('/book/form/exhflowers.twig', [
                'model' => $model, 'events' => $events, 'event_id' => $event_id ?? '', 'person' => $person ?? ''
            ]);
        }
    }


    public function actionCommfest($event_id = false)
    {

        $model = new Bookingapi();

        $events = Events::find()
            ->where(['event_id' => 1893])->andwhere('DATE(date) >= DATE(NOW())')
            ->orderBy(['date' => SORT_DESC])
            ->all();
        $booking = Booking::find()
            ->where('booking.id = :city', [':city' => '4'])
            ->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 1;

            if ($model->save()) {

                Vk::Send('Заявка на участие CommFest.<br>' . $model->brand . '<br>'
                    . $model->second_name . ' ' . $model->name . ' ' . $model->thirdname . ' ' . $model->phone . ' ' . $model->mail .
                    '<br>Назв.: ' . $model->brand .
                    '<br>Как: ' . $model->message .
                    '<br>Телеграм: ' . $model->link_tg .
                    '<br>Макс. кол-во: ' . $model->price .
                    '<br>Сколько раз: ' . $model->info_cat .
                    '<br>Соц. сети: ' . $model->info_wish .
                    '<br>Публикации: ' . $model->info_job .
                    '<br>Фото: ' . $model->info_goal, [9570368]);

                return $this->redirect(['thanks']);
            } else {
                file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - Bookingapi test ') . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), FILE_APPEND);
                return $this->redirect(['foursizthanks']);
            }
        } else {

            $user = Users::findOne(Yii::$app->user->id) ?? Null;
            $person = Persons::findOne($user->person_id ?? Null);

            return $this->render('/book/personal_comm_fest.twig', [
                'model' => $model, 'events' => $events, 'event_id' => $event_id ?? '', 'booking' => $booking, 'person' => $person ?? ''
            ]);
        }
    }

    public function actionMarketcats($event_id = false)
    {

        $model = new Bookingapi();
        $isimage = $model->image;
        $isimage1 = $model->image1;
        $isimage2 = $model->image2;
        $isimage3 = $model->image3;
        $isimage4 = $model->image4;
        $isimage5 = $model->image5;
        $isimage6 = $model->image6;
        $isimage7 = $model->image7;
        $isimage8 = $model->image8;
        $isimage9 = $model->image9;


        $events = Events::find()
            ->where(['event_id' => 1810])->andwhere('DATE(date) >= DATE(NOW())')
            ->orderBy(['date' => SORT_DESC])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 1;

            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $model->image = Yii::$app->storage->saveBookingFile($image, $model->company_id ?? 0);
            } else {
                $model->image = $isimage;
            }

            $image1 = UploadedFile::getInstance($model, 'image1');
            if ($image1) {
                $model->image1 = Yii::$app->storage->saveBookingFile($image1, $model->company_id ?? 0);
            } else {
                $model->image1 = $isimage1;
            }

            $image2 = UploadedFile::getInstance($model, 'image2');
            if ($image2) {
                $model->image2 = Yii::$app->storage->saveBookingFile($image2, $model->company_id ?? 0);
            } else {
                $model->image2 = $isimage2;
            }

            $image3 = UploadedFile::getInstance($model, 'image3');
            if ($image3) {
                $model->image3 = Yii::$app->storage->saveBookingFile($image3, $model->company_id ?? 0);
            } else {
                $model->image3 = $isimage3;
            }

            $image4 = UploadedFile::getInstance($model, 'image4');
            if ($image4) {
                $model->image4 = Yii::$app->storage->saveBookingFile($image4, $model->company_id ?? 0);
            } else {
                $model->image4 = $isimage4;
            }

            $image5 = UploadedFile::getInstance($model, 'image5');
            if ($image5) {
                $model->image5 = Yii::$app->storage->saveBookingFile($image5, $model->company_id ?? 0);
            } else {
                $model->image5 = $isimage5;
            }

            $image6 = UploadedFile::getInstance($model, 'image6');
            if ($image6) {
                $model->image6 = Yii::$app->storage->saveBookingFile($image6, $model->company_id ?? 0);
            } else {
                $model->image6 = $isimage6;
            }

            $image7 = UploadedFile::getInstance($model, 'image7');
            if ($image7) {
                $model->image7 = Yii::$app->storage->saveBookingFile($image7, $model->company_id ?? 0);
            } else {
                $model->image7 = $isimage7;
            }

            $image8 = UploadedFile::getInstance($model, 'image8');
            if ($image8) {
                $model->image8 = Yii::$app->storage->saveBookingFile($image8, $model->company_id ?? 0);
            } else {
                $model->image8 = $isimage8;
            }

            $image9 = UploadedFile::getInstance($model, 'image9');
            if ($image9) {
                $model->image9 = Yii::$app->storage->saveBookingFile($image9, $model->company_id ?? 0);
            } else {
                $model->image9 = $isimage9;
            }

            // echo "<pre>"; print_r($model); echo "</pre>"; die;

            if ($model->save()) {

                Vk::Send('Заявка на участие на котостоле.<br>' . $model->brand . ' / '
                    . $model->second_name . ' ' . $model->name . ' ' . $model->thirdname . ' ' . $model->phone . ' ' . $model->mail .
                    ((empty($model->image)) ? ' не прикрепил(а) фотографии. ' : '<br>Фото: https://igoevent.com/uploads/booking/' . $model->image) . '<br>Текст поста:<br>' . $model->message . '<br>' . $model->link_vk . '<br>' . $model->link_insta . '<br>' . $model->link_site, [90794]);
                return $this->redirect(['thanks']);
            } else {
                file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - Bookingapi test ') . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), FILE_APPEND);
                return $this->redirect(['foursizthanks']);
            }
        } else {

            $user = Users::findOne(Yii::$app->user->id) ?? Null;
            $person = Persons::findOne($user->person_id ?? Null);

            return $this->render('/book/form/marketcats.twig', [
                'model' => $model, 'events' => $events, 'event_id' => $event_id ?? '', 'person' => $person ?? ''
            ]);
        }
    }

    public function actionThanks()
    {
        $model = new Bookingapi();
        $company = Companies::getCompany();

        if ($model->load(Yii::$app->request->post())) {

            $imageName = date("y-m-d-h-i-s") . $model->event_id;
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->file->saveAs("uploads/foursiz/" . $imageName . '00.' . $model->file->extension);
            $model->image = "uploads/foursiz/" . $imageName . '00.' . $model->file->extension;

            $model->save();
            return $this->redirect(['thanks']);
        }

        return $this->render('/book/form/thanks.twig', ['model' => $model, 'company' => $company ?? Null]);
    }


    public function actionLanding($city, $alias, $event_id = false)
    {
        //$this->layout='page-landing';
        $model = new Bookingapi();

        // Валидация формы
        if ($model->load(Yii::$app->request->post())) {
            $formS = new Form();
            $result = $formS->join($model);
            
            if ($model->vk_id) {

                Vk::Send('Заявка на участие CommFest.<br>' . $model->brand . '<br>'
                    . $model->second_name . ' ' . $model->name . ' ' . $model->thirdname . ' ' . $model->phone . ' ' . $model->mail .
                    '<br>Назв.: ' . $model->brand .
                    '<br>Как: ' . $model->message .
                    '<br>Телеграм: ' . $model->link_tg .
                    '<br>Макс. кол-во: ' . $model->price .
                    '<br>Сколько раз: ' . $model->info_cat .
                    '<br>Соц. сети: ' . $model->info_wish .
                    '<br>Публикации: ' . $model->info_job .
                    '<br>Фото: ' . $model->info_goal, $model->vk_id );

                return $this->redirect(['thanks?test=test']);
            } else {
                file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - Bookingapi test ') . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), FILE_APPEND);
                return $this->redirect(['thanks']);
            }
            

            if ($result) {
                Vk::Send('Заявка: <br>' . $model->brand . ' / '
                    . $model->second_name . ' ' . $model->name . ' ' . $model->thirdname . ' ' . $model->phone . ' ' . $model->mail .
                    ((empty($model->image)) ? ' не прикрепил(а) фотографии. ' : '<br>Фото: https://igoevent.com/uploads/booking/' . $model->image) . '<br>Текст поста:<br>' . $model->message . '<br>' . $model->link_vk . '<br>' . $model->link_insta . '<br>' . $model->link_site, $model->vk_id );
                return $this->redirect(['thanks']);
            } else {
                file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - Bookingapi test ') . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), FILE_APPEND);
                return $this->redirect(['thanks']);
            }
        } else {

            $city = Cities::find()->where('cities.alias = :a', [':a' => $city])->one();
            if (empty($city)) {
                return $this->render('404.twig');
            }
            if (is_numeric($alias)) {
                $booking = Booking::find()
                    ->where('booking.city = :city', [':city' => $city->id])
                    ->andWhere('booking.id = :id', [':id' => $alias])
                    ->one();
            } else {
                $booking = Booking::find()
                    ->where('booking.city = :city', [':city' => $city->id])
                    ->andWhere('booking.alias = :alias', [':alias' => $alias])
                    ->one();
            }

            if (empty($booking)) {
                return $this->render('../site/404.twig');
            }

            if (empty($booking->biblioevent_id)) {

            } else {
                $biblioevent = Biblioevents::find()
                    ->joinWith([
                        'events' => function ($query) {
                            $query->onCondition('DATE(events.date) >= DATE(NOW())');
                        }])
                    ->joinWith('places')
                    ->joinWith('img')
                    ->joinWith('landing')
                    ->joinWith('sections')
                    ->joinwith('posts')
                    ->joinWith('company')
                    ->andWhere('biblioevents.id = :id', [':id' => $booking->biblioevent_id])
                    ->one();
            }

            $iuser = Yii::$app->user->identity;

            $qrurl = 'https://igoevent.com/' . $city->alias . '/book/' . $alias . '?utm_source=partner&utm_medium=' . (($iuser) ? $iuser->utm_medium : 'igoevent') . '&utm_campaign=qr&utm_content=' . $alias . '&utm_term=';

            $qrCode = (new QrCode($qrurl))
                ->setSize(250)
                ->setMargin(5)
                ->useForegroundColor(70, 10, 90);
            $qr = $qrCode->writeDataUri();

            $landing_template = 'booking.twig';

            if ($booking->alias === 'comm-fest') { // костыль

                $landing_template = 'personal_comm_fest.twig';
            }

            $events = Events::find()
                ->where(['biblioevent_id' => $booking->biblioevent_id ?? Null
                ])->andwhere('DATE(date) >= DATE(NOW())')
                ->orderBy(['date' => SORT_DESC])
                ->all();

            $user = Users::findOne(Yii::$app->user->id) ?? Null;
            $person = Persons::findOne($user->person_id ?? Null);

            // echo "<pre>";
            // print_r($events);
            // echo "</pre>";die;

            return $this->render($landing_template, ['booking' => $booking ?? Null, 'biblioevent' => $biblioevent ?? Null, 'iuser' => $iuser, 'model' => $model, 'event_id' => $event_id ?? Null, 'qr' => $qr, 'city' => $city,'events' => $events, 'person' => $person,]);
        }
    }
}
