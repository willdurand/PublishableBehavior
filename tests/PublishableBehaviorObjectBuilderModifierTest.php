<?php

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class PublishableBehaviorObjectBuilderModifierTest extends TestCase
{
    public function setUp()
    {
        $this->addPublishableObject('publishable_object', array());
        $this->addPublishableObject('published_object', array(
            'published_by_default' => 'true'
        ));

        $this->deleteAll();
    }

    public function testIsPublishedShouldReturnFalseByDefault()
    {
        $obj = new PublishableObject();

        $this->assertFalse($obj->isPublished());
    }

    public function testIsPublished()
    {
        $obj = new PublishableObject();
        $this->assertFalse($obj->isPublished());

        $obj->setIsPublished(true);
        $this->assertTrue($obj->isPublished());

        $obj->setIsPublished(false);
        $this->assertFalse($obj->isPublished());
    }

    public function testIsPublishedShouldReturnTrueIsIsPublishedByDefault()
    {
        $obj = new PublishedObject();

        $this->assertTrue($obj->isPublished());
    }

    public function testIsPublishedWithObjectPublishedByDefault()
    {
        $obj = new PublishedObject();
        $this->assertTrue($obj->isPublished());

        $obj->setIsPublished(true);
        $this->assertTrue($obj->isPublished());

        $obj->setIsPublished(false);
        $this->assertFalse($obj->isPublished());
    }

    public function testPublishNewObject()
    {
        $obj = new PublishableObject();
        $this->assertFalse($obj->isPublished());
        $this->assertTrue($obj->isNew());

        $obj->publish();

        $this->assertTrue($obj->isPublished());
        $this->assertFalse($obj->isNew());

        $this->assertEquals(1, PublishableObjectQuery::create()->count());
    }

    public function testPublishExistingObject()
    {
        $obj = new PublishableObject();
        $this->assertFalse($obj->isPublished());
        $this->assertTrue($obj->isNew());

        $obj->save();
        $this->assertFalse($obj->isPublished());
        $this->assertFalse($obj->isNew());

        $this->assertEquals(0, PublishableObjectQuery::create()->count());

        $obj->publish();
        $this->assertTrue($obj->isPublished());

        $this->assertEquals(1, PublishableObjectQuery::create()->count());
    }

    public function testPublishNewObjectPublishedByDefault()
    {
        $obj = new PublishedObject();
        $this->assertTrue($obj->isPublished());
        $this->assertTrue($obj->isNew());

        $obj->publish();

        $this->assertTrue($obj->isPublished());
        $this->assertFalse($obj->isNew());

        $this->assertEquals(1, PublishedObjectQuery::create()->count());
    }

    public function testPublishExistingObjectPublishedByDefault()
    {
        $obj = new PublishedObject();
        $this->assertTrue($obj->isPublished());
        $this->assertTrue($obj->isNew());

        $obj->save();
        $this->assertTrue($obj->isPublished());

        $this->assertEquals(1, PublishedObjectQuery::create()->count());

        $obj->publish();
        $this->assertTrue($obj->isPublished());

        $this->assertEquals(1, PublishedObjectQuery::create()->count());
    }

    public function testUnpublishWithNewObjectPublishedByDefault()
    {
        $obj = new PublishedObject();
        $this->assertTrue($obj->isPublished());
        $this->assertTrue($obj->isNew());

        $this->assertEquals(1, $obj->unpublish());

        $this->assertFalse($obj->isPublished());
        $this->assertFalse($obj->isNew());
        $this->assertEquals(0, PublishedObjectQuery::create()->count());
    }

    public function testUnpublishWithNewObject()
    {
        $obj = new PublishableObject();
        $this->assertFalse($obj->isPublished());
        $this->assertTrue($obj->isNew());

        $this->assertEquals(1, $obj->unpublish());

        $this->assertFalse($obj->isPublished());
        $this->assertFalse($obj->isNew());
        $this->assertEquals(0, PublishedObjectQuery::create()->count());
    }

    public function testDefaultPublicationOverride()
    {
        $obj = new PublishedObject();
        $obj->setIsPublished(false);
        $obj->save();
        $this->assertEquals(false, $obj->getIsPublished());

        $obj = new PublishableObject();
        $obj->setIsPublished(true);
        $obj->save();
        $this->assertEquals(true, $obj->getIsPublished());
    }
}
