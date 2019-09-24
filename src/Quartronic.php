<?php

namespace quarsintex\yii2\quartronic;

use yii\base\Component;

require_once(\Yii::getAlias('@vendor/quardex/quarsintex/qcore/Quartronic.php'));

/**
 *
 * @author Andrew Quardex <quardex@mail.com>
 */
class Quartronic extends Component
{
    /**
     * @var \quardex\quartronic\qcore\Quartronic
     */
    protected $_cms;

    public function init()
    {
        parent::init();
        $this->$_cms = new \quarsintex\quartronic\qcore\Quartronic();
    }

    /**
     * @var self
     */
    static protected $_instance = null;

    /**
     * @return self
     */
    static public function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->_mobileDetect, $name], $arguments);
    }
}