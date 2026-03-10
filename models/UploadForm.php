<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;
    public $date;
    public $task_id;
    public $info;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, pdf, doc, docx, xls, xlsx, ppt, pptx, numbers, jpeg', 'maxFiles' => 6, 'checkExtensionByMimeType' => false],
        ];
    }
    
    public function upload()
    {
        $comp_id = Companies::getCompanyId();
        if ($this->validate()) { 
            foreach ($this->imageFiles as $file) {
                $rand = Yii::$app->getSecurity()->generateRandomString(5);
                $file->saveAs('uploads/docs/' . $comp_id . '_' . $rand . '_' . $file->name);
                $doc = new Docs();
                $doc->company_id = $comp_id;
                $doc->user_id = Yii::$app->user->id;
                $doc->date = $this->date;
                $doc->task_id = $this->task_id;
                $doc->info = $this->info;
                $doc->image = $comp_id . '_' . $rand . '_' . $file->name;
                $doc->save();
            }
            return true;
        } else {
            return false;
        }
    }
}