<?php

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class PublishableBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $parameters = array(
        'is_published_column'   => 'is_published',
        'published_by_default'  => 'false',
    );

    /**
     * @var PublishableBehaviorObjectBuilderModifier
     */
    protected $objectBuilderModifier;

    /**
     * @var PublishableBehaviorQueryBuilderModifier
     */
    protected $queryBuilderModifier;

    /**
     * {@inheritdoc}
     */
    public function modifyTable()
    {
        // add the 'is_published' column
        if (!$this->getTable()->containsColumn($this->getParameter('is_published_column'))) {
            $this->getTable()->addColumn(array(
                'name'          => $this->getParameter('is_published_column'),
                'type'          => 'BOOLEAN',
                'defaultValue'  => $this->getParameter('published_by_default'),
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectBuilderModifier()
    {
        if (null === $this->objectBuilderModifier) {
            $this->objectBuilderModifier = new PublishableBehaviorObjectBuilderModifier($this);
        }

        return $this->objectBuilderModifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryBuilderModifier()
    {
        if (null === $this->queryBuilderModifier) {
            $this->queryBuilderModifier = new PublishableBehaviorQueryBuilderModifier($this);
        }

        return $this->queryBuilderModifier;
    }

    public function isPublishedByDefault()
    {
        return 'true' === $this->getParameter('published_by_default');
    }
}
