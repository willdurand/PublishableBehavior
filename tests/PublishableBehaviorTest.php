<?php

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class PublishableBehaviorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('PublishableObject')) {
            $schema = <<<EOF
<database name="bookstore" defaultIdMethod="native">
    <table name="publishable_object">
        <column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />

        <behavior name="publishable" />
    </table>

    <table name="published_object">
        <column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />

        <behavior name="publishable">
            <parameter name="published_by_default" value="true" />
        </behavior>
    </table>
</database>
EOF;
            $builder = new PropelQuickBuilder();
            $config  = $builder->getConfig();
            $config->setBuildProperty('behavior.publishable.class', '../src/PublishableBehavior');
            $builder->setConfig($config);
            $builder->setSchema($schema);

            $builder->build();
        }

        PublishableObjectQuery::create()->deleteAll();
        PublishedObjectQuery::create()->deleteAll();
    }

    public function testObjectMethods()
    {
        $this->assertTrue(method_exists('PublishableObject', 'isPublished'));
        $this->assertTrue(method_exists('PublishableObject', 'publish'));
        $this->assertTrue(method_exists('PublishableObject', 'unpublish'));
    }

    public function testQueryMethods()
    {
        $this->assertTrue(method_exists('PublishableObjectQuery', 'includeUnpublished'));
        $this->assertTrue(method_exists('PublishableObjectQuery', 'filterPublished'));
        $this->assertTrue(method_exists('PublishableObjectQuery', 'filterUnpublished'));
        $this->assertTrue(method_exists('PublishableObjectQuery', 'publish'));
        $this->assertTrue(method_exists('PublishableObjectQuery', 'unpublish'));
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
}
