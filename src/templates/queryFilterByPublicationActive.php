
/**
 * Filters out element with invalid publication date at $date.
 *
 * @param Mixed $date the date to filter on
 */
public function filterByPublicationActive($date = 'now')
{
    $date = new PropelDateTime($date);
    return $this
<?php if (!$require_start): ?>
        ->condition('published_at_null', $this->getModelAliasOrName().'.<?php echo $publishedAtColumnPhpName ?> IS NULL')
        ->condition('published_at_ok', $this->getModelAliasOrName().'.<?php echo $publishedAtColumnPhpName ?> < ?', $date)
        ->where(array('published_at_null', 'published_at_ok'), 'OR')
<?php else: ?>
        -><?php echo $publishedAtColumnFilter ?>(array('max' => $date))
<?php endif; ?>
<?php if (!$require_end): ?>
        ->condition('published_until_null', $this->getModelAliasOrName().'.<?php echo $publishedUntilColumnPhpName ?> IS NULL')
        ->condition('published_until_ok', $this->getModelAliasOrName().'.<?php echo $publishedUntilColumnPhpName ?> > ?', $date)
        ->where(array('published_until_null', 'published_until_ok'), 'OR')
<?php else: ?>
        -><?php echo $publishedUntilColumnFilter ?>(array('min' => $date))
<?php endif; ?>
    ;
}
