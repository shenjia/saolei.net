<?php
require_once __DIR__.'/../../helpers/Parser.php'; 
class MvfParseTest extends TestCase
{
    public function testParseMvfShouldSuccess()
    {
        $parsed = Parser::parse(MVF_PATH.'/normal.mvf');
        $this->assertEqual($parsed['rawvf_version'], 'Rev5');
        $this->assertFalse($parsed['noflag']);        
    }

    public function testParseNoFlagShouldSuccess() 
    {
        $parsed = Parser::parse(MVF_PATH.'/noflag.mvf');
        $this->assertEqual($parsed['rawvf_version'], 'Rev5');
        $this->assertTrue($parsed['noflag']);
    }

    public function testParseNoClassicShouldFailed() 
    {
        $parsed = Parser::parse(MVF_PATH.'/upk.mvf');
        $this->assertEqual($parsed, ErrorCode::parseInvalidMode);
        $parsed = Parser::parse(MVF_PATH.'/density.mvf');
        $this->assertEqual($parsed, ErrorCode::parseInvalidMode);
    }
    
    public function testParseNotFinishedShouldFailed() 
    {
        $parsed = Parser::parse(MVF_PATH.'/notfinish.mvf');
        $this->assertEqual($parsed, ErrorCode::parseNotFinished);
    }

    public function testParseNotExistsFileShouldFailed() 
    {
        $parsed = Parser::parse(MVF_PATH.'/notexists.mvf');
        $this->assertEqual($parsed, ErrorCode::parseFileNotExists);
    }
    
    public function testParseBadFileShouldFailed() 
    {
        $parsed = Parser::parse(MVF_PATH.'/bad.mvf');
        $this->assertEqual($parsed, ErrorCode::parseFailed);
    }
}