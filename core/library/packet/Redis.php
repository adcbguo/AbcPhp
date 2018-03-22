<?php
/**
 * Redis缓存类
 * User: 郭冠常
 * Date: 2018/3/10
 * Time: 11:07
 */
namespace core\packet;
use core\Container;
class Redis extends \Redis
{
    /**
     * 对象实例
     * @var object
     */
    protected static $redis;

    /**
     * 连接配置
     * @var array $options
     */
    protected static $options = [];

    /**
     * 实例化连接Redis
     * @param array $options
     */
    public function __construct($options = [])
    {
        if (!is_object(self::$redis)) {
            if (!extension_loaded('redis')) {
                throw new \BadFunctionCallException('not support: redis');
            }
            self::$options = Container::get('config')->get('redis');
            if (!empty($options)) {
                self::$options = array_merge(self::$options, $options);
            }
            $func = self::$options['persistent'] ? 'pconnect' : 'connect';
            self::$redis = new static();

            self::$redis->$func(self::$options['host'], self::$options['port'], self::$options['timeout']);

            if ('' != self::$options['password']) {
                self::$redis->auth(self::$options['password']);
            }

            if (0 != self::$options['select']) {
                self::$redis->select(self::$options['select']);
            }
        }
        return self::$redis;
    }

    /**
     * 加锁防止同步操作
     * @param $lock
     * @param int $lockTime
     * @return bool
     */
    public function getRedisLock($lock, $lockTime = 5000)
    {
        $currTime = intval(microtime(true) * 1000);
        $time = $currTime + $lockTime;
        //首先看看是否能加锁
        $res = self::$redis->setnx("{$lock}_lock", $time);
        if (!$res) {
            //不能加锁看看上一个加锁的时间
            $oldLockTime = self::$redis->get("{$lock}_lock");
            if ($oldLockTime < $currTime) {
                //如果上一个加锁时间小于当前时间,那么重新设置时间
                $res = self::$redis->getSet("{$lock}_lock", $time);
                //如果老的时间等于前面获取的时间那么获取锁
                if ($res == $oldLockTime) return true;
                unset($oldLockTime, $currTime);
            }
            usleep(10000);
            return $this->getRedisLock($lock);
        } else {
            return true;
        }
    }

    /**
     * 释放锁
     * @param $lock
     * @return int
     */
    public function freeRedisLock($lock)
    {
        return self::$redis->del("{$lock}_lock");
    }
}