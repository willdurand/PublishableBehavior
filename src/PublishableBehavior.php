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
        'is_published_column'     => 'is_published',
        'published_by_default'    => 'false',
        // timeframe configuration
        'with_timeframe'          => 'false',
        'published_at_column'     => 'published_at',
        'published_until_column'  => 'published_until',
        'require_start'           => 'false',
        'require_end'             => 'false',
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
        $this->ensureColumn($this->getParameter('is_published_column'), array(
            'type'          => 'BOOLEAN',
            'defaultValue'  => $this->getParameter('published_by_default'),
        ));
        if ('true' === $this->getParameter('with_timeframe')) {
            $this->ensureColumn($this->getParameter('published_at_column'), array(
                'type'          => 'TIMESTAMP',
                'required'      => $this->getParameter('require_start')
            ));
            $this->ensureColumn($this->getParameter('published_until_column'), array(
                'type'          => 'TIMESTAMP',
                'required'      => $this->getParameter('require_end')
            ));
        }
    }

    protected function ensureColumn($name, $configuration = array())
    {
        if (!$this->getTable()->containsColumn($name)) {
            $required = false;
            $this->getTable()->addColumn(array_merge($configuration, array(
                'name'          => $name,
            )));
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
