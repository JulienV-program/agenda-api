<?php


namespace App\Tests\entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Event;
use App\Entity\User;
use App\Entity\End;
use App\Entity\Start;



class EventTest extends TestCase
{
    function testSummaryGetterandSetter()
    {
        $event =  new Event();

        $this->assertnull( $event->getSummary());

        $event->setSummary('test');
        $this->assertSame('test', $event->getSummary());

    }

    function testUserGetterandSetter()
    {
        $event =  new Event();

        $this->assertNull( $event->getUser());

        $user = new User();
        $event->setUser($user);
        $this->assertSame($user, $event->getUser());


    }

    function testStartGetterandSetter()
    {
        $event =  new Event();

        $this->assertNull( $event->getStart());

        $start = new Start();
        $event->setStart($start);
        $this->assertSame($start, $event->getStart());
    }

    function test1EndGetterandSetter()
    {
        $event =  new Event();

        $this->assertNull( $event->getEnd());

        $end = new End();
        $event->setEnd($end);
        $this->assertSame($end, $event->getEnd());
    }
}
