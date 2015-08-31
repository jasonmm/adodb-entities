<?php
namespace Jasonmm\ArCreator;

/**
 * Class Configuration
 * @package Jasonmm\ArCreator
 */
class Configuration
{
    /**
     * @var string
     */
    public $driver = 'mysqli';
    /**
     * @var string
     */
    public $hostname = 'localhost';
    /**
     * @var string
     */
    public $username = 'root';
    /**
     * @var string
     */
    public $password = '';
    /**
     * @var string
     */
    public $database = '';
    /**
     * @var string
     */
    public $namespace = 'Models';
    /**
     * @var string
     */
    public $outputDirectory = './';
    /**
     * @var bool
     */
    public $includeOverrideMethods = false;
}
