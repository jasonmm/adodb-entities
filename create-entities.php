<?php

require_once 'vendor/autoload.php';

require_once 'config.php';

$db = ADONewConnection($driver);
$db->NConnect($hostname, $username, $password, $database);

$tables = $db->MetaTables();

foreach ($tables as $index => $classNameFromTableName) {
    $tables[$index] = array(
        'tableName' => $classNameFromTableName,
    );

    $tables[$index]['columns'] = $db->MetaColumns($classNameFromTableName);
}

//print_r($tables);

if (!file_exists($outputDirectory)) {
    mkdir($outputDirectory);
}


$loader = new Twig_Loader_Filesystem('./');
$twig = new Twig_Environment($loader, []);

foreach ($tables as $tableObj) {
    $className = classNameFromTableName($tableObj['tableName']);
    $table = [
        'namespace' => $namespace,
        'className' => $className,
        'baseClass' => '',
        'tableName' => $tableObj['tableName'],
        'columns'   => []
    ];
    foreach ($tableObj['columns'] as $column) {
        $c = [
            'name'     => $column->name,
            'dataType' => columnDataType($column),
        ];
        $table['columns'][] = $c;
    }

    $output = $twig->render('class.twig', $table);

    $fn = $outputDirectory . $className . '.php';
    $fp = fopen($fn, 'w');
    fwrite($fp, "<?php\r\n" . $output);
    fclose($fp);
}


/**
 * @param ADOFieldObject $column
 *
 * @return string
 */
function columnDataType(ADOFieldObject $column)
{
    $integers = ['tinyint', 'smallint', 'mediumint', 'bigint'];
    $floats = ['decimal'];

    if (in_array($column->type, $integers)) {
        return 'int';
    }

    if (in_array($column->type, $floats)) {
        return 'float';
    }

    return 'string';
}

/**
 * @param string $tableName
 *
 * @return string
 */
function classNameFromTableName($tableName)
{
    $parts = explode('_', $tableName);
    $parts = array_map(function ($item) {
        return ucfirst($item);
    }, $parts);

    return implode('', $parts);
}
