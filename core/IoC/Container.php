<?php


class Container
{
    // TODO may use bitwise flags
    public const REG_TYPE_NORMAL = 0x01;
    public const REG_TYPE_SINGLETON = 0x02;
    public const REG_TYPE_NON_LAZY_SINGLETON = 0x03;
    public const REG_TYPE_APPLICATION_SINGLETON = 0x04;
    public const REG_TYPE_NON_LAZY_APPLICATION_SINGLETON = 0x05;

    /**
     * @var array
     * collection = ["interface_name" => [["class_name","REG_TYPE"]] ]
     */
    private $_collection = array(); // array of array :: allow multiple

    // we will also allow multiple singleton detentions
    // TODO : Check duplication type
    private $_local_singletons = array();

    private $built = false;

    public function build() : self {

        // init all non_lazy_singletons // O(N^2)
        foreach ($this->_collection as $interface_name => $interface_array){
            foreach ($interface_array as $numeric_key => $interface){
                [$class, $registerType] = $interface;
                if ($registerType === self::REG_TYPE_NON_LAZY_SINGLETON) {
                    $_local_singletons[$interface][$class] =
                        $this->_resolve($interface, $interface_name);
                } elseif ($registerType === self::REG_TYPE_NON_LAZY_APPLICATION_SINGLETON) {
                    $this->add_to_apc($interface_name, $class,
                        $this->_resolve($interface, $interface_name));
                }
            }
        }
        $this->built = true;
        return $this;
    }

    // return all implementations of interface
    public function all_implementations(string $interface) : array {
        $imps = [];
        foreach ($this->_collection[$interface] as $key => $value){
            [$class, $registerType] = $value;
            if ($registerType === self::REG_TYPE_NON_LAZY_SINGLETON || $registerType === self::REG_TYPE_SINGLETON){
                $imps[] =  $this->_local_singletons[$interface][$class];
            } else{
                $imps[] = $this->_resolve($value, $interface);
            }
        }
        return $imps;
    }

    public function resolve($interface) : object {
        if (!$this->built){
            throw new RuntimeException('You Must Build The Container Before Resolving Any Dependency');
        }
        // resolve itself
        if ($interface === self::class){
            return $this;
        }
        //            print_line($interface);
        // [$class , $registerType, $params]
        // array values is mutable so we need to clone it , to kept the
        // so we will clone it with list() or []
        if (!isset($this->_collection[$interface])){
                throw new RuntimeException('No Register Type For '  . $interface);
        }

        return $this->_resolve($this->_collection[$interface][0], $interface);
    }
    public function _resolve(array $interface_meta, string $interface_name) : object {

        // we will only get the fist implementation
        [$class, $registerType, $params] = $interface_meta;

        // already inits in build()
        if ($registerType === self::REG_TYPE_NON_LAZY_SINGLETON || $registerType === self::REG_TYPE_SINGLETON){
            if (isset($this->_local_singletons[$interface_name][$class])) {
                return $this->_local_singletons[$interface_name][$class];
            }
        }

        elseif ($registerType === self::REG_TYPE_APPLICATION_SINGLETON
            || $registerType === self::REG_TYPE_NON_LAZY_APPLICATION_SINGLETON){
            $o = $this->get_from_apc($interface_name, $class);
            if ($o !== null){
                return $o;
            }
        }

        // will contains all params we will pass to the constructor
        $passed_params = array();

        try {
            $ref = new ReflectionClass($class);
            if ($ref->getConstructor()){
                $con_params = $ref->getConstructor()->getParameters();
                if ($con_params){
                    // check if param name in $params
                    foreach ($con_params as $key => $value){
                        if (array_key_exists($value->getName(), $params)){
                            $passed_params[] = $params[$value->getName()];
                        } else {
                            // TODO we may check if type here is not partitive

                            $type = $value->getClass()->getName();
                            $passed_params[] = $this->resolve($type);
                        }
                    }
                    // create
                    $obj = $ref->newInstanceArgs($passed_params);
                    if ($registerType === self::REG_TYPE_SINGLETON){
                        $this->_local_singletons[$interface_name][$class] = $obj;
                    } elseif ($registerType === self::REG_TYPE_APPLICATION_SINGLETON) {
                        $this->add_to_apc($interface_name, $class, $obj);
                    }
                    return $obj;
                }
            }
            $obj = $ref->newInstance();
            if ($registerType === self::REG_TYPE_SINGLETON){
                $this->_local_singletons[$interface_name][$class] = $obj;
            } elseif ($registerType === self::REG_TYPE_APPLICATION_SINGLETON) {
                $this->add_to_apc($interface_name, $class, $obj);
            }
            return $obj;

        } catch (ReflectionException $e) {
            echo 'Error in reflection'; // we must throw exception here
        }
        return null;
    }


    public function register(string $interface, string $class, $params = [],
                             $registerType = Container::REG_TYPE_NORMAL) : void {
        if (!$this->can_substitute($class, $interface)) {
            throw new RuntimeException('Provided class must implements provided interface');
        }

        if ($this->provided($interface, $class)){
            throw new RuntimeException('You already define this combination');
        }
        switch ($registerType){
            case self::REG_TYPE_SINGLETON :
                $this->register_singleton($interface, $class, $params);
                break;
            case self::REG_TYPE_NON_LAZY_SINGLETON :
                $this->register_singleton($interface, $class, $params,false);
                break;
            case self::REG_TYPE_APPLICATION_SINGLETON :
                $this->register_application_scope($interface, $class, $params);
                break;
            default:
                $this->register_normal($interface, $class, $params);
        }
    }

    public function provide(string $class, $params = [], $registerType = Container::REG_TYPE_NORMAL) : void{
        $this->register($class, $class, $params, $registerType);
    }

    private function can_substitute($class, $interface) : bool {
        try {
            if($class === $interface){
                return true;
            }
            $ref = new ReflectionClass($class);
            return $ref->implementsInterface($interface);
        } catch (ReflectionException $e) {
        }
        return false;
    }

    private function provided(string $interface, string $class): bool
    {
        if (!isset($this->_collection[$interface])){
            return false;
        }
        if ($this->in_collection($interface, $class)){
            return true;
        }
        return false;
    }

    private function in_collection(string $interface, string $class) : bool {
        $all = $this->_collection[$interface];
        foreach ($all as $key => $value) {
            [$cls] = $value;
            if($class === $cls) {
                return true;
            }
        }
        return false;
    }

    private function internal_registration(string $interface, string $class, $registerType , $params = []) : void {
        if (!isset($this->_collection[$interface])){
            $this->_collection[$interface] = array();
        }
        $this->_collection[$interface][] = [$class , $registerType, $params];
        // instead of array_push , for legacy developers :)
    }
    private function register_normal(string $interface, string $class, $params = []) : void {
        $this->internal_registration($interface, $class,self::REG_TYPE_NORMAL, $params );
    }

    // lazy loading
    private function register_singleton(string $interface, string $class, $params = [], $lazy = true) : void  {
        if ($lazy){
            $this->internal_registration($interface, $class,self::REG_TYPE_SINGLETON, $params );
        } else{
            $this->internal_registration($interface, $class,self::REG_TYPE_NON_LAZY_SINGLETON, $params );
        }
        if (!isset($_local_singletons[$interface])){
            $_local_singletons[$interface] = array();
        }
    }
    private function register_application_scope(string $interface, string $class, $params = [], $lazy= true) : void {

        $this->internal_registration($interface, $class,
            $lazy ? self::REG_TYPE_APPLICATION_SINGLETON : self::REG_TYPE_NON_LAZY_APPLICATION_SINGLETON ,
            $params );

        // add to apc
        if (!apc_exists($interface)){
            apc_store($interface, array());
        }
        // we may drop multiple definitions of application scope in the future
        $arr = apc_fetch($interface);
        // $arr[] = $class; // resolve
        apc_store($arr);
    }

    private function add_to_apc(string $interface, string $class, object $obj) : void {
        if (!apc_exists($interface)){
            // may set application prefix
            apc_store($interface, array());
        }
        $arr = apc_fetch($interface);
        $arr[$class] = $obj;
        apc_store($interface, $arr);
    }

    private function get_from_apc (string $interface, string $class) : object {
        if (!apc_exists($interface)){
            return null;
        }
        $arr = apc_fetch($interface);
        if (!isset($arr[$class])){
            return null;
        }
        return $arr[$class];
    }


}
