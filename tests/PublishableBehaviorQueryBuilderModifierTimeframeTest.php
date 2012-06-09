<?php

/**
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 */
class PublishableBehaviorQueryBuilderModifierTimeframeTest extends TestCase
{
    public function setUp()
    {
        $this->addPublishableObject('publishable_simple', array());
        $this->addPublishableObject('publishable_timeframe', array(
            'with_timeframe'  => 'true'
        ));
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
} // END OF PublishableBehaviorQueryBuilderModifierTimeframeTest
