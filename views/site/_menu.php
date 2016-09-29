<?php 
use yii\bootstrap\Nav;
?>
<?php
    $menuItems = [];
    
    $menuItems[] = [
        'label' => '<i class="fa fa-lg fa-dashboard sub_icon"></i> Dashboard', 
        'url' => ['/site/index']
    ];

    $menuItems[] = [
        'label' => '<i class="fa fa-lg fa-music sub_icon"></i> Proof of Flight', 
        'url' => ['/site/track-list']
    ];

    $menuItems[] = [
        'label' => '<i class="fa fa-lg fa-book sub_icon"></i> Completitor Analysis', 
        'url' => ['/album/index']
    ];
    $menuItems[] = [
        'label' => '<i class="fa fa-lg fa-music sub_icon"></i> Reconciliation Log', 
        'url' => ['/works/index']
    ];
    $menuItems[] = [
        'label' => '<i class="fa fa-lg fa-cog sub_icon"></i> '.Yii::t('user', 'Account Settings'), 
        'url' => ['/user/settings/account']
    ];
    $menuItems[] = [
        'label' => '<i id="fa-toggle" class="fa fa-lg fa-angle-double-left sub_icon"></i> Toggle',
        'options'=>['id'=>'menu-toggle'], 
        'url' => '#'
    ];

    echo Nav::widget([
        'options' => ['class' => 'sidebar-nav'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);
?>
