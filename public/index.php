<?php 
//echo '<pre>';
//print_r(get_loaded_extensions()); 
//echo '</pre>';

try{
    //Read the configuration
    $config = new Phalcon\Config\Adapter\Ini('../app/config/config.ini');
    
    //Register an autoloaders 
    $loader=new \Phalcon\Loader();
    $loader->registerDirs(array(
        $config->application->controllersDir,
        $config->application->modelsDir,
    ))->register();
    
    //create a DI
    $di=new Phalcon\DI\FactoryDefault();
    
    //Setup the databases service
    $di->set('db',function() use($config){
        return new Phalcon\Db\Adapter\Pdo\Mysql(array(
            $config->database->host,
            $config->database->username,
            $config->database->password,
            $config->database->dbname
        ));
    });
    
    //Setup the view component
    $di->set('view',function() use($config){
        $view=new \Phalcon\Mvc\View();
        $view->setViewsDir($config->application->viewsDir);
        return $view;
    });
    
    //Setup a base URI so that all generated URIs include the project folder
    $di->set('url',  function () use($config){
        $url=new \Phalcon\Mvc\Url();
        $url->setBaseUri($config->application->baseUri);
        return $url;
    });
    

    //Handle the request
    $application=new \Phalcon\Mvc\Application($di);
    echo $application->handle()->getContent();
    
} catch (\Phalcon\Exception $e) {
    echo "PhalconException: ".$e->getMessage();
}