<?php

require "vendor/autoload.php";

// Load our settings from the .env file
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// Initialize Slim and set error settings to true
// do change this if you put the app in production
$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$container = $app->getContainer();

// Add the Slydepay API into the container for easy access in routes
$container['slydepay'] = function() {
    return new Slydepay\Slydepay(getenv('EMAIL'), getenv('API_KEY'));
};

$app->post('/order', function($request, $response) {
    $items = [];
    $ordersRequest = $request->getParam('orders');
    
    foreach ($ordersRequest['items'] as $item) {
        $orderItem = new Slydepay\Order\OrderItem(
            $item['id'],
            $item['name'],
            $item['price'],
            $item['quantity']
        );
        array_push($items, $orderItem);
    }

    $orderItems = new Slydepay\Order\OrderItems($items);
    $orderAmount = new Slydepay\Order\OrderAmount(
        $orderItems->subTotal(),
        $ordersRequest['shipping_cost'],
        $ordersRequest['tax_amount']
    );

    /* we can access slydepay here as a property of the closure 
     * because we are using the magic __get on the container to 
     * retrieve properties
     */
    try {
        $result = $this->slydepay->processPaymentOrder(
            $ordersRequest['id'],
            $ordersRequest['description'],
            $orderAmount,
            $orderItems
        );

        return $response->withJson([
            'success' => true,
            'redirect' => $result->redirectUrl()
        ], 200);
    } catch (Slydepay\Exception\ProcessPayment $e) {
        return $response->withJson([
            'success' => false,
            'error_message' => $e->getMessage()
        ], 200);
    }
});

$app->run();