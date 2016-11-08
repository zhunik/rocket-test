<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/application.php';


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Csrf\CsrfToken;

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
$app->register(new Silex\Provider\CsrfServiceProvider);


$app->get('/', function() use($app) {
    return $app['twig']->render('index.twig',[
        'token' => $app['csrf.token_manager']->getToken('icao_form')
    ]);
});
$app->get('/about', function() use($app) {
    return $app['twig']->render('about.twig');
});
$app->get('/contact', function() use($app) {
    return $app['twig']->render('contact.twig');
});

$rocket = new \Rocket\App($app['monolog']);

$app->post('/', function(Request $request) use($app, $rocket) {

    $token = $request->get('token');

    if (!$app['csrf.token_manager']->isTokenValid(new CsrfToken('icao_form', $token))){
        return $app->json([
            'status' => 'error',
            'message' => 'Don\'t cheat!'
        ]);
    }
    $icao = $request->get('icao');


    $errors = $app['validator']->validate($icao, [
        new Assert\NotBlank(),
        new Assert\Length(['min'=>4, 'max'=>4]),
        new Assert\Regex([
            'pattern' =>'/[A-Z]{4}/i',
            'match' => true,
            'message' => 'ICAO code should represent 4 capital latin letters.'
        ])
    ]);

    if ($count = count($errors)) {
        $errorMessage = '';
        foreach ($errors as $key => $error) {
            $app['monolog']->addError($error->getMessage());
            $errorMessage .= $error->getMessage() . ($key === $count-1?'':'<br/>');
        }
        return $app->json([
           'status' => 'error',
           'message' => $errorMessage,
        ]);
    }

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

