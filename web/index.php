<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/application.php';


use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

if ('dev' == ENV){
    $app['debug'] = true;
}

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../logs/development.log',
));

$app->register(new Silex\Provider\ValidatorServiceProvider());


$app->get('/', function() use($app) {
    return $app['twig']->render('index.twig');
});
$app->get('/about', function() use($app) {
    return $app['twig']->render('about.twig');
});
$app->get('/contact', function() use($app) {
    return $app['twig']->render('contact.twig');
});

$rocket = new \Rocket\App($app['monolog']);

$app->post('/', function(Request $request) use($app, $rocket) {

    $icao = $request->get('icao');

    $app['monolog']->addInfo('Icao code'. $icao);

    $notams = $rocket->getNotamsByIcao($icao);

    if (!$notams) {
        return $app->json([
            'status' => 'error',
            'message' => 'Oops, something went wrong! PLease check your ICAO code & try again.'
        ]);
    }

    return $app->json([
        'status' => 'success',
        'message' => 'Soccessfully located notams.',
        'notams' => $notams
    ]);
});

$app->run();

