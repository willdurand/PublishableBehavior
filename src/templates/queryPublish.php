
/**
 * Publish the objects satisfying the query.
 *
 * @return <?php echo $queryClassName ?> The current query, for fluid interface
 */
public function publish(PropelPDO $con = null)
{
    return $this->update(array('<?php echo $isPublishedColumnName ?>' => true), $con);
}
