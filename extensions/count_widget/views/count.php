<?php if ( !empty($count) ) : ?>

    <?php if ( $this->withLink ) : ?>
        <a href="<?php echo $url; ?>">
    <?php endif; ?>

        <span data-role="new-comments" class="badge">+<?php echo $count; ?></span>

    <?php if ( $this->withLink ) : ?>
        </a>
    <?php endif; ?>

<?php endif; ?>