<?php
/**
 * @project  yii2-debug
 * @author   Phuong Dev <phuongdev89@gmail.com>
 * @datetime 3/9/2023 3:18 PM
 */

namespace phuongdev89\debug\db;

use Yii;
use yii\base\InvalidArgumentException;
use yii\db\ActiveRecordInterface;
use yii\helpers\Json;

class Backtrace
{
    /**
     * @var ActiveRecordInterface an existed active record
     */
    protected $existedObject;

    /**
     * @var array All traces of this action
     */
    private $traces;


    /**
     * Initialize the backtrace with an existed active record.
     * Default return itself
     *
     * Example:
     * if($model->save()) {
     *   Backtrace::init($model);
     * }
     *
     * @param ActiveRecordInterface $existedObject existed active record
     * @param string $destination
     * @return self|void
     *
     * @datetime 3/6/2023 3:26 PM
     * @author   Phuong Dev <phuongdev89@gmail.com>
     */
    public static function init(ActiveRecordInterface $existedObject, string $destination = BACKTRACE_TO_SELF)
    {
        if ($existedObject->isNewRecord) {
            throw new InvalidArgumentException('This Object is not existed!');
        }
        if (!in_array($destination, [
            BACKTRACE_TO_SELF,
            BACKTRACE_TO_ARRAY,
            BACKTRACE_TO_JSON,
            BACKTRACE_TO_FILE,
            BACKTRACE_TO_ATTRIBUTE
        ])) {
            throw new InvalidArgumentException('`$destination` must be BACKTRACE_TO_ARRAY|BACKTRACE_TO_JSON|BACKTRACE_TO_FILE|BACKTRACE_TO_ATTRIBUTE!');
        }
        $self = new self();
        $self->existedObject = $existedObject;
        $traces = [];
        $backtraces = debug_backtrace(!DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS);
        foreach ($backtraces as $backtrace) {
            if (isset($backtrace['class'])) {
                preg_match('/yii/', $backtrace['class'], $output_array);
            } else {
                $output_array = [];
            }
            if (!isset($output_array[0]) && (!isset($backtrace['file']) || !strpos($backtrace['file'], 'vendor'))) {
                $traces[] = $backtrace;
            }
        }
        $traces = array_reverse($traces);
        $self->traces = $traces;
        if ($destination == BACKTRACE_TO_SELF) {
            return $self;
        }
        return $self->$destination();
    }

    /**
     * Save traces to file with json formatted.
     *
     * Example:
     *  $model = new Model;
     *  $model->id = 1;
     *  $model->username = 'test';
     *  if($model->save()) {
     *    Backtrace::init($model)->toFile('@runtime/test.json');
     *  }
     *
     * Output:
     * /runtime/test.json
     *
     *
     * @param string|null $store_path default to `console/runtime/debug/backtrace`
     * @return void
     *
     * @datetime 3/9/2023 3:55 PM
     * @author   Phuong Dev <phuongdev89@gmail.com>
     */
    public function toFile(string $store_path = null)
    {
        if ($store_path === null) {
            if (Yii::getAlias('@console', false) !== false) {
                $store_path = Yii::getAlias('@console/runtime/debug/backtrace/' . get_class($this->existedObject));
            } else {
                $store_path = Yii::getAlias('@app/runtime/debug/backtrace/' . get_class($this->existedObject));
            }
        }
        if (!file_exists($store_path)) {
            mkdir($store_path, 0777, true);
        }
        file_put_contents($store_path . '/' . $this->existedObject->getPrimaryKey() . '.json', $this->toJson());
    }

    /**
     * Return json format
     *
     * Example:
     * if($model->save()) {
     *   $traceJson = Backtrace::init($model)->toJson();
     * }
     *
     * Output:
     *  [
     *      {
     *          "function": "actionUpdate",
     *          "class": "backend\\controllers\\UserController",
     *          "type": "->"
     *      },
     *      {
     *          "file": "backend/controllers/UserController.php",
     *          "line": 366,
     *          "function": "save",
     *          "class": "common\\models\\User",
     *          "type": "->"
     *      },
     *      {
     *          "file": "/var/www/tribe/common/models/User.php",
     *          "line": 289,
     *          "function": "toJson",
     *          "class": "phuongdev89\\debug\\db\\Backtrace",
     *          "type": "::"
     *      }
     *  ]
     *
     * @param bool $include_me the result will include where called Backtrace::init()
     * @return string
     *
     * @datetime 3/9/2023 3:55 PM
     * @author   Phuong Dev <phuongdev89@gmail.com>
     */
    public function toJson(bool $include_me = true): string
    {
        return Json::encode($this->toArray($include_me));
    }

    /**
     * Return php array format
     *
     * Example:
     * if($model->save()) {
     *   $traceJson = Backtrace::init($model)->toArray();
     * }
     *
     * Output:
     *  [
     *      [
     *          "function" => "actionUpdate",
     *          "class" => "backend\\controllers\\UserController",
     *          "type" => "->"
     *      ],
     *      [
     *          "file" => "backend/controllers/UserController.php",
     *          "line" => 366,
     *          "function" => "save",
     *          "class" => "common\\models\\User",
     *          "type" => "->"
     *      ],
     *      [
     *          "file" => "/var/www/tribe/common/models/User.php",
     *          "line" => 289,
     *          "function" => "toJson",
     *          "class" => "phuongdev89\\debug\\db\\Backtrace",
     *          "type" => "::"
     *      ]
     *  ]
     *
     * @param bool $include_me the result will include where called Backtrace::init()
     * @return array
     *
     * @datetime 3/9/2023 3:55 PM
     * @author   Phuong Dev <phuongdev89@gmail.com>
     */
    public function toArray(bool $include_me = true): array
    {
        $traces = $this->traces;
        if (!$include_me) {
            array_pop($traces);
        }
        return $traces;
    }

    /**
     * This action will be automated update the given column with json data
     *
     * Example:
     * if($model->save()) {
     *   Backtrace::init($model)->toAttribute('trace');
     * }
     *
     * @param string $attribute column name
     * @param bool $include_me the result will include where called Backtrace::init()
     * @return void
     *
     * @datetime 3/9/2023 4:21 PM
     * @author   Phuong Dev <phuongdev89@gmail.com>
     */
    public function toAttribute(string $attribute, bool $include_me = true)
    {
        if ($this->existedObject->hasAttribute($attribute)) {
            $this->existedObject->$attribute = $this->toJson($include_me);
            $this->existedObject->update(false);
        } else {
            throw new InvalidArgumentException('The `' . get_class($this->existedObject) . '` has not attribute `' . $attribute . '`');
        }
    }
}
