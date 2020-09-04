# Matano ORM

![Matano ORM Logo](https://github.com/d3vkk/matano-orm/blob/master/matano-orm-logo.png)

Minimal PHP/SQL ORM. MariaDB/MySQL compatible

## Prerequisites

You should have the following installed

- [PHP 7.3 or higher](https://php.net/)
- [MariaDB 10 or higher](https://mariadb.com/) or [MySQL 5.7 or higher](https://mysql.com/)
  
## Set up

Fork or clone this repo
```
git clone https://github.com/d3vkk/matano-orm.git
```

Add your database connection details in `model.php`
```php
$db = new Model('host', 'user', 'password', 'database');
```

Use the functions from `matano.php` to CRUD your database records in `model.php`

© 2020 Donald K • Under MIT License
