<p><a href="/blog/44?cmd=view&similar=1"> test url - click me </a></p>
<p><a href="/test"> test </a></p>
<p><a href="/">  root</a></p>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("vendor/autoload.php");
use Aura\Router\RouterContainer;
$routerContainer = new RouterContainer();

$map = $routerContainer->getMap();
$map->get('hello', '/', function($req,$res){
    $res->getBody()->write("Hello World");
    return $res;
});

$map->get('test', '/test', function($req,$res){
    $res->getBody()->write("Ceci est un test");
    return $res;
});

$map->get('blog.read', '/blog/{id}', function ($request, $response) {
    $id = (int) $request->getAttribute('id');
    $response->getBody()->write("You asked for blog entry {$id}.");
    return $response;
});


$matcher = $routerContainer->getMatcher();
$request = GuzzleHttp\Psr7\ServerRequest::fromGlobals(); #var_dump($request);
$route = $matcher->match($request);
if( false === $route ){ echo "route not found for current url"; }
/*var_dump($route);
var_dump($route->attributes);
var_dump($_GET);*/
foreach ($route->attributes as $key => $val) {
    $request = $request->withAttribute($key, $val);
}
#var_dump($request);
/**
 * @var Closure $callable
 */
$callable = $route->handler;
$response = $callable($request, new GuzzleHttp\Psr7\Response);
echo $response->getBody();