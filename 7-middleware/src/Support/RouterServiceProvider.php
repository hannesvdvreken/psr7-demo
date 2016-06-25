<?php
namespace Demo7\Support;

use Demo7\Controller;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\RouteCollection;

class RouterServiceProvider extends AbstractServiceProvider
{
    protected $provides = [RouteCollection::class];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     */
    public function register()
    {
        $this->container->add(RouteCollection::class, function () {
            $router = new RouteCollection($this->container);

            $router->get('/', Controller::class.'::index');

            return $router;
        });
    }
}
