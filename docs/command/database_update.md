Database update command
=======================

to execute this command :

```bash
php app/console.php itkg-core:database:update
```

# Default release structure

* Default release directory is under /script/releases directory (you can override it, see below)
* Your release should be a version number (1.0, 1.1, etc)
* Your release must have two directories : script directory & rollback directory
* Each directory must contain the same number of scripts with the same name
* Each script is a PHP file which is executed with a class context (see : [Loader class](https://github.com/itkg/core/blob/master/src/Itkg/Core/Command/DatabaseUpdate/Loader.php))


Default structure example :
```text
.
+-- script
|  +-- releases
|     +-- release_version
|        +-- script
|           +-- script_1.php
|        +-- rollback
|           +-- script_1.php
```

Script example

```php
    /**
     * @var \Itkg\Core\Command\DatabaseUpdate\Loader
     */
    $this->addQuery("insert into YOUR_TABLE (YOUR_FIELDS)
    values (YOUR_VALUES)");
```

# Script options

## Display a release script
```bash
php app/console.php itkg-core:database:update RELEASE_VERSION
```

This command will run all scripts under release_version directory & display results (no execution at this time)

You can add output colors by adding colors option

```bash
php app/console.php itkg-core:database:update RELEASE_VERSION --colors
```

## Execute a release script

To execute release, add execute option

```bash
php app/console.php itkg-core:database:update RELEASE_VERSION --execute
```

If a script execution failed, his rollback is played to restore database structure

## Execute a specific script under a release_version

To execute specific script add script option

```bash
php app/console.php itkg-core:database:update RELEASE_VERSION --script=YOUR_SCRIPT_NAME
```

## Play rollback first

You may want to play rollback only with rollback-first option :

```bash
php app/console.php itkg-core:database:update RELEASE_VERSION --rollback-first
```

## Force a rollback

You can force rollback script to restore state after script execution with force-rollback option :

```bash
php app/console.php itkg-core:database:update RELEASE_VERSION --force-rollback
```

## Override default releases directory

You can define another releases directory by specifiying path option :

```bash
php app/console.php itkg-core:database:update RELEASE_VERSION --path=/path/to/you/releases/directory
```
