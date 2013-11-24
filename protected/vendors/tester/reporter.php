<?php
class TestHtmlReporter extends HtmlReporter
{
    function __construct()
    {
        parent::__construct( 'utf-8' );
    }

    function paintHeader ( $test_name )
    {
        parent::paintHeader( TEST_API_SERVER );
    }

    function paintFooter ( $test_name )
    {
        parent::paintFooter( $test_name );
        Tester::done();
    }
}

class TestTextReporter extends TextReporter
{
    function paintHeader ( $test_name )
    {
        parent::paintHeader( TEST_API_SERVER );
    }

    function paintFooter ( $test_name )
    {
        parent::paintFooter( $test_name );
        Tester::done();
    }
}