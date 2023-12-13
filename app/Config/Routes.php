<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->post('webhook', 'TelegramController::webhook');
$routes->get('popular_tags', 'SubscriberController::popularTags');
$routes->get('top_tag_values', 'SubscriberController::topTagValues');
