# Plugin system

You can make your own executable code for ColorCore - plugins!

Plugins are .php files containing a specific structure of methods and events.

## Method names & events
```php
static function on_registerGJAccount($userName, $email, $password) 
```
```php
static function on_loginGJAccount($userName, $password) 
```
## Notes
<b>File name (without .php) must be the class name.</b><br>
<b>All methods in a plugin must be static.</b>
## Example
```php
<?php
// example.php
class example {
    public static function on_registerGJAccount($userName, $email, $password) {
        file_put_contents(__DIR__ . "/register.log", "$userName has registered an account!");
    }
}
```