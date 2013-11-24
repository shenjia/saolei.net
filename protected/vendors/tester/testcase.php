<?php
abstract class ZTestCase extends WebTestCase
{
    const STATE = 'state';
    const PARAM_TYPE = 'string';
    const RESPONSE_TYPE = 'string';
    const SKIP = false;
    const SUCCESS = 1;

    protected $fields = array();

    public function skip()
    {
        $this->skipIf( static::SKIP );
    }

    public function getAPI ( $route = null, $params = array() )
    {
        $timer = new Timer();
        if ( empty( $params ) && is_array( $route ) ) $params = $route;
        $route = is_string( $route ) ? $route : $this->getRoute();	
        $response = parent::get( TEST_API_SERVER . $route, $this->appendParams( $params ) );
        $cost = $timer->stop( false );

        if ( $cost >= Tester::$slow_request_threhold ) {
            $this->reporter->paintFormattedMessage( 'get [ ' . $route . ' ] cost ' . $cost . 's.' );
        }
        return $this->parseResponse( $response );
    }

    public function postAPI ( $route = null, $params = array() )
    {
        $timer = new Timer();
        if ( empty( $params ) && is_array( $route ) ) $params = $route;
        $route = is_string( $route ) ? $route : $this->getRoute();	
        $response = parent::post( TEST_API_SERVER . $route, $this->appendParams( $params ) );
        $cost = $timer->stop( false );

        if ( $cost >= Tester::$slow_request_threhold ) {
            $this->reporter->paintFormattedMessage( 'post [ ' . $route . ' ] cost ' . $cost . 's.' );
        }
        return $this->parseResponse( $response );
    }

    public function assertSuccess ( $response, $fields = array() )
    {
        $success = $this->assertEqual( $response[ static::STATE ], static::SUCCESS, 'Should success but failed.' );
        if ( !$success ) {
            $this->dumpResponse( $response );
        } else if ( $fields ) {
            $success = $this->assertFields( $this->getFields( $response ), $fields );
        } else if ( $this->fields ) {
            $success = $this->assertFields( $this->getFields( $response ), $this->fields );
        }
        return $success;
    }

    public function assertFail ( $response, $code = null )
    {
    	$fail = $this->assertNotEqual( $response[ static::STATE ], static::SUCCESS, 'Should fail but successed.' );
        if ( !$fail ) {
            $this->dumpResponse( $response );
        } else if ( $code ) {
            $this->assertEqual( $response[ static::STATE ], $code, 'Should return code [' . $code . '] but [' . $response[ static::STATE ] . '].' );
        }
        return $fail;
    }

    public function assertFields ( $data, $fields = array() )
    {
        $success = true;
        $dump = false;
        foreach ( $fields as $field => $type ) {
            //不传类型则应用默认类型
            if ( is_numeric( $field ) ) {
                $field = $type;
                $type = static::PARAM_TYPE;
            }
            //判断是否必须
            if ( strstr( $field, '?' ) ) {
                if ( !isset( $data[ $field ] ) ) continue;
                $field = str_replace( '?', '', $field );
            };
            //如果缺少字段则输出
            if ( !$this->assertTrue( isset( $data[ $field ] ), 'Field "' . $field . '" missed.' ) ) {
                $success = false;
                $dump = true;
            }
            //如果有指定检查方法则执行
            else if ( is_string( $type ) && method_exists( $this, 'assert' . ucfirst( $type ) ) ) {
                $success = $success && $this->{ 'assert' . ucfirst( $type ) }( $data[ $field ] );
            }
            //检查定义的字段
            else if ( isset( $this->{ $type . '_fields' } ) || is_array( $type ) ) {
            	$type_fields = is_array( $type ) ? $type : $this->{ $type . '_fields' };
                if ( !empty( $data[ $field ] ) ) {
                    $item = current( $data[ $field ] );
                    $success = $success && $this->assertFields( $item, $type_fields );
                } else {
                    $this->reporter->paintSkip( ucfirst( $type ) . ' is empty.' );
                }
            }
            //如果类型错误则输出
            else if ( !$this->assertTrue( $type == gettype( $data[ $field ] ), 'Field "' . $field . '" should be a ' . ucfirst( $type ) . ', ' . ucfirst( gettype( $data[ $field ] ) ) .  ' given.' ) ) {
                $dump = true;
            }
        }
        if ( $dump ) $this->dump( $data );
        return $success;
    }
    
    public function assertNumber ( $data )
    {
        $this->assertTrue( is_numeric( $data ), 'Should be number but not.' );
    }
    
    public function assertJson ( $data )
    {
        $this->assertTrue( !is_null( json_decode( $data ) ), 'Should be JSON but not.' );
    }

    public function assertEmpty ( $data )
    {
        if ( !$this->assertTrue( empty( $data ), 'Should empty but not.' ) ) {
            $this->dump( $data );
        }
    }

    public function assertNotEmpty ( $data )
    {
        if ( !$this->assertFalse( empty( $data ), 'Should not empty but it is.' ) ) {
            $this->dump( $data );
        }
    }

    public function parseResponse ( $response )
    {
        $parser = 'parseResponseAs' . ucfirst( static::RESPONSE_TYPE );
        return method_exists( $this, $parser ) ? $this->$parser( $response ) : $response;
    }

    public function parseResponseAsString ( $response )
    {
        return $response;
    }

    public function parseResponseAsJson ( $response )
    {
        $parsed = json_decode( $response, true );
        if ( !$this->assertTrue( is_array( $parsed ), 'Should return JSON string.' ) ) {
            $this->dumpResponse( $response );
        }
        return $parsed;
    }

    protected function appendParams ( $params )
    {
        return $params;
    }

    protected function dumpResponse ( $response )
    {
        $this->dump( $this->getBrowser()->getUrl() );
        $this->dump( $response );
    }

    protected function getFields ( $response )
    {
        return $response;
    }

    protected function getRoute()
    {
        //如有指定ROUTE则返回，否则从类名生成ROUTE
        return defined( get_class( $this ) . '::ROUTE' ) 
        	? constant( get_class( $this ) . '::ROUTE' ) 
        	: $this->generateRoute();
    }

    protected function generateRoute()
    {
        $name = $this->getTestName();
        $parts = preg_split( '/(?=[A-Z])/', $name );
        return strtolower( implode( '/', $parts ) );
    }
    
    protected function getTestName()
    {
    	$class = get_class( $this );
        return str_replace( 'Test', '', $class );
    }
}
