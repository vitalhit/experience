<?php

namespace app\components;

use Yii;
use app\models\Img;
use yii\base\Component;
use yii\web\UploadedFile;
use app\models\Companies;
use yii\helpers\FileHelper;
use app\models\Users;


/**
 * File storage compoment
 *
 * @author admin
 */
class Storage extends Component implements StorageInterface
{

    private $fileName;


    // Сохранить картинки для PLACE
    public function savePlaceFile(UploadedFile $file, int $id)
    {
        $name = sprintf('%05d', $id) . '_' . Yii::$app->security->generateRandomString(5) . '.' . $file->extension;
        $path = Yii::getAlias(Yii::$app->params['placePath']) . $name;
        $path = FileHelper::normalizePath($path);

        if ($path && $file->saveAs($path)) {
            Img::crop1200(Yii::getAlias(Yii::$app->params['placePath']), $name);
            return $name;
        }
    }

    // Сохранить картинки для BOOKING
    public function saveBookingFile(UploadedFile $file, int $id)
    {
        $name = sprintf('%05d', $id) . '_' . Yii::$app->security->generateRandomString(5) . '.' . $file->extension;
        $path = Yii::getAlias(Yii::$app->params['bookingPath']) . $name;
        $path = FileHelper::normalizePath($path);

        if ($path && $file->saveAs($path)) {
            Img::crop1200(Yii::getAlias(Yii::$app->params['bookingPath']), $name);
            return $name;
        }
    }

    // Сохранить картинки для COMPANY
    public function saveCompanyFile(UploadedFile $file, int $id)
    {
        $name = $id . '_' . Yii::$app->security->generateRandomString(5) . '.' . $file->extension;
        $path = Yii::getAlias(Yii::$app->params['companyPath']) . $name;
        $path = FileHelper::normalizePath($path);

        if ($path && $file->saveAs($path)) {
            Img::crop1200(Yii::getAlias(Yii::$app->params['companyPath']), $name);
            return $name;
        }
    }

    // Сохранить картинки для IMG
    public function saveImgFile(UploadedFile $file, $id = False , $company = False)
    {   
        $company = Companies::getCompany();
        if ($id) {$name = sprintf('%05d', $company->id) . '_'. $id .'_' . Yii::$app->security->generateRandomString(5) . '.' . $file->extension;}
        else { $name = sprintf('%05d', $company->id).  '_' . Yii::$app->security->generateRandomString(5) . '.' . $file->extension; }
        $path = Yii::getAlias(Yii::$app->params['imgPath']) . $name;
        $path = FileHelper::normalizePath($path);

        if ($path && $file->saveAs($path)) {
            Img::crop1200(Yii::getAlias(Yii::$app->params['imgPath']), $name);
            return $name;
        }
    }

    // Сохранить картинки для landing
    public function saveLandingFile(UploadedFile $file)
    {
        $user = Users::findOne(Yii::$app->user->id);
        $name = 'landing' . '_'. date("Y") .'_'. $user->id .'_'. Yii::$app->security->generateRandomString(5) . '.' . $file->extension;
        $path = Yii::getAlias(Yii::$app->params['storagePath']) . $name;
        $path = FileHelper::normalizePath($path);

        if ($path && $file->saveAs($path)) {
            Img::crop1200(Yii::getAlias(Yii::$app->params['storagePath']), $name);
            return $name;
        }
    }


    /**
     * Save given UploadedFile instance to disk
     * @param UploadedFile $file
     * @return string|null
     */
    public function saveUploadedFile(UploadedFile $file)
    {
        $path = $this->preparePath($file); 

        if ($path && $file->saveAs($path)) {
            return $this->fileName;
        }
    }

    /**
     * Prepare path to save uploaded file
     * @param UploadedFile $file
     * @return string|null
     */
    protected function preparePath(UploadedFile $file)
    {
        $this->fileName = $this->getFileName($file);  
        //     0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg
        
        $path = $this->getStoragePath() . $this->fileName;  
        
        $path = FileHelper::normalizePath($path);
        if (FileHelper::createDirectory(dirname($path))) {
            return $path;
        }
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function getFilename(UploadedFile $file)
    {
        // $file->tempname   -   /tmp/qio93kf

        // $hash = sha1_file($file->tempName); // 0ca9277f91e40054767f69afeb0426711ca0fddd

        // $name = substr_replace($hash, '/', 2, 0);
        // $name = substr_replace($name, '/', 5, 0);  // 0c/a9/277f91e40054767f69afeb0426711ca0fddd

        //file_put_contents('test.txt', PHP_EOL . Date('d.m.Y H:i:s - имя ').json_encode($file->name), FILE_APPEND);        
        //file_put_contents('test.txt', PHP_EOL . Date('d.m.Y H:i:s - темп имя ').json_encode($file->tempName), FILE_APPEND);        

        return $file->name . '.' . $file->extension;  // 0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg
    }

    /**
     * @return string
     */
    protected function getStoragePath()
    {
        return Yii::getAlias(Yii::$app->params['storagePath']);
    }

    /**
     * 
     * @param string $filename
     * @return string
     */
    public function getFile(string $filename)
    {
        return Yii::$app->params['storageUri'].$filename;
    }
}
