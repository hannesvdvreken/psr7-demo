<?php

$container = new League\Container\Container();
$container->delegate(new League\Container\ReflectionContainer());

$container->addServiceProvider(Demo7\Support\AuthenticationServiceProvider::class);
$container->addServiceProvider(Demo7\Support\RouterServiceProvider::class);

return $container;
