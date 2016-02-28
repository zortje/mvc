# MVC

MVC

[![Travis](https://img.shields.io/travis/zortje/mvc.svg?style=flat)](https://travis-ci.org/zortje/mvc) [![Codecov](https://img.shields.io/codecov/c/github/zortje/mvc.svg)](https://codecov.io/github/zortje/mvc) [![Scrutinizer](https://img.shields.io/scrutinizer/g/zortje/mvc.svg?style=flat)](https://scrutinizer-ci.com/g/zortje/mvc/?branch=master) [![Packagist](https://img.shields.io/packagist/v/zortje/mvc.svg?style=flat)](https://packagist.org/packages/zortje/mvc) [![Packagist](https://img.shields.io/packagist/dt/zortje/mvc.svg?style=flat)](https://packagist.org/packages/zortje/mvc)

## Installing via Composer

The recommended way to install MVC is though [Composer](https://getcomposer.org/).

```JSON
{
    "require": {
        "zortje/mvc": "~0.0"
    }
}
```

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
