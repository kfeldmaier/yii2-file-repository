<?php

/**
 * @copyright Copyright &copy; Kai Feldmaier, 2014 - 2015
 * @package yii2-file-watcher
 * @version 1.0.1
 */

namespace kfeldmaier\filerepository;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * The filerepository module for Yii Framework 2.0.
 *
 * @author Kai Feldmaier <kai.feldmaier@gmail.com>
 * @since 1.0
 */
class Module extends \yii\base\Module
{

    /**
     * @inherit doc
     */
    public function init()
    {
        parent::init();

        if (!isset($this->controllerMap['repository'])) {
            $this->controllerMap['repository'] = 'kfeldmaier\filerepository\console\RepositoryController';
        }

    }

}