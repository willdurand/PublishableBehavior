
/**
 * Filter the query on unpublished objects exclusively.
 *
 * @return <?php echo $queryClassName ?> The current query, for fluid interface
 */
public function filterUnpublished()
{
    $this->includeUnpublished = true;

    return $this-><?php echo $isPublishedColumnFilter ?>(false);
}
