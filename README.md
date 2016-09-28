# MVC

MVC

[![Packagist](https://img.shields.io/packagist/v/zortje/mvc.svg?style=flat)](https://packagist.org/packages/zortje/mvc)
[![Travis](https://img.shields.io/travis/zortje/mvc.svg?style=flat)](https://travis-ci.org/zortje/mvc)
[![Codecov](https://img.shields.io/codecov/c/github/zortje/mvc.svg)](https://codecov.io/github/zortje/mvc)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/zortje/mvc.svg?style=flat)](https://scrutinizer-ci.com/g/zortje/mvc/?branch=master)
[![Dependency Status](https://dependencyci.com/github/zortje/mvc/badge)](https://dependencyci.com/github/zortje/mvc)
[![Packagist](https://img.shields.io/packagist/dt/zortje/mvc.svg?style=flat)](https://packagist.org/packages/zortje/mvc)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/7a0a476d-4956-4bfe-b6d9-f695e6b75f8f/big.png)](https://insight.sensiolabs.com/projects/7a0a476d-4956-4bfe-b6d9-f695e6b75f8f)

## Installing via Composer

The recommended way to install MVC is through [Composer](https://getcomposer.org/).

```JSON
{
    "require": {
        "zortje/mvc": "~0.0"
    }
}
```

## Setup

**Database**

Init Phinx with `phinx.yml` and run migration to create user tables.

**Webroot**

The NGINX server block root should point to the webroot folder where the index.php file should be, along with any files that should be directly accessible from the web browser.

# Documentation

## Controller

## Model
The model is devided into two classes; Table and Entity.

### Table
A table must extend the Table class and contain a property for `tableName` which is the database table name and `entityClass` which is the entity class name.

	class UserTable extends Zortje\MVC\Model\Table\Table
    {
        protected $tableName = 'users';
        protected $entityClass = User::class;
    }

### Entity
A entity must extend the Entity class and contain a property for columns.

The columns are defined with the table column name as the key and data type for value, the type can be either `string`, `int`, `float`, `double`, `bool`, `date` or `datetime`.

A optional "convenience" constructor can be added to the class to class to ease the creation of the entity object.

	class User extends Zortje\MVC\Model\Table\Entity
    {

        protected static $columns = [
            'email'         => 'string',
            'password_hash' => 'string',
        ];

        public function __construct(string $email, string $passwordHash)
        {
            parent::__construct(null, new \DateTime(), new \DateTime());

            $this->set('email', $email);
            $this->set('password_hash', $passwordHash);
        }
    }
