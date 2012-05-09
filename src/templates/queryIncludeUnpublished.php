
/**
 * Add unpublished objects to this query.
 *
 * @return <?php echo $queryClassName ?> The current query, for fluid interface
 */
public function includeUnpublished()
{
    $this->includeUnpublished = true;

    return $this;
}
