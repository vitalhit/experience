<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadImage extends Model{

    public $image;

    public function rules(){
        return[
        [['image'], 'file', 'extensions' => 'png, jpg, pdf, doc, jpeg, docx, xls, xlsx, numbers'],
        ];
    }

    public function upload(){
        if($this->validate()){
            $this->image->saveAs("uploads/{$this->image->baseName}.{$this->image->extension}");
        }else{
            return false;
        }
    }

}