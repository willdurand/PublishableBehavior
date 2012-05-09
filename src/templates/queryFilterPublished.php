
/**
 * Filter the query on published objects exclusively.
 *
 * @return <?php echo $queryClassName ?> The current query, for fluid interface
 */
public function filterPublished()
{
    return $this-><?php echo $isPublishedColumnFilter ?>(true);
}
