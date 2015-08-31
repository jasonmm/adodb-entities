<?php

require_once 'vendor/autoload.php';

$cfg = getConfig();

try {
    checkConfig($cfg);
} catch( Exception $e ) {
    echo $e->getMessage() . "\r\n";
    exit;
}

if( is_null($cfg->password) ) {
    $cfg->password = readline('Enter the database password: ');
}

$db = ADONewConnection($cfg->driver);
if( $db === false ) {
    echo $cfg->driver . " is not a valid database driver.\r\n";
    exit;
}
$ret = $db->NConnect($cfg->hostname, $cfg->username, $cfg->password, $cfg->database);
if( $ret === false ) {
    echo "Error connecting to the database.\r\n";
    exit;
}

$tables = $db->MetaTables();

foreach( $tables as $index => $classNameFromTableName ) {
    $tables[$index] = array(
        'tableName' => $classNameFromTableName,
    );

    $tables[$index]['columns'] = $db->MetaColumns($classNameFromTableName);
}

if( !file_exists($cfg->outputDirectory) ) {
    $ret = mkdir($cfg->outputDirectory, 0777, true);
    if( $ret === false ) {
        echo 'Error creating the directory ' . $cfg->outputDirectory . "\r\n";
        exit;
    }
}

$loader = new Twig_Loader_Filesystem('./');
$twig = new Twig_Environment($loader, []);

foreach( $tables as $tableObj ) {
    $className = classNameFromTableName($tableObj['tableName']);
    $table = [
        'namespace'              => $cfg->namespace,
        'className'              => $className,
        'baseClass'              => '\\ADODB_Active_Record',
        'tableName'              => $tableObj['tableName'],
        'includeOverrideMethods' => $cfg->includeOverrideMethods,
        'columns'                => []
    ];
    foreach( $tableObj['columns'] as $column ) {
        $c = [
            'name'     => strtolower($column->name),    // ADODB lowercases table column names.
            'dataType' => columnDataType($column),
        ];
        $table['columns'][] = $c;
    }

    $output = $twig->render('class.twig', $table);

    $fn = $cfg->outputDirectory . $className . '.php';
    $fp = fopen($fn, 'w');
    fwrite($fp, "<?php\n" . $output);
    fclose($fp);
}


