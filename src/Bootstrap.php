<?php
/**
 * @project  yii2-debug
 * @author   Phuong Dev <phuongdev89@gmail.com>
 * @datetime 3/9/2023 3:52 PM
 */

namespace phuongdev89\debug;

use yii\base\Application;
use yii\base\BootstrapInterface;

defined('BACKTRACE_TO_SELF') or define('BACKTRACE_TO_SELF', 'toSelf');
defined('BACKTRACE_TO_ARRAY') or define('BACKTRACE_TO_ARRAY', 'toArray');
defined('BACKTRACE_TO_JSON') or define('BACKTRACE_TO_JSON', 'toJson');
defined('BACKTRACE_TO_FILE') or define('BACKTRACE_TO_FILE', 'toFile');
defined('BACKTRACE_TO_ATTRIBUTE') or define('BACKTRACE_TO_ATTRIBUTE', 'toAttribute');


class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
    }
}
