<a href="https://codeclimate.com/github/yohannzaoui/Task_App/maintainability"><img src="https://api.codeclimate.com/v1/badges/191d9df633c332f3f12a/maintainability" /></a>
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/4bedf3e82b284176b40896f43f726e7f)](https://www.codacy.com/app/yohannzaoui/Task_App?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=yohannzaoui/Task_App&amp;utm_campaign=Badge_Grade)


Task Manager Application
==================================

![symfony](https://d1pwix07io15pr.cloudfront.net/vd3200fdf32/images/logos/header-logo.svg)

* Developped with the Symfony 4.2 framework
* CSS : Bootstrap 4
* Translate in french and english
* Include TinyMCE
* Symfony cache PSR-6

## Prérequisites
* **Php 7.2**
* **Mysql 5.7**

## Tested with:
- PHPUnit [more infos](https://phpunit.de/)

## Install application:
clone or download the repository into your environment. https://github.com/yohannzaoui/Task_App.git

```
$ composer install
```
```
$ yarn install
```
```
$ yarn encore dev
```
enter your parameters database and email in .env (rename .env_example)
```
DATABASE_URL=mysql://root:root@127.0.0.1:3306/task_app
MAILER_URL=
```
```
$ php bin/console doctrine:database:create
```
```
$ php bin/console make:migrations
```
```
$ php bin/console doctrine:migrations:migrate
```

Run application in your favorite browser

- Create user
- LogIn
- Create task

# *Enjoy !!*
