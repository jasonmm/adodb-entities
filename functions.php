<?php
use Jasonmm\ArCreator\Configuration;
use Ulrichsg\Getopt\Getopt;
use Ulrichsg\Getopt\Option;

require_once 'vendor/autoload.php';

/**
 * @param ADOFieldObject $column
 *
 * @return string
 */
function columnDataType(ADOFieldObject $column) {
    $integers = ['tinyint', 'smallint', 'mediumint', 'bigint'];
    $floats = ['decimal'];

    if( in_array($column->type, $integers) ) {
        return 'int';
    }

    if( in_array($column->type, $floats) ) {
        return 'float';
    }

    return 'string';
}

/**
 * @param string $tableName
 *
 * @return string
 */
function classNameFromTableName($tableName) {
    $parts = explode('_', $tableName);
    $parts = array_map(function ($item) {
        return ucfirst($item);
    }, $parts);

    return implode('', $parts);
}

/**
 * @return Configuration
 */
function getConfig() {
    $options = new Getopt([
        new Option(null, 'driver', Getopt::REQUIRED_ARGUMENT),
        new Option(null, 'hostname', Getopt::REQUIRED_ARGUMENT),
        new Option(null, 'username', Getopt::REQUIRED_ARGUMENT),
        new Option(null, 'password', Getopt::REQUIRED_ARGUMENT),
        new Option(null, 'database', Getopt::REQUIRED_ARGUMENT),
        new Option('n', 'namespace', Getopt::REQUIRED_ARGUMENT),
        new Option('o', 'outputDirectory', Getopt::REQUIRED_ARGUMENT),
    ]);

    $options->parse();

    $nn = function($v) {
        return !is_null($v);
    };

    $c = new Configuration();
    $c->driver = $nn($options['driver']) ? $options['driver'] : $c->driver;
    $c->hostname = $nn($options['hostname']) ? $options['hostname'] : $c->hostname;
    $c->password = $options['password'];
    $c->database = $nn($options['database']) ? $options['database'] : $c->database;
    $c->namespace = $nn($options['namespace']) ? $options['namespace'] : $c->namespace;
    $c->outputDirectory = $nn($options['outputDirectory']) ? $options['outputDirectory'] : $c->outputDirectory;

    return $c;
}

/**
 * @param Configuration $cfg
 *
 * @throws Exception
 */
function checkConfig(Configuration $cfg) {
    foreach( get_object_vars($cfg) as $name => $value ) {
        if( is_null($value) ) {
            if( $name === 'password' ) {
                continue;
            }
            throw new Exception('Option "' . $name . '" is missing, but is required.');
        }
    }
}
