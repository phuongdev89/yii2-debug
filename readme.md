# Installation #

## Dependent ##

Must have [phpstorm-protocol](https://github.com/phuong17889/phpstorm-protocol) before

## Composer ##

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist phuong17889/yii2-debug "@dev"
```

or add

```
"phuong17889/yii2-debug": "@dev"
```

to the require section of your `composer.json` file.

# Configuration #

```
'modules' => [
    'debug' => [
        'class' => 'phuong17889\debug\Module',
    ], 
],
```
or
```
$config['bootstrap'][]      = 'debug';
$config['modules']['debug'] = [
    'class' => 'phuong17889\debug\Module',
];
```
