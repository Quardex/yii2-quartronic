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
    protected $params;

    public function init()
    {
        parent::init();

        $defaultParams = [
            'db'=>[
                'driver'    => Yii::$app->db->getDriverName(),
                'database'  => $this->getDsnAttribute('dbname'),
                'host'      => $this->getDsnAttribute('host'),
                'username'  => Yii::$app->db->username,
                'password'  => Yii::$app->db->password,
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'webDir' => __DIR__.'/../../../../backend/web/',
            'webPath' => '/',
        ];

        $this->_Q = new \quarsintex\quartronic\qcore\Quartronic(array_merge($defaultParams, $this->params));
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
        return $this->_Q ? $this->_Q->__get($name) : $this->params[$name];
    }

    public function __set($name, $value)
    {
        $this->_Q ? $this->_Q->__set($name, $value) : $this->params[$name] = $value;
    }

    public function __isset($name)
    {
        return $this->_Q ? $this->_Q->__isset($name) : isset($this->params[$name]);
    }

    public function __unset($name)
    {
        if ($this->_Q) {
            $this->_Q->__unset($name);
        } else {
            unset($this->params[$name]);
        }
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