<?php

namespace app\controllers\user;

use dektrium\user\controllers\SecurityController as BaseSecurityController;

/**
 * Controller that manages user authentication process.
 *
 * @property Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SecurityController extends BaseSecurityController
{
    public $layout = '@app/views/layouts/login';
}