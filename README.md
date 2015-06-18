php-decorators
==============

Python-like decorators for PHP

Disclaimer
----------

Please note that this is a proof of concept, use at your own risk.

Usage
-----

Specify decorators with the ```@Decorate``` annotation using one of the following formats:

- Class name::method name
- Class name (must implement __invoke)
- Function name

Decorators takes the original method and the context as arguments and returns a function that takes the same arguments the original method do.

It's possible to add as many decorators as desired, always returning a valid callable for the next decorator.

(almost) Real life examples
---------------------------

### In memory cache

```php
class InMemoryCacheDecorator
{
    private static $cache;

    public function __invoke($callable)
    {
        return function () use ($callable) {
            $args = func_get_args();
            $key = serialize([$callable, $args]);

            if (!isset(static::$cache[$key])) {
                static::$cache[$key] = call_user_func_array($callable, $args);
            }

            return static::$cache[$key];
        };
    }
}

class MyClass
{
    use Decorator\AnnotationDecoratorFactoryTrait;

    /**
     * @Decorate InMemoryCacheDecorator
     */
    public function myMethod($name)
    {
        sleep(1);
        return 'Hello ' . $name;
    }
}

$x = MyClass::factory(); // or use new AnnotationDecorator(new MyClass());

echo $x->myMethod('Marco'), PHP_EOL;
echo $x->myMethod('Marco'), ' (this time is cached)', PHP_EOL;
echo $x->myMethod('Marco'), ' (this time is cached)', PHP_EOL;

echo $x->myMethod('World'), PHP_EOL;
echo $x->myMethod('World'), ' (this time is cached)', PHP_EOL;
echo $x->myMethod('World'), ' (this time is cached)', PHP_EOL;
```

### Cart promos

```php
class Cart
{
    private $total = 100;

    /**
     * @Decorate halfVat
     * @Decorate fixedDiscount
     */
    public function calcTotal($vat)
    {
        return $this->total * (1 + $vat / 100);
    }
}

function halfVat($callable)
{
    return function ($vat) use ($callable) {
        return $callable($vat / 2);
    };
}

function fixedDiscount($callable)
{
    return function ($vat) use ($callable) {
        $total = $callable($vat) - 5;
        return $total < 0 ? 0 : $total;
    };
}

/** @var Cart $order */
$order = new Decorator\AnnotationDecorator(new Cart());
echo $order->calcTotal(21), PHP_EOL;
```

### Controller requirements

```php
function require_http_post($callable, $context)
{
    return function () use ($callable, $context) {
        if ($context->getMethod() !== 'POST') {
            throw new \Exception('Not supported');
        }

        return call_user_func_array($callable, func_get_args());
    };
}

class AddressController
{
    use Decorator\AnnotationDecoratorFactoryTrait;

    private $method;

    public function __construct($method)
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @Decorate require_http_post
     */
    public function deleteAction($id)
    {
        // delete...
        return 'success';
    }
}

echo AddressController::factory('POST')->deleteAction(1), PHP_EOL;
echo AddressController::factory('GET')->deleteAction(1), PHP_EOL;
```

Gotchas
-------

- Only works for methods
- Not completely transparent, you need to use the factory method
- No parameters type hinting for the decorated class
