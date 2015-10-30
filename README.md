# ADOdb Entities

This program creates [ADOdb](https://github.com/ADOdb/ADOdb) active record classes for a given database model.  This program is intended for use in a database-first approach.

Running `create-entities.php` will read the tables in your database and creates simple active record classes based on the table structure.


# Usage

Example:

    php create-entities.php --hostname=<db host> --database=<db name> --namespace=<namespace of generated classes> --outputDirectory=<directory to write the generated files to>

The list of available options are:

* driver: the ADOdb database driver to use (default "mysqli")
* hostname: the name of the database host (default "localhost")
* username: the username used to login to the database (default "root")
* password: the username's password (default "")
* database: the name of the database to generate active record classes for (default "")
* namespace: the PHP namespace to write at the top of the generated files (default "Models")
* outputDirectory: the directory where the generated files will be written (default "./")  This is typically the directory in your project where you want the model classes to reside.
* includeOverrideMethods: adds method stubs to the generated classes that override some of ADOdb's `ADODB_Active_Record` methods.  This will provide better type-hinting when using the generated classes without changing the functionality of the methods.




