<?php
namespace Aura\Filter\Rule;

class UploadTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_UPLOAD';
    
    public function providerIs()
    {
        return [
            [],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [],
        ];
    }
    
    public function providerFix()
    {
        return [
            ['',''],
        ];
    }
}
