<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="wrapper" class="active">
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <div class="profile-img">
            <?= Html::img('@web/img/placeholder.png',['width'=>'50px','class'=>'img-circle center-block']) ?>
            <h5 class="text-center text-uppercase"><?= Yii::$app->user->identity->username ?></h5>
            <h5 class="text-center text-uppercase"><small><?= isset(Yii::$app->user->identity->profile->company->company_name) ? Yii::$app->user->identity->profile->company->company_name : '' ?></small></h5>
        </div>
        <?= $this->render('@app/views/site/_menu') ?>
    </div>
    <!-- Page content -->
    <div id="page-content-wrapper">
        <!-- Keep all page content within the page-content inset div! -->
        <div class="page-content inset">
            <?php
            NavBar::begin([
                'brandLabel' => Html::img('@web/img/reelforge_logo',['height'=>'60px']),
                'brandUrl' => Yii::$app->homeUrl,
                'innerContainerOptions' => ['class'=>'container-fluid'],
                'options' => [
                    'class' => 'navbar-default',
                ],
            ]);
            
            $menuItems = [];

            $menuItems[] = [
                'label' => 'OutDoor', 
            ];

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
                'encodeLabels' => false,
            ]);
            NavBar::end();
            ?>

            <div class="container-fluid">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

                <?= $content ?>
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <p class="pull-left">&copy; ReelForge OutDoor <?= date('Y') ?></p>
                </div>
            </footer>
        </div>
    </div>
</div>

<?php 
    Modal::begin([
        'header' => '<h4>ReelForge OutDoor</h4>',
        'options'=>['id'=>'siteModal']
    ]);

    Modal::end();
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>