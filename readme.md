# Installation #

## Dependent ##

Must have [phpstorm-protocol](https://github.com/phuongdev89/phpstorm-protocol) before

## Composer ##

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist phuongdev89/yii2-debug "@dev"
```

or add

```
"phuongdev89/yii2-debug": "@dev"
```

to the require section of your `composer.json` file.

# Configuration #

```
'modules' => [
    'debug' => [
        'class' => 'phuongdev89\debug\Module',
    ], 
],
```

or

```
$config['bootstrap'][]      = 'debug';
$config['modules']['debug'] = [
    'class' => 'phuongdev89\debug\Module',
];
```

# Backtrace Usage #

Use to see traces of given Object

### Initialize the backtrace with an existed active record.
Example:
 ```
if($model->save()) {
    Backtrace::init($model);
}
```

### Save traces to file with json formatted
Example:
```
$model = new Model;
$model->id = 1;
$model->username = 'test';
if($model->save()) {
  Backtrace::init($model)->toFile('@runtime/test.json');
  //Output is /runtime/test.json
}
```

### Return json format
Example:
```
if($model->save()) {
 $traceJson = Backtrace::init($model)->toJson();
}
```
output:
```
[
    {
        "function": "actionUpdate",
        "class": "backend\\controllers\\UserController",
        "type": "->"
    },
    {
        "file": "backend/controllers/UserController.php",
        "line": 366,
        "function": "save",
        "class": "common\\models\\User",
        "type": "->"
    },
    {
        "file": "/var/www/tribe/common/models/User.php",
        "line": 289,
        "function": "toJson",
        "class": "phuongdev89\\debug\\db\\Backtrace",
        "type": "::"
    }
]
```

### Return php array format
Example:
```
if($model->save()) {
 $traceJson = Backtrace::init($model)->toArray();
}
```
Output:
```
[
    [
        "function" => "actionUpdate",
        "class" => "backend\\controllers\\UserController",
        "type" => "->"
    ],
    [
        "file" => "backend/controllers/UserController.php",
        "line" => 366,
        "function" => "save",
        "class" => "common\\models\\User",
        "type" => "->"
    ],
    [
        "file" => "/var/www/tribe/common/models/User.php",
        "line" => 289,
        "function" => "toJson",
        "class" => "phuongdev89\\debug\\db\\Backtrace",
        "type" => "::"
    ]
]
```

### Automated update the given column with json data
Example:
```
if($model->save()) {
 Backtrace::init($model)->toAttribute('trace');
}
```
