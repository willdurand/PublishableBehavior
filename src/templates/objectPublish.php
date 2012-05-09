
/**
 * Publish the current <?php echo $modelName ?>.
 *
 * @param  PropelPDO $con Optional connection object
 * @return <?php echo $objectClassName ?> The current object (for fluent API support)
 */
public function publish(PropelPDO $con = null)
{
    if (false === $this->isPublished()) {
        $this-><?php echo $isPublishedColumnSetter ?>(true);
        $this->forcePublish = true;
    }

    return $this->save($con);
}
