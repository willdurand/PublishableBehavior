<?php

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class PublishableBehaviorTest extends TestCase
{
    public function setUp()
    {
        $this->addPublishableObject('publishable_object', array());
        $this->addPublishableObject('timeframe_object', array(
          'with_timeframe'  => 'true'
        ));
        $this->addPublishableObject('timeframe_object_different_name', array(
          'with_timeframe'  => 'true',
          'published_at_column'     => 'published_at1',
          'published_until_column'  => 'published_until1',
        ));

        $this->deleteAll();
    }

    public function testObjectMethods()
    {
        $this->assertTrue(method_exists('PublishableObject', 'isPublished'));
        $this->assertTrue(method_exists('PublishableObject', 'publish'));
        $this->assertTrue(method_exists('PublishableObject', 'unpublish'));
        $this->assertFalse(method_exists('PublishableObject', 'getPublishedAt'));
    }

    public function testQueryMethods()
    {
        $this->assertTrue(method_exists('PublishableObjectQuery', 'includeUnpublished'));
        $this->assertTrue(method_exists('PublishableObjectQuery', 'filterPublished'));
        $this->assertTrue(method_exists('PublishableObjectQuery', 'filterUnpublished'));
        $this->assertTrue(method_exists('PublishableObjectQuery', 'publish'));
        $this->assertTrue(method_exists('PublishableObjectQuery', 'unpublish'));
    }

    public function testTimeframeObjectMethods()
    {
        $this->assertTrue(method_exists('TimeframeObject', 'getPublishedAt'));
        $this->assertTrue(method_exists('TimeframeObject', 'getPublishedUntil'));
    }

    public function testTimeframeObjectMethodsDifferentName()
    {
        $this->assertTrue(method_exists('TimeframeObjectDifferentName', 'getPublishedAt1'));
        $this->assertTrue(method_exists('TimeframeObjectDifferentName', 'getPublishedUntil1'));
    }

    public function testSqlTimeframeRequired()
    {
        $options = array(
            'with_timeframe' => 'true',
            'require_start'   => 'true',
            'require_end'     => 'true',
        );
        $expected = <<<EOF
    [published_at] TIMESTAMP NOT NULL,
    [published_until] TIMESTAMP NOT NULL
EOF;
        $this->assertSQLContains('publishable_object', $options, $expected);
    }

    public function testSqlTimeframe()
    {
        $options = array(
            'with_timeframe' => 'true',
        );
        $expected = <<<EOF
    [published_at] TIMESTAMP,
    [published_until] TIMESTAMP
EOF;
        $this->assertSQLContains('publishable_object', $options, $expected);
    }
}
