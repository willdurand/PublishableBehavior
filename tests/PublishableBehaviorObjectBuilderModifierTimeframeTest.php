<?php

/**
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 */
class PublishableBehaviorObjectBuilderModifierTimeframeTest extends TestCase
{
    public function setUp()
    {
        $this->addPublishableObject('publishable_simple', array());
        $this->addPublishableObject('publishable_required', array(
            'with_timeframe'  => 'true',
            'require_start'   => 'true',
            'require_end'     => 'true',
        ));
        $this->addPublishableObject('publishable_require_start', array(
            'with_timeframe'  => 'true',
            'require_start'   => 'true',
        ));
        $this->addPublishableObject('publishable_require_end', array(
            'with_timeframe'  => 'true',
            'require_end'     => 'true',
        ));
        $this->addPublishableObject('publishable_timeframe', array(
            'with_timeframe'  => 'true'
        ));

        $this->deleteAll();
    }

    public function requireStartFalseDataProvider()
    {
        return array(
            'publishable_simple' => array('PublishableSimple'),
            'publishable_timeframe' => array('PublishableTimeframe'),
            'publishable_require_end' => array('PublishableRequireEnd'),
        );
    }

    public function requireStartTrueDataProvider()
    {
        return array(
            'publishable_required' => array('PublishableRequired'),
            'publishable_require_start' => array('PublishableRequireStart'),
        );
    }

    public function requireEndFalseDataProvider()
    {
        return array(
            'publishable_simple' => array('PublishableSimple'),
            'publishable_timeframe' => array('PublishableTimeframe'),
            'publishable_require_start' => array('PublishableRequireStart'),
        );
    }

    public function requireEndTrueDataProvider()
    {
        return array(
            'publishable_required' => array('PublishableRequired'),
            'publishable_require_end' => array('PublishableRequireEnd'),
        );
    }

    /**
     * @dataProvider requireStartFalseDataProvider
     */
    public function testRequireStartFalse($class)
    {
        $object = new $class();
        if (method_exists($object, 'setPublishedUntil')) {
            $object->setPublishedUntil(new DateTime('+1 week'));
        }
        $object->save();
    }

    /**
     * @dataProvider requireStartTrueDataProvider
     * @expectedException PropelException
     */
    public function testRequireStartTrue($class)
    {
        $object = new $class();
        if (method_exists($object, 'setPublishedUntil')) {
            $object->setPublishedUntil(new DateTime('+1 week'));
        }
        $object->save();
    }

    /**
     * @dataProvider requireEndFalseDataProvider
     */
    public function testRequireEndFalse($class)
    {
        $object = new $class();
        if (method_exists($object, 'setPublishedAt')) {
            $object->setPublishedAt(new DateTime());
        }
        $object->save();
    }

    /**
     * @dataProvider requireEndTrueDataProvider
     * @expectedException PropelException
     */
    public function testRequireEndTrue($class)
    {
        $object = new $class();
        if (method_exists($object, 'setPublishedAt')) {
            $object->setPublishedAt(new DateTime());
        }
        $object->save();
    }

    public function isPublishedTimeframeDataProvider()
    {
        return array(
            'is over'   => array('-1 week', '-1 day', false),
            'in future' => array('+1 week', '+2 week', false),
            'is now'    => array('-1 week', '+1 week', true)
        );
    }

    /**
     * @dataProvider isPublishedTimeframeDataProvider
     */
    public function testIsPublishedTimeframe($published_at, $published_until, $expected)
    {
        $object = new PublishableTimeframe();
        $object->setPublishedAt($published_at);
        $object->setPublishedUntil($published_until);
        $object->setIsPublished(true);

        $published = $object->isPublished();
        $this->assertInternalType('bool', $published);
        $this->assertEquals($expected, $published);
    }

    /**
     * @dataProvider isPublishedTimeframeDataProvider
     */
    public function testIsPublishedTimeframeButUpublished($published_at, $published_until)
    {
        $object = new PublishableTimeframe();
        $object->setPublishedAt($published_at);
        $object->setPublishedUntil($published_until);
        $object->setIsPublished(false);

        $published = $object->isPublished();
        $this->assertInternalType('bool', $published);
        $this->assertEquals(false, $published);
    }

    public function hasPublicationStartedDataProvider()
    {
        return array(
            'started before'    => array('-1 week', true),
            'now'               => array('now', true),
            'future'            => array('+1 week', false),
        );
    }

    /**
     * @dataProvider hasPublicationStartedDataProvider
     */
    public function testHasPublicationStarted($published_at, $expected)
    {
        $object = new PublishableTimeframe();
        $object->setPublishedAt($published_at);

        $published = $object->hasPublicationStarted();
        $this->assertInternalType('bool', $published);
        $this->assertEquals($expected, $published);
    }

    public function hasPublicationEndedDataProvider()
    {
        return array(
            'ended before'      => array('-1 week', true),
            'now'               => array('now', false),
            'future'            => array('+1 week', false),
        );
    }

    /**
     * @dataProvider hasPublicationEndedDataProvider
     */
    public function testHasPublicationEnded($published_until, $expected)
    {
        $object = new PublishableTimeframe();
        $object->setPublishedUntil($published_until);

        $published = $object->hasPublicationEnded();
        $this->assertInternalType('bool', $published);
        $this->assertEquals($expected, $published);
    }
} // END OF PublishableBehaviorQueryBuilderModifierTimeframeTest
