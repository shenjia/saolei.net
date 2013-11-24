<?php
require_once SIMPLETEST_PATH . '/autorun.php';
require_once SIMPLETEST_PATH . '/web_tester.php';
require_once 'testcase.php';
require_once 'reporter.php';
require_once 'timer.php';

class Tester
{
	public static $basePath;
	public static $lock = false;
    public static $locker = 'test.lock';
    public static $slow_request_threhold = 0.2;

    public function __construct ( $basePath = null, $lock = false )
    {
        SimpleTest::ignore( 'WebTestCase' );
        SimpleTest::ignore( 'ZTestCase' );
        SimpleTest::ignore( 'TestCase' );
        SimpleTest::prefer( new TestHtmlReporter() );
        SimpleTest::prefer( new TestTextReporter() );
        
        self::setBasePath( $basePath );

        if ( $lock ) self::setLock();
        
        $this->run();
    }

    public function run()
    {
        $params = $this->getParams();
        empty( $params ) ? $this->runAllTests()
                         : $this->runTests( $params );
    }

    public function runAllTests()
    {
        foreach ( glob( self::$basePath . '/*' ) as $tests ) {
            if ( is_dir( $tests ) ) new Tests( $tests );
        }
    }

    public function runTests ( $params )
    {
        $tests = explode( ',', current( $params ) );
        foreach ( $tests as $test ) {
        	$name = self::$basePath . '/' . $test;
            is_dir( $name ) ? new Tests( $name )
                            : new Test( $name );
        }
    }

    public static function done()
    {
        self::releaseLock();
        die();
    }
    
	public static function getBasePath()
    {
    	return self::$base_path; 	
    }
    
    public static function setBasePath ( $basePath )
    {
    	self::$basePath = !is_null( $basePath ) ? $basePath : '/tmp';
    }
    
    private static function getLocker()
    {
    	return isset( self::$basePath ) ? self::$basePath . '/' . self::$locker : self::$locker;
    } 
        
    private static function setLock()
    {
    	$locker = self::getLocker();
    	if ( file_exists( $locker ) ) {
            echo 'Test is running by others. Please wait or remove [ ' . $locker . ' ].' . PHP_EOL;
            die();
        }
        touch( $locker );
    	self::$lock = true;
    }

    private static function releaseLock()
    {
    	$locker = self::getLocker();
        if ( self::$lock && is_file( $locker ) ) {
            unlink( $locker );
        }
    }

    private function getParams()
    {
        global $argv;
        return isset( $argv ) ? $this->getParamsFromShell()
                              : $this->getParamsFromBrowser();
    }

    private function getParamsFromShell()
    {
        global $argv;
        array_shift( $argv );
        $this->setSlowRequestThrehold( $argv );
        return $argv;
    }

    private function getParamsFromBrowser()
    {
        $params = array_keys( $_GET );
        $this->setSlowRequestThrehold( $params );
        return $params;
    }

    private function setSlowRequestThrehold ( &$params )
    {
        $threhold = end( $params );
        $threhold = str_replace( '_', '.', $threhold );
        if ( is_numeric( $threhold ) ) {
            self::$slow_request_threhold = (float) $threhold;
            array_pop( $params );
        }
    }
}

class Tests
{
    function __construct( $folder )
    {
        $suite = new TestSuite();
        $suite->collect( $folder . '/', new SimplePatternCollector( '/_test.php/' ) );
    }
}

class Test
{
    function __construct( $file )
    {
        $suite = new TestSuite();
        $suite->addFile( $file . '_test.php' );
    }
}

