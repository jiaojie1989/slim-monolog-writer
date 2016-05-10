<?php

/*
 * Copyright (C) 2016 SINA Corporation
 *  
 * When I reflect upon the number of disagreeable people who I know who have gone
 * to a better world, I am moved to lead a different life.
 * 
 * This script is firstly created at 2016-05-10.
 * 
 * To see more infomation,
 *    visit our official website http://jiaoyi.sina.com.cn/.
 */

namespace Jiaojie\Slim\LogWriter;

/**
 * MonologWriter, Middleware for Slim 2.x Log Writter
 *
 * @encoding UTF-8 
 * @author jiaojie <jiaojie@staff.sina.com> 
 * @since 2016-05-10 15:10 (CST) 
 * @version 0.1
 * @description 
 * @license MIT
 */
class MonologWriter {

    /**
     * monolog logger
     * @var \Monolog\Logger
     */
    protected $logger;

    /**
     * Log Level Converts
     * @var array
     */
    protected static $logLevel = array(
        \Slim\Log::EMERGENCY => \Monolog\Logger::EMERGENCY,
        \Slim\Log::ALERT => \Monolog\Logger::ALERT,
        \Slim\Log::CRITICAL => \Monolog\Logger::CRITICAL,
        \Slim\Log::ERROR => \Monolog\Logger::ERROR,
        \Slim\Log::WARN => \Monolog\Logger::WARNING,
        \Slim\Log::NOTICE => \Monolog\Logger::NOTICE,
        \Slim\Log::INFO => \Monolog\Logger::INFO,
        \Slim\Log::DEBUG => \Monolog\Logger::DEBUG,
    );

    /**
     * monolog setting: 
     * 
     *      name: "appname"
     * 
     *      handlers: array(handler1, handler2...)
     * 
     *      processors: array(processor1, processor2...)
     *
     * @param array $settings Setting of monolog
     */
    public function __construct($settings = array()) {
        $this->settings = $settings;
        $this->logger = new \Monolog\Logger($settings['name']);
        foreach ($settings['handlers'] as $handler) {
            if (!$handler instanceof \Monolog\Handler\HandlerInterface) {
                throw new \RuntimeException("handlers must be an implementation of '\Monolog\Handler\HandlerInterface'");
            }
            $this->logger->pushHandler($handler);
        }
        foreach ($settings['processors'] as $processor) {
            if (!is_callable($processor)) {
                throw new \RuntimeException("processors must be callable");
            }
            $this->logger->pushProcessor($processor);
        }
    }

    /**
     * public interface write for slim writer
     *
     * @see \Slim\LogWriter
     * @param mixed $object
     * @param int $level
     * @return void
     */
    public function write($object, $level = \Slim\Log::DEBUG) {
        if (!array_key_exists($level, static::$logLevel)) {
            throw new \RuntimeException("log level do not exist");
        }
        $this->logger->addRecord(static::$logLevel[$level], $object);
    }

}
