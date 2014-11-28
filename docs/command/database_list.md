Database list command
=======================

to execute this command :

```bash
php app/console.php itkg-core:database:list
```

# Overview

This command provide a quick overview of your releases
* Release name
* Number of scripts
* Number of rollback scripts
* Status (OK or error message)


# Script options

## Override default releases directory

You can define another releases directory by specifiying path option :

```bash
php app/console.php itkg-core:database:list --path=/path/to/you/releases/directory
```
