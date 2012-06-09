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

    protected function createObjects($class, $data, $published)
    {
        foreach ($data as $values) {
            $o = new $class();
            $o->setIsPublished($published);
            $o->setPublishedAt($values[0]);
            $o->setPublishedUntil($values[1]);
            $o->save();
        }
    }

    public function simplePublishTimeframeDataProvider()
    {
        $data = array(
            array('-1 week', '+1 week', 1),
            array('-1 day', '+1 day', 1),
            array('-1 week', '-1 day', 0),
            array('+1 day', '+1 week', 0),
        );
        $classes = array('PublishableTimeframe', 'PublishableRequired', 'PublishableRequireStart', 'PublishableRequireEnd');

        $return = array();
        foreach ($classes as $class) {
            foreach ($data as $row) {
                $return [] = array_merge(array($class), $row);
            }
        }

        return $return;
    }

    /**
     * @dataProvider simplePublishTimeframeDataProvider
     */
    public function testSimplePublishTimeframe($class, $start, $end, $expected)
    {
        $this->createObjects($class, array(array($start, $end)), true);
        $queryName = $class.'Query';
        $query = $queryName::create()
            ->includeUnpublished()
            ->filterByPublicationActive();

        $this->assertEquals($expected, $query->count());
    }

    public function isPublishedTimeframeDataProvider()
    {
        return array(
            'simple dataset'   => array(
                'PublishableTimeframe',
                array(
                    array('-1 week', '+1 week'),
                    array('-1 week', '+1 day'),
                    array('-2 week', '-1 week'),
                    array('+1 day', '+1 week'),
                    array('+1 day', '+1 week'),
                ),
                2
            ),
            'no matche'         => array(
                'PublishableTimeframe',
                array(
                    array('-2 week', '-1 week'),
                    array('+1 day', '+1 week'),
                ),
                0
            ),
            'all match'         => array(
                'PublishableTimeframe',
                array(
                    array('-1 week', '+1 week'),
                    array('-1 week', '+1 day'),
                    array('-2 week', '+3 week'),
                ),
                3
            ),
            'Normal: simple dataset'   => array(
                'PublishableTimeframe',
                array(
                    array('-1 week', '+1 week'),
                    array('-1 week', '+1 day'),
                    array('-2 week', '-1 week'),
                    array('+1 day', '+1 week'),
                    array('+1 day', '+1 week'),
                ),
                2
            ),
            'REQUIRE END: simple dataset'   => array(
                'PublishableRequireEnd',
                array(
                    array('-1 week', '+1 week'),
                    array('-1 week', '+1 day'),
                    array('-2 week', '-1 week'),
                    array('+1 day', '+1 week'),
                    array('+1 day', '+1 week'),
                ),
                2
            ),
            'REQUIRE START: simple dataset'   => array(
                'PublishableRequireStart',
                array(
                    array('-1 week', '+1 week'),
                    array('-1 week', '+1 day'),
                    array('-2 week', '-1 week'),
                    array('+1 day', '+1 week'),
                    array('+1 day', '+1 week'),
                ),
                2
            ),
            'REQUIRED: simple dataset'   => array(
                'PublishableRequired',
                array(
                    array('-1 week', '+1 week'),
                    array('-1 week', '+1 day'),
                    array('-2 week', '-1 week'),
                    array('+1 day', '+1 week'),
                    array('+1 day', '+1 week'),
                ),
                2
            ),
            'REQUIRED: no matche'         => array(
                'PublishableRequired',
                array(
                    array('-2 week', '-1 week'),
                    array('+1 day', '+1 week'),
                ),
                0
            ),
            'REQUIRED: all match'         => array(
                'PublishableRequired',
                array(
                    array('-1 week', '+1 week'),
                    array('-1 week', '+1 day'),
                    array('-2 week', '+3 week'),
                ),
                3
            ),
            'REQUIRED: simple match'         => array(
                'PublishableRequired',
                array(
                    array('-1 day', '+1 week'),
                ),
                1
            ),
            'match unrequired end' => array(
                'PublishableTimeframe',
                array(
                    array(null, null),
                    array('-1 week', null),
                    array('+1 week', null),
                    array('-2 week', null),
                ),
                3
            ),
            'match unrequired end and start' => array(
                'PublishableTimeframe',
                array(
                    array(null, null),
                ),
                1
            ),
            'match unrequired end started' => array(
                'PublishableTimeframe',
                array(
                    array('-1 week', null),
                ),
                1
            ),
            'match unrequired end not started' => array(
                'PublishableTimeframe',
                array(
                    array('+1 week', null),
                ),
                0
            ),
            'match unrequired start' => array(
                'PublishableTimeframe',
                array(
                    array(null, '+1 week'),
                    array(null, '-1 week'),
                    array('-1 week', '+1 day'),
                    array('-2 week', '+3 week'),
                ),
                3
            ),
            'match unrequired start and not ended' => array(
                'publishabletimeframe',
                array(
                    array(null, '+1 week'),
                ),
                1
            ),
            'match unrequired start ended' => array(
                'PublishableTimeframe',
                array(
                    array(null, '-1 week'),
                ),
                0
            ),
        );
    }

    /**
     * @dataProvider isPublishedTimeframeDataProvider
     */
    public function testIsPublishedTimeframe($objectClass, $data, $count)
    {
        $this->createObjects($objectClass, $data, true);
        $queryName = $objectClass.'Query';
        $query = $queryName::create()
            ->includeUnpublished()
            ->filterByPublicationActive();

        $this->assertEquals($count, $query->count());
    }
} // END OF PublishableBehaviorQueryBuilderModifierTimeframeTest
