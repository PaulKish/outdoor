<?php 
	use yii\helpers\Html;
	$this->title = 'Photo';
?>
<?= Html::img(Yii::$app->params['imageUrl'].$photo,['class'=>'img-responsive img-center']) ;?>