<?php 
use yii\bootstrap\Nav;
?>
<?php
    $menuItems = [];
    
    $menuItems[] = [
        'label' => '<i class="fa fa-lg fa-fw fa-home sub_icon"></i><span>Dashboard</span>', 
        'url' => ['/site/index']
    ];

    $menuItems[] = [
        'label' => '<i class="fa fa-lg fa-fw fa-plane sub_icon"></i><span>Proof of Flight</span>', 
        'url' => ['/flight/index']
    ];

    $menuItems[] = [
        'label' => '<i class="fa fa-lg fa-fw fa-users sub_icon"></i><span>Competitor Analysis</span>', 
        'url' => ['/competitor/index']
    ];
    $menuItems[] = [
        'label' => '<i class="fa fa-lg fa-fw fa-user sub_icon"></i><span>Account Settings</span>', 
        'url' => ['/user/settings/account']
    ];
    if(Yii::$app->user->identity->isAdmin){
        $menuItems[] = [
            'label' => '<i class="fa fa-lg fa-fw fa-cog sub_icon"></i><span>Manage Users</span>', 
            'url' => ['/user/admin/index']
        ]; 
    }
    $menuItems[] = [
        'label' => '<i class="fa fa-lg fa-fw fa-sign-out sub_icon"></i><span>Logout</span>',
        'url' => ['/user/security/logout'],
        'linkOptions' => ['data-method' => 'post']
    ];
    $menuItems[] = [
        'label' => '<i id="fa-toggle" class="fa fa-lg fa-fw fa-angle-double-left sub_icon"></i><span>Toggle</span>',
        'options'=>['id'=>'menu-toggle'], 
        'url' => '#'
    ];

    echo Nav::widget([
        'options' => ['class' => 'sidebar-nav'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);
?>
