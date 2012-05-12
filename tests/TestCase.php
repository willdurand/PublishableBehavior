<?php

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class TestCase extends \PHPUnit_Framework_TestCase
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
}
