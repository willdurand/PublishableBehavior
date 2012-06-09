<?php

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $registered_schemas = array();

    /**
     * add a new publishable schema
     */
    public function addPublishableObject($tablename, $options = array())
    {
        $generator = new PhpNameGenerator();
        $classname = $generator->generateName(array($tablename, PhpNameGenerator::CONV_METHOD_CLEAN));
        $this->registered_schemas[$classname] = $options;
        $this->buildSchemaObject($tablename, $classname, $options);
    }

    protected function buildSchemaObject($tablename, $classname, $options)
    {
        if (!class_exists($classname)) {
            $schema = $this->generateSchema($tablename, $options);

            $builder = $this->getBuilder($schema);

            $builder->build();
        }
    }

    public function assertSQLContains($tablename, $options, $expected)
    {
        $schema = $this->generateSchema($tablename, $options);

        $builder = $this->getBuilder($schema);

        $this->assertContains($expected, $builder->getSQL());
    }

    protected function getBuilder($schema)
    {
        $builder = new PropelQuickBuilder();
        $config  = $builder->getConfig();
        $config->setBuildProperty('behavior.publishable.class', '../src/PublishableBehavior');
        $builder->setConfig($config);
        $builder->setSchema($schema);
        return $builder;
    }

    protected function generateSchema($tablename, $options)
    {
         $optionString = array();
         foreach ($options as $name => $value) {
             $optionString[] = sprintf('<parameter name="%s" value="%s" />', $name, $value);
         }

         $schema = strtr(<<<EOF
<database name="bookstore%suffix%" defaultIdMethod="native">
    <table name="%tablename%">
        <column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />

        <behavior name="publishable">
          %options%
        </behavior>
    </table>
</database>
EOF
        , array(
          '%tablename%' => $tablename,
          '%suffix%'    => get_class($this) . count($this->registered_schemas),
          '%options%'   => join('\n', $optionString)
        ));

        return $schema;
    }

    public function deleteAll($objectClass = null)
    {
        if (empty($objectClass)) {
            foreach($this->registered_schemas as $name => $options) {
                $this->deleteAll($name);
            }
            return ;
        }

        $queryClass = sprintf('%sQuery', $objectClass);
        $queryClass::create()->deleteAll();
    }
}
