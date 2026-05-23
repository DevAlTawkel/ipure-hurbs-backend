@echo off
REM Force PHP with intl enabled (required by Filament admin)
set "PATH=C:\php;%PATH%"

php -r "if (!extension_loaded('intl')) { fwrite(STDERR, 'ERROR: PHP intl extension is not loaded.' . PHP_EOL . 'Edit C:\php\php.ini and enable: extension=intl' . PHP_EOL); exit(1); }"

php artisan serve %*
