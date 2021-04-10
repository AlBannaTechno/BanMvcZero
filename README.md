# BanMvcZero
PHP MVC Framework That Porting Some Of [C# Asp.net Core] Features, philosophies To PHP

----------------------------------------------

### Hi , Please Notice : This Framework is just a small piece of [BanMvc](https://github.com/AlBannaTechno/PHP-BanMVC) framework `I will publish it soon` which will contains
Most of Asp.net Core Features , by using Annotations instead of C# Attribute Syntax , Both BanMvc and BanMvcZero , Have zero
Dependency `every thing built from the ground including annotation system`

-----------------------------------


#### So What `BanMvcZero` Exactly is 

This is a Simple Porting for some of asp.net core features With MVC Design Paradigm

#### Features
* Everything can customize including : project directory structure
* Supports Routing Via On Of the next paradigms or all `can customize the order of resolving`
    * Area System 
        * Area 
          * Models : `AreaModelBase`
          * Views
            * View Customization Files `_layout`
          * Controllers : `AreaControllerBase`
          * Area Customization File
            * general layout
              * can be overridden by Views layout
              * Will override general project layout if this exists
            * defaults configurations
    
    * Controller System
        * Controllers : `ControllerBase`
            * Actions `methods`
                * auto map parameters `only ordered parameters` , `query param only supported in BanMvc not Zero version`
                * auto invoke any user defined class from method `vi IoC System`
                * load models from action or with controller constructor
                    * it's more efficient to load models only if you need it `in action`
        * Models : `ModelBase`
        
        * Views
            * View Customization Files `_layout`
    
    * Pages System
        * Resolve Pages Structure and map it to url
    * Notice 
        * complex url mapping `eg /Customers/{custooerID}/Payment/{prs}/buy/34?role=22&rec=PLV` not supported in `Zero`
            * required abstract layer around the project : `so we implement it on BanMvc project with annotations`
        * All config files eg , `_defaults.php, _layout.php` are based on Most Nearest Match `MNM` looping behaviour
            * so if our system start from areas => controllers => pages
            and we we locate `customers` controller, the resolver will use `_defaults.php` from Customers Directory
            instead of `_defaults.php` in Areas Directory, and the same for view 
               * just remember the main `_layout.php` located at `views.dir`
            Also you must know, due to `MNM` if config file removed from Areas/Controllers level, then the resolver
            will use config files from Areas Level, if not exists, then from base site level `eg, views/_layout.php`
            `So it is a reversed hierarchy system`
            
    
* DI System : `The Implementation of IoC` , `core/Ioc/Container.php`
    * Please Notice : Since very high limitation level in PHP connection system philosophy
    we can not implement All IoC features  of asp.net core Container `DI System` because of php delegate request/server/connection
    controlling to out of the box servers eg. apache without any ability to access it even with CGI porting solutions,
    So we can not do any controlling on per-request features of asp.net core at all
    But we implement wrapper around new [APC](https://www.php.net/manual/en/book.apc.php) storage system of PHP 
    So we can now register the singletons for all connections .
    
    * Features
        * As any IoC system : registering any with interface or with the same type `direct implementation`
        * Multiple Implementations
            * For Direct Invocation behaviour : we used first match paradigm
                so the first registered implementation will used
            * You can still get all implementations
            * You can specific any implementation of multiple implementations with any type `eg, Wide Application Singleton {server}`
            * You can use any features available when you register single implementation
        * Ability to pass default `named` parameters to pass it to constructors with instantiation
        * Ability to use factory to resolve/create new instance at runtime
            * The main purpose of implementing this features to solve static singleton design paradigm problem
                since it's considered as an anti pattern , so when using factory pattern around it you can easily
                mocking it within testing by changing the factory or provide it with pre complex test environment 
                configurations
        
        * Invoke Method of object
            * With Resolving all method dependencies
            * With Mapping inputs => parameters : `for pararmeters that container will not resolve eg, premitive types`       
        * Available Registration Systems
            * Normal : `REG_TYPE_NORMAL`
                * Create new instance with every request
            * Singleton `Lazy` : `REG_TYPE_SINGLETON`
                * Request type singleton : one instance per request
                * This Singleton is lazy , so will be initiated only with the most first request after 
                    building the container
                * The Best for performance and memory usage 
            * Singleton `Non Lazy` : `REG_TYPE_NON_LAZY_SINGLETON`
                * Singleton but will be initiated while building the container
            * Application Singleton `Lazy` : `REG_TYPE_APPLICATION_SINGLETON`
                * Share the same instance across all connections : `No notification system due to php limitations`
            * Application Singleton `Non Lazy` : `REG_TYPE_NON_LAZY_APPLICATION_SINGLETON`
            * Factory : `REG_TYPE_FACTORY`
                * `We Explained it `
    
    * Final Notes `Technical`
        * The Main purposes of implementing IoC design principle : 
        * For Development : 
            * Support `Liskov substitution principle/ability` for unit testing
            * Escape From Constructor `new keyword` hell 
            * provide dynamic renovation
        * For Runtime
            * Provide lightweight and efficient flexible system , by resolving , building type maps
            once and then use it every where
                * `The Problem` : here is the most confusing problem for php developers
                Since php handler `controller server eg, appahce` will run single instance for every request
                so we need to build the type tree again and again , so it's not efficient at all to
                use dynamic tree building paradigms with PHP
                And the most elegant solution is to use a static analysis system to solve this problem
                    * `SSS` : means for every change in project hierarchy we will execute script
                    to go through our project and resolve all types , locations , then register it
                    in php syntax in file in our project
                    Then at runtime the project will load it and store it in the memory
                        * some developers hat this behaviour , but if you will use php in High performance environment
                        `it's ever will not be my choice` you must use static analysis or delegating routing system
                        to any abstraction layer `eg, c/c++ server` to work as a proxy between php and it's handler
                        `i dont prefer this solution since it's will not pay for its complexity [Not in PHP use cases]`
                 
            

* DataBase Supporting
    * The `Zero` version does not aims to support ORM Solutions So : there is only one wrapper class
    around PDO of php to enable providing it into DI system of this project
    This class at `database/PdoDatabase.php` 
    
    * `BanMvc` will support ORM Solution

### Project Structure

* core : `Contains All Project Core`
* public : `Contains your site start point & public resources`
    * we route every thing via `index.php` as a proxy to `CoresProvider`
    because of php does not support routing `its delegate routing to hosting server` we can not get the same
    performance of asp.net core.... or any other routing systems `we need the delay between server and php instance`
* site : `Your site`
    * Take a look at `register.php`
    * Also you must provide `Secrets.php` or `name it what you want in core/config/config.php`
        * This file should contains all secret keys  : `so we can not provide it to VCS`
        * This Must Contains Next Constants to use PdoDatabase class , like next example
            ```php
          define('DB_HOST', 'localhost');
          define('DB_USER', 'root');
          define('DB_PASS', 'Pa$$w0rd');
          define('DB_NAME', 'database');
            ```
* All [.htaccess](https://www.linode.com/docs/web-servers/apache/how-to-set-up-htaccess-on-apache/) files
    * used to configure routing system start point for apache server
    * file `public/.htaccess`
        * you must change `RewriteBase /BanMVC/public` to `RewriteBase /your/public`
            * so configure it to your project relative path from the server/handler

#####  Finally

* To use this project : just take a tour at `site/` and `public/` to understand how to use it
* To Develop it or for deep understanding go with `core/`

* For `BanMvc` name : its just a funny name i combine first part of may family name with `MVC`
    * `Osama Al Banna` => `[Osama] {Al = The : in arabic} [{Ban}na] ` Mvc `:)`

* Please Notice: PHP does not first class oop language so neither \[we/nor any one] can force developers to go with specific paradigm
and language design itself force us to wrap functional behaviour with oop containers but this may leads to un necessary
level of complexity , so you may find us in `core/Helpers/*` use functions with namespaces to support our oop system
so this should not make you confuse about the system , its is the PHP oriented design patter `spaghetti IMHO`
