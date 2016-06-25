<?php
namespace Demo7\Support;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr7Middlewares\Middleware\BasicAuthentication;

class AuthenticationServiceProvider extends AbstractServiceProvider
{
    protected $provides = [BasicAuthentication::class];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     */
    public function register()
    {
        $this->container->add(BasicAuthentication::class, function () {
            return new BasicAuthentication(['dpcon' => 'ams']);
        });
    }
}
