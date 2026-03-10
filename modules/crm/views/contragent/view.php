<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Contragent */

$this->title = $model->name;
?>


<div class="row">
    <div class="col-xs-12 col-lg-12">
        <div class="newblo">
            <h2><?= Html::encode($this->title) ?>
                <sup>
                <?php echo '<a href="/crm/finance/create?price=15000&date_payment=now&from_contragent='.$model->id.'&to_contragent=237&biblioevent_id=158&state=3&status=1&name=Предоставление выставочного места на маркете «4 Сезона», Москва.">+fs/ХП</a>
                <a href="/crm/finance/create?price=5000&date_payment=now&from_contragent=237&to_contragent='.$model->id.'&biblioevent_id=158&state=3&status=3&name=Возврат денежных средств. НДС не облагается.">-fs/ХП</a>';
                ?>
            </sup></h2> 
        </div>
    </div>
    <div class="col-xs-12 col-lg-3 left_blo">
        <div class="steps">
        <?= $this->render('/module/finance_step.twig') ?>
        </div>
    </div>
    <div class="col-xs-12 col-lg-9 newblos">
        <table class="tb stacktable"><tr>
            <th>id</th>
            <th>name</th>
            <th>price</th>
            <th>Docs</th>
            <th>логистика</th>
        </tr>
        <?php foreach ($docst as $doc) {
             echo '<tr><td>'.$doc->id.'</td><td><a href="https://igoevent.com/crm/finance/view?id='.$doc->id.'">'.$doc->name.'</a><br>'.$doc->from_contragent.'->'.$doc->to_contragent.'</td>';
             echo '<td><a class="btn btn-success btn-xs" href="/crm/finance/akt?&id='.$doc->id.'">акт</a>'.$doc->act_number.'от'.date("Y-m-d",strtotime($doc->date)).' <br> <a class="btn btn-success btn-xs"href="/crm/finance/bill?&id='.$doc->id.'">счет</a> <a  class="btn btn-primary btn-xs" href="'.$doc->link.'">Договор</a></td><td>-'.(($doc->state == "3") ? $doc->summa : '<s>'.$doc->summa.'</s>').'</td>';

             echo '<td>';
             if ($doc->logistics == 0 and $doc->logistics_our == 0){ echo  '<a class="btn btn-success btn-xs"> не требуются </a>';  }
             if ( 0 < $doc->logistics and $doc->logistics < 7){ 
                echo  '<a class="btn btn-danger btn-xs"> им: требуются </a>';  }

             if (0 < $doc->logistics_our and $doc->logistics_our < 7){
                 echo  '<a class="btn btn-danger btn-xs"> нам: требуются </a>';  }
             
             if ($doc->logistics == 7 and ( $doc->logistics_our == 7 or $doc->logistics_our == 0) ){ echo  '<a class="btn btn-success btn-xs"> Done </a>'; }             
             echo '</tr></tr>'; 
             }
         ?>
         <?php foreach ($docsf as $doc) {
             echo '<tr><td>'.$doc->id.'<br><small>'.date("y-m-d",strtotime($doc->date)).'</small></td><td><a href="https://igoevent.com/crm/finance/view?id='.$doc->id.'">'.(($doc->state == -1)?'<s>'.$doc->name.'</s>':$doc->name).'</a><br>'.$doc->from_contragent.'->'.$doc->to_contragent.'</td>';
             echo '<td><a class="btn btn-success btn-xs" href="/crm/finance/akt?&id='.$doc->id.'">акт</a><small>'.$doc->act_number.'от'.date("Y-m-d",strtotime($doc->date)).'</small> <br><a class="btn btn-success btn-xs"href="/crm/finance/bill?&id='.$doc->id.'">счет</a> <a class="btn btn-success btn-xs" href="/doc?id='.$doc->id.'">Договор</a>';
             if ($doc->link){
                echo '<a class="btn btn-primary btn-xs" href="'.$doc->link.'">Ссылка</a>';
             }

             echo '</td><td>'.(($doc->state == "3") ? $doc->summa : '<s>'.$doc->summa.'</s>').'</td><td>';


             if ($doc->logistics == 0 and $doc->logistics_our == 0){ echo  '<a class="btn btn-success btn-xs"> не требуются </a>';  }
             if ( 0 < $doc->logistics and $doc->logistics < 7){ 
                echo  '<a class="btn btn-danger btn-xs"> им: требуются </a>';  }

             if (0 < $doc->logistics_our and $doc->logistics_our < 7){
                 echo  '<a class="btn btn-danger btn-xs"> нам: требуются </a>';  }
             
             if ($doc->logistics == 7 and ( $doc->logistics_our == 7 or $doc->logistics_our == 0) ){ echo  '<a class="btn btn-success btn-xs"> Done </a>'; }             
             echo '</tr></tr>'; 
             }
         ?>
        </table>
    </div>
    <div class="col-xs-12 col-lg-9 newblos">

        <h4>Реквизиты <?php echo $model->name; ?></h4>
       
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'company_id',
                'name',
                'inn',
                'kpp',
                'ogrn',
                'jaddress',
                'faddress',
                'man',
                'position',
                'nds',
                'bank',
                'bik',
                'korr',
                'raschet',
                'date',
                'status',
                'info:ntext',
            ],
        ]) ?>
         <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

    </div>
</div>
