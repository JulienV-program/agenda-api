<?php


namespace App\Tests\entity;

use App\Entity\Event;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    function testEmailGetterandSetter()
    {
        $user = new User();

        $this->assertNull($user->getEmail());

        $user->setEmail('test@admin.com');
        $this->assertSame('test@admin.com', $user->getEmail());
    }

    function testEventGetterandSetter()
    {
        $user = new User();

        $this->assertTrue($user->getEvents()->isEmpty());

        $event = new Event();
        $user->addEvent($event);
        $this->assertContains($event, $user->getEvents());

        $user->removeEvent($event);
        $this->assertFalse($user->getEvents()->contains($event));
    }
}
