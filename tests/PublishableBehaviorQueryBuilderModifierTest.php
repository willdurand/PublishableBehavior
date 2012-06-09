<?php

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class PublishableBehaviorQueryBuilderModifierTest extends TestCase
{
    private $unpublishedObjects = array();

    private $publishedObjects   = array();

    public function setUp()
    {
        $this->addPublishableObject('publishable_object', array());
        $this->addPublishableObject('published_object', array(
            'published_by_default' => 'true'
        ));

        $this->deleteAll();

        for ($i = 0; $i < 10; $i++) {
            $obj = new PublishableObject();
            $obj->save();
            $this->unpublishedObjects[] = $obj;

            $obj = new PublishableObject();
            $obj->publish();
            $this->publishedObjects[] = $obj;

            $obj = new PublishedObject();
            $obj->save();

            $obj = new PublishedObject();
            $obj->unpublish();
        }
    }

    public function testQueryShouldNotReturnsUnpublishedObjects()
    {
        $results = PublishableObjectQuery::create()
            ->find();

        $this->assertNotNull($results);
        $this->assertEquals(10, count($results));
        $this->assertSame($this->publishedObjects, $results->getData());
    }

    public function testQueryShouldNotReturnsUnpublishedObjectsWithObjectsPublishedByDefault()
    {
        $results = PublishedObjectQuery::create()
            ->find();

        $this->assertNotNull($results);
        $this->assertEquals(10, count($results));

        foreach ($results as $result) {
            $this->assertTrue($result->isPublished());
        }
    }

    public function testIncludeUnpublishedSelectsAllObjects()
    {
        $results = PublishableObjectQuery::create()
            ->includeUnpublished()
            ->find();

        $this->assertNotNull($results);
        $this->assertEquals(20, count($results));
    }

    public function testIncludeUnpublishedSelectsAllObjectsWithObjectsPublishedByDefault()
    {
        $results = PublishedObjectQuery::create()
            ->includeUnpublished()
            ->find();

        $this->assertNotNull($results);
        $this->assertEquals(20, count($results));
    }

    public function testFilterPublished()
    {
        $results = PublishableObjectQuery::create()
            ->filterPublished()
            ->find();

        $this->assertNotNull($results);
        $this->assertEquals(10, count($results));

        foreach ($results as $result) {
            $this->assertTrue($result->isPublished());
        }
    }

    public function testFilterUnpublished()
    {
        $results = PublishableObjectQuery::create()
            ->filterUnpublished()
            ->find();

        $this->assertNotNull($results);
        $this->assertEquals(10, count($results));

        foreach ($results as $result) {
            $this->assertFalse($result->isPublished());
        }
    }

    public function testPublish()
    {
        $results = PublishableObjectQuery::create()
            ->filterUnpublished()
            ->find();

        $this->assertNotNull($results);
        $this->assertEquals(10, count($results));

        foreach ($results as $result) {
            $this->assertFalse($result->isPublished());
        }

        PublishableObjectQuery::create()->publish();

        $results = PublishableObjectQuery::create()
            ->find();

        $this->assertNotNull($results);
        $this->assertEquals(20, count($results));

        foreach ($results as $result) {
            $this->assertTrue($result->isPublished());
        }
    }

    public function testUnpublish()
    {
        $results = PublishableObjectQuery::create()
            ->find();

        $this->assertNotNull($results);
        $this->assertEquals(10, count($results));

        foreach ($results as $result) {
            $this->assertTrue($result->isPublished());
        }

        PublishableObjectQuery::create()->unpublish();

        $results = PublishableObjectQuery::create()
            ->filterUnpublished()
            ->find();

        $this->assertNotNull($results);
        $this->assertEquals(20, count($results));

        foreach ($results as $result) {
            $this->assertFalse($result->isPublished());
        }
    }
}
