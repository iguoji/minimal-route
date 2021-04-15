<?php
declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Minimal\Route\Manager;
use Minimal\Route\Route;
use Minimal\Route\Group;


$manager = new Manager();
// $manager->domain('a.baidu.com', function() use($manager){
//     $manager->group('admin', function() use($manager){
//         $manager->get('login', ['\App\Login', 'login']);
//         $manager->post('register', ['\App\Login', 'login']);
//     });
// });


// $manager->get('login', ['\App\Account', 'login']);
// $manager->post('register', ['\App\Account', 'register']);
// $manager->match(['GET', 'POST'], 'profile', ['\App\Account', 'profile']);
// $manager->any('ping', ['\App\Account', 'ping']);
// $manager->any('debug', ['\App\Account', 'debug'])->middleware(\App\Test::class);


$manager->domain('a.baidu.com', function() use($manager){
    $manager->group('api', '', function() use($manager){
        $manager->get('aaa', ['\App\Account', 'aaa']);
        $manager->group('account', '', function() use($manager){
            $manager->post('bbb', ['\App\Account', 'bbb']);
            $manager->post('ccc', ['\App\Account', 'ccc'])->middleware(\App\Test222::class);
        })->middleware(\App\Test::class);
        $manager->post('ddd', ['\App\Account', 'ddd']);
        $manager->post('eee');
    });
});

var_dump(
    $manager->cache()
    // $manager->routes
);