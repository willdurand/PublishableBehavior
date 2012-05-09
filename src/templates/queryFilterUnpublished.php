
/**
 * Filter the query on unpublished objects exclusively.
 *
 * @return <?php echo $queryClassName ?> The current query, for fluid interface
 */
public function filterUnpublished()
{
    return $this-><?php echo $isPublishedColumnFilter ?>(false);
}
