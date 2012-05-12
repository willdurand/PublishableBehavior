<?php

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class PublishableBehaviorTest extends TestCase
{
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
}
