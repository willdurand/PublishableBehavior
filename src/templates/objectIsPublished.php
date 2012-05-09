
/**
 * Returns true if the <?php echo $modelName ?> is published,
 * false otherwise.
 *
 * @return Boolean
 */
public function isPublished()
{
    return null !== $this-><?php echo $isPublishedColumnGetter ?>() && $this-><?php echo $isPublishedColumnGetter ?>();
}
