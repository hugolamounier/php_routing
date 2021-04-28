<?php
class Route{

    public static $routes = Array();
    
    public static function add($expression, $path, $auth = 1)
    {
        array_push(self::$routes, array(
            'expression' => $expression,
            'path' => $path,
            'auth' => $auth
        ));
    }

    public static function pathExists($path)
    {
        if(file_exists($path))
        {
            return true;
        }else{
            return false;
        }
    }

    public static function run($basePath = __DIR__)
    {
        $request = parse_url($_SERVER['REQUEST_URI']);
        if(!array_search($request['path'], array_column(self::$routes, "expression"))){
            self::notFound($basePath);
            return;
        }
   
        foreach(self::$routes as $route)
        {
            if($route['expression'] == $request['path'])
            {
                if(file_exists($basePath.$route['path']))
                {
                    require $basePath.$route['path'];
                }else{
                    self::notFound($basePath);
                }
            }
        }
    }

    public static function notFound($basePath)
    {
        http_response_code(404);
        require $basePath . '/views/404.php';
    }
}
