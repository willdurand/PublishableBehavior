<?php

class PublishableBehaviorObjectBuilderModifier
{
    /**
     * @var PublishableBehavior
     */
    private $behavior;

    public function __construct(Behavior $behavior)
    {
        $this->behavior = $behavior;
    }

    public function preInsert($builder)
    {
        return $this->behavior->renderTemplate('objectPreInsert', array(
            'isPublishedColumnSetter'   => $this->getColumnSetter('is_published_column'),
        ));
    }

    public function objectAttributes($builder)
    {
        return "

private \$forcePublish = false;
";
    }

    public function objectMethods($builder)
    {
        $script  = '';
        $script .= $this->addIsPublished($builder);
        $script .= $this->addHasPublicationStarted($builder);
        $script .= $this->addHasPublicationEnded($builder);
        $script .= $this->addPublish($builder);
        $script .= $this->addUnpublish($builder);

        return $script;
    }

    public function addIsPublished($builder)
    {
        return $this->behavior->renderTemplate('objectIsPublished', array(
            'modelName'                 => $this->getModelName($builder),
            'isPublishedColumnGetter'   => $this->getColumnGetter('is_published_column'),
            'with_timeframe'            => 'true' === $this->behavior->getParameter('with_timeframe'),
        ));
    }

    public function addPublish($builder)
    {
        return $this->behavior->renderTemplate('objectPublish', array(
            'modelName'                 => $this->getModelName($builder),
            'objectClassName'           => $this->getObjectClassName($builder),
            'isPublishedColumnSetter'   => $this->getColumnSetter('is_published_column'),
        ));
    }

    public function addUnpublish($builder)
    {
        return $this->behavior->renderTemplate('objectUnpublish', array(
            'modelName'                 => $this->getModelName($builder),
            'objectClassName'           => $this->getObjectClassName($builder),
            'isPublishedColumnSetter'   => $this->getColumnSetter('is_published_column'),
        ));
    }

    public function addHasPublicationStarted($builder)
    {
        if  ('true'  !==  $this->behavior->getParameter('with_timeframe'))  {
            return  '';
        }
        return $out = $this->behavior->renderTemplate('objectHasPublicationStarted', array(
            'modelName'                 => $this->getModelName($builder),
            'objectClassName'           => $this->getObjectClassName($builder),
            'publishedAtColumnGetter'   => $this->getColumnGetter('published_at_column'),
            'required'                  => 'true' === $this->behavior->getParameter('require_start'),
        ));
    }

    public function addHasPublicationEnded($builder)
    {
        if  ('true'  !==  $this->behavior->getParameter('with_timeframe'))  {
            return  '';
        }
        return $this->behavior->renderTemplate('objectHasPublicationEnded', array(
            'modelName'                 => $this->getModelName($builder),
            'objectClassName'           => $this->getObjectClassName($builder),
            'publishedUntilColumnGetter' => $this->getColumnGetter('published_until_column'),
            'required'                  => 'true' === $this->behavior->getParameter('require_end'),
        ));
    }

    protected function getColumnSetter($columnName)
    {
        return 'set' . $this->behavior->getColumnForParameter($columnName)->getPhpName();
    }

    protected function getColumnGetter($columnName)
    {
        return 'get' . $this->behavior->getColumnForParameter($columnName)->getPhpName();
    }

    protected function getModelName($builder)
    {
        return strtolower($this->getObjectClassName($builder));
    }

    protected function getObjectClassName($builder)
    {
        return $builder->getObjectClassname();
    }
}
