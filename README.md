# console
a php console command use javascript console api

# example 

```php
Console::log('test');

Console::group('count');

Console::count();

Console::countReset();

Console::groupEnd('count');

Console::group('profile');

Console::profile();

Console::profileEnd();

Console::groupEnd('profile');

Console::time('time');

Console::timeEnd('time');
```

# output 

```php
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dom Url</title>
</head>

<body>
    <script>
    <?php echo Console::output() ?>
    </script>
</body>
</html>
```
