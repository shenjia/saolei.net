<?php
class ErrorCode
{
    // Common
    const statSuccess         = 0;   
    const statServerErr       = -1; 
    const statParamErr        = -2; 
    const statAuthErr         = -3;  
    const statDBErr           = -4;
    const statDBNoRecord      = -5;
    
    // Parse
    const parseFailed         = 1000;
    const parseFileNotExists  = 1001;
    const parseInvalidType    = 1002;
    const parseInvalidLevel   = 1003;
    const parseInvalidMode    = 1004;
    const parseInvalidVersion = 1005;
    const parseNotFinished    = 1010;
}