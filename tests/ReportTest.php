<?php

use Scientist\Result;
use Scientist\Report;

class ReportTest extends PHPUnit_Framework_TestCase
{
    public function test_that_report_can_be_created()
    {
        $r = new Result;
        new Report('foo', $r, array());
    }

    public function test_that_report_can_hold_experiment_name()
    {
        $r = new Result;
        $rp = new Report('foo', $r, array());
        $this->assertEquals('foo', $rp->getName());
    }

    public function test_that_report_can_hold_control_result()
    {
        $r = new Result;
        $rp = new Report('foo', $r, array());
        $this->assertInstanceOf('\Scientist\Result', $rp->getControl());
        $this->assertSame($r, $rp->getControl());
    }

    public function test_that_report_can_hold_trial_result()
    {
        $r = new Result;
        $rp = new Report('foo', $r, array('bar' => $r));
        $this->assertInstanceOf('\Scientist\Result', $rp->getTrial('bar'));
        $this->assertSame($r, $rp->getTrial('bar'));
    }

    public function test_that_report_can_hold_multiple_trial_results()
    {
        $r = new Result;
        $rp = new Report('foo', $r, array('bar' => $r, 'baz' => $r));
        $this->assertInstanceOf('\Scientist\Result', $rp->getTrial('bar'));
        $this->assertInstanceOf('\Scientist\Result', $rp->getTrial('baz'));
        $this->assertSame($r, $rp->getTrial('bar'));
        $this->assertSame($r, $rp->getTrial('baz'));
        $this->assertCount(2, $rp->getTrials());
        $this->assertEquals(array(
            'bar' => $r, 'baz' => $r
        ), $rp->getTrials());
    }
}
