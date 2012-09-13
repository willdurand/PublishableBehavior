<?php

class PublishableBehaviorQueryBuilderModifier
{
    /**
     * @var PublishableBehavior
     */
    private $behavior;

    public function __construct(Behavior $behavior)
    {
        $this->behavior = $behavior;
    }

    public function preSelectQuery($builder)
    {
        return $this->behavior->renderTemplate('queryPreSelect');
    }

    public function queryAttributes($builder)
    {
        return "protected \$includeUnpublished = false;
";
    }

    public function queryMethods($builder)
    {
        $script  = '';
        $script .= $this->addFilterPublished($builder);
        $script .= $this->addFilterUnpublished($builder);
        $script .= $this->addFilterByPublicationActive($builder);
        $script .= $this->addIncludeUnpublished($builder);
        $script .= $this->addPublish($builder);
        $script .= $this->addUnpublish($builder);

        return $script;
    }

    public function addFilterPublished($builder)
    {
        return $this->behavior->renderTemplate('queryFilterPublished', array(
            'isPublishedColumnFilter'   => $this->getColumnFilter('is_published_column'),
            'queryClassName'            => $this->getQueryClassName($builder),
            'with_timeframe'			=> 'true' === $this->behavior->getParameter('with_timeframe'),
        ));
    }

    public function addFilterUnpublished($builder)
    {
        return $this->behavior->renderTemplate('queryFilterUnpublished', array(
            'isPublishedColumnFilter'   => $this->getColumnFilter('is_published_column'),
            'queryClassName'            => $this->getQueryClassName($builder),
        ));
    }

    public function addFilterByPublicationActive($builder)
    {
        if ('true' !== $this->behavior->getParameter('with_timeframe')) {
            return '';
        }

        $builder->declareClass('PropelDateTime');
        
        return $this->behavior->renderTemplate('queryFilterByPublicationActive', array(
            'queryClassName'            => $this->getQueryClassName($builder),
            'publishedAtColumnFilter'   => $this->getColumnFilter('published_at_column'),
            'publishedAtColumnPhpName'   => $this->getColumnPhpName('published_at_column'),
            'publishedUntilColumnFilter'=> $this->getColumnFilter('published_until_column'),
            'publishedUntilColumnPhpName'=> $this->getColumnPhpName('published_until_column'),
            'require_start'     		=> 'true' === $this->behavior->getParameter('require_start'),
            'require_end'       		=> 'true' === $this->behavior->getParameter('require_end'),
        ));
    }

    public function addIncludeUnpublished($builder)
    {
        return $this->behavior->renderTemplate('queryIncludeUnpublished', array(
            'queryClassName'            => $this->getQueryClassName($builder),
        ));
    }

    public function addPublish($builder)
    {
        return $this->behavior->renderTemplate('queryPublish', array(
            'queryClassName'            => $this->getQueryClassName($builder),
            'isPublishedColumnName'     => $this->behavior->getColumnForParameter('is_published_column')->getPhpName(),
        ));
    }

    public function addUnpublish($builder)
    {
        return $this->behavior->renderTemplate('queryUnpublish', array(
            'queryClassName'            => $this->getQueryClassName($builder),
            'isPublishedColumnName'     => $this->behavior->getColumnForParameter('is_published_column')->getPhpName(),
        ));
    }

    protected function getColumnFilter($columnName)
    {
        return 'filterBy' . $this->behavior->getColumnForParameter($columnName)->getPhpName();
    }

    protected function getColumnPhpName($columnName)
    {
        return $this->behavior->getColumnForParameter($columnName)->getPhpName();
    }

    protected function getQueryClassName($builder)
    {
        return $builder->getStubQueryBuilder()->getClassname();
    }
}
