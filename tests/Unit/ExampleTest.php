<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $table = convertJsonToMarkdownTable('{"_id":"641971e0559d700574e0aaa7","automationId":"MhwaKyN8AxI96lGlcmM3JUndHvFzc45F","nodeId":"root","createTime":"2023-03-21T08:59:12.596+0000","finishedTime":"2023-03-21T08:59:12.769+0000","enterpriseId":5072,"userId":236988,"nodeName":"root","executeStatus":"EXECUTED","nodeType":"ROOT","executionId":"484fa34945b142d19ebf24679a496201","processInstanceId":"484fa34945b142d19ebf24679a496201","contextData":{"triggerName":"新增触发","form":{"formId":"5e7792cd-f400-43db-a246-70ced9853198","formInstanceId":"beffe072cd20477492e87bd599c3d26f","enterpriseId":5072,"creatorId":236988,"operatorId":236988,"creatorName":"谢小勤","operatorName":"谢小勤","_class":"com.yunsom.mid.business.automation.runtime.form.impl.FormImpl"},"_class":"com.yunsom.mid.business.automation.runtime.engine.impl.AutTriggerEvtImpl"},"version":5,"tranceId":"116983d7-ea71-4f0f-97a9-9fd047647b83","triggerMethod":1,"order":1,"submitTest":2,"_class":"com.yunsom.mid.business.automation.runtime.dao.entity.AutomationLog"}');
        echo $table;
    }
}
