<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\models\user;

use dektrium\user\models\User as UserBaseModel;
use app\models\UserAssignment;

class User extends UserBaseModel
{
    /**
     * @return bool Whether the user is confirmed or not.
     */
    public function getIsConfirmed()
    {
        return $this->confirmed_at != null;
    }

    /**
     * @return bool Whether the user is blocked or not.
     */
    public function getIsBlocked()
    {
        return $this->blocked_at != null;
    }

    /**
     * @return bool Whether the user is an admin or not.
     */
    public function getIsAdmin()
    {
        return
            (\Yii::$app->getAuthManager() && $this->module->adminPermission ?
                \Yii::$app->user->can($this->module->adminPermission) : false)
            || in_array($this->username, $this->module->admins);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return UserAssignment::findOne($this->id); 
    }

    /** @inheritdoc */
    public function getId()
    {
        return $this->getAttribute('id');
    }
}
