
/**
 * Unpublish the current <?php echo $modelName ?>.
 *
 * @param  PropelPDO $con Optional connection object
 * @return <?php echo $objectClassName ?> The current object (for fluent API support)
 */
public function unpublish(PropelPDO $con = null)
{
    if (true === $this->isPublished()) {
        $this-><?php echo $isPublishedColumnSetter ?>(false);
    }

    return $this->save($con);
}
