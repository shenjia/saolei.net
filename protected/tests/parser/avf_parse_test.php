<?php
require_once __DIR__.'/../../helpers/Parser.php'; 
class AvfParseTest extends TestCase
{
    public function testParseAvfShouldSuccess()
    {
        $parsed = Parser::parse(AVF_PATH.'/normal.avf');
        $this->assertEqual($parsed['rawvf_version'], 'Rev5');
        $this->assertFalse($parsed['noflag']);        
    }
    
    public function testParseNoFlagShouldSuccess() 
    {
        $parsed = Parser::parse(AVF_PATH.'/noflag.avf');
        $this->assertEqual($parsed['rawvf_version'], 'Rev5');
        $this->assertTrue($parsed['noflag']);
    }
    
    public function testParseNotFinishShouldFail() 
    {
        $parsed = Parser::parse(AVF_PATH.'/notfinish.avf');
        $this->assertEqual($parsed, ErrorCode::parseNotFinished);
    }
    
    public function testParseNotExistsFileShouldFailed() 
    {
        $parsed = Parser::parse(AVF_PATH.'/notexists.avf');
        $this->assertEqual($parsed, ErrorCode::parseFileNotExists);
    }  
    
    public function testParseBadFileShouldFailed() 
    {
        $parsed = Parser::parse(AVF_PATH.'/bad.avf');
        $this->assertEqual($parsed, ErrorCode::parseFailed);        
    }
}