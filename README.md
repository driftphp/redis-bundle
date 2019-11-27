# DriftPHP - Redis adapter

This is a simple adapter for Redis on top of ReactPHP and DriftPHP. Following
the same structure that is followed in the Symfony ecosystem, you can use this
package as a Bundle, only usable under DriftPHP Framework.

## Install

You can install the package by using composer

```bash
composer require drift/redis-bundle
```

## Configure

This package will allow you to configure all your Redis async clients, taking
care of duplicity and the loop integration. Once your package is required by
composer, add the bundle in the kernel and change your `services.yaml`
configuration file to defined the clients

```yaml
redis:
    clients:
        users:
            host: "127.0.0.1"
            port: 6379
            database: "/users"
            password: "secret"
            protocol: "rediss://"
            idle: 0.5
            timeout: 10.0

        
        orders:
            host: "127.0.0.1"
            database: "/orders"
```

Only host is required. All the other values are optional.

## Use it

Once you have your clients created, you can inject them in your services by
using the name of the client in your dependency injection arguments array

```yaml
a_service:
    class: My\Service
    arguments:
        - "@redis.users_client"
        - "@redis.orders_client"
```

You can use Autowiring as well in the bundle, by using the name of the client
and using it as a named parameter

```php
public function __construct(
    Client $usersClient,
    Client $ordersClient
)
```