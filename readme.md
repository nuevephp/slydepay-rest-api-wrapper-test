Slydepay PHP REST Wrapper test app
=====================

You can sign up for a Slydepay Merchant account at https://app.slydepay.com.gh/auth/signup#business_reg

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install this project.

```bash
$ composer install
```

This will require Slydepay PHP and all its dependencies. Slydepay PHP required PHP 5.5 or newer.

## Usage

You will need to copy the `.env.example` file and rename it `.env` with your Slydepay email and mechant secret.

You can test this by using a tool like Postman with the `test.json` file provided.

You can test the project using the PHP built in webserver by running this command from the root directory:

```bash
php -S 0.0.0.0:8080
```
