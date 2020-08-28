<?php

namespace quarsintex\yii2\quartronic;

use Yii;
use yii\base\Component;

require_once(\Yii::getAlias('@vendor/quardex/quartronic/qcore/Quartronic.php'));

/**
 *
 * @author Andrew Quardex <quardex@mail.com>
 */
class Quartronic extends Component
{
    /**
     * @var \quardex\quartronic\qcore\Quartronic
     */
    protected $_Q;
    public $webDir = __DIR__.'/../../../../backend/web/';
    public $webPath = '/';

    public function init()
    {
        parent::init();
        $this->_Q = new \quarsintex\quartronic\qcore\Quartronic([
            'db'=>[
                'driver'    => Yii::$app->db->getDriverName(),
                'database'  => $this->getDsnAttribute('dbname'),
                'host'      => $this->getDsnAttribute('host'),
                'username'  => Yii::$app->db->username,
                'password'  => Yii::$app->db->password,
            ]
        ]);
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
        return call_user_func_array([$this->_Q, $name], $arguments);
    }

    public function __get($name)
    {
      return $this->_Q->__get($name);
    }

    public function __set($name, $value)
    {
      $this->_Q->__set($name, $value);
    }

    public function __isset($name)
    {
     return $this->_Q->__isset($name);
    }

    public function __unset($name)
    {
      $this->_Q->__unset($name);
    }

    private function getDsnAttribute($name)
    {
        if (preg_match('/' . $name . '=([^;]*)/', \Yii::$app->getDb()->dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }
}