<?php

$app->add(new \Cart\Middleware\ValidationErrorsMiddleware($container->get('view')));
$app->add(new \Cart\Middleware\OldInputMiddleware($container->get('view')));
