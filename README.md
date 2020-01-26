# Simple Web Math Calculator

### Stack
* Symfony 5.0
* Math Calculator 0.1
* React 16

## Test and Code Quality
```
composer test
composer phpcsfix
composer phpstan
```

## Dev
```
composer install
yarn encore dev
```

## Production
```
APP_ENV=prod composer install --no-dev
yarn encore prod
bin/console ca:cl -e prod
bin/console ca:wa -e prod
```
