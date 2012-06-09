
/**
 * Returns true if the <?php echo $modelName ?> is published,
 * false otherwise.
 *
 * @return Boolean
 */
public function isPublished()
{
<?php if ($with_timeframe): ?>
  if  (!$this->hasPublicationStarted()  ||  $this->hasPublicationEnded())  {
      return  false;
  }
<?php endif ?>
    return null !== $this-><?php echo $isPublishedColumnGetter ?>() && $this-><?php echo $isPublishedColumnGetter ?>();
}
