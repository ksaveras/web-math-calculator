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
yarn install
yarn encore dev
```

## Production
```
APP_ENV=prod composer install --no-dev
yarn install
yarn encore prod
bin/console ca:cl -e prod
bin/console ca:wa -e prod
```

## Docker image
Quickest way to test application.
```
docker build -t web-calc .
docker run -it --rm -p 8000:80 web-calc:latest
```
Open your browser URL: http://localhost:8000/
