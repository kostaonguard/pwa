<div class="wrap">
    <h1><?= $heading ?></h1>
    <div class="description">This is description of the page.</div>
    <h2 class="nav-tab-wrapper">
        <?php echo self::admin_tabs(); ?>
    </h2>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <form action="options.php" method="post" enctype="multipart/form-data">
                  <?php
                      echo self::admin_tabs_active();
                      if( self::admin_tabs_active() == 'manifest' ) {
                        settings_fields( 'setting-manifest' );
                        do_settings_sections( 'menu-manifest' );
                      } elseif (self::admin_tabs_active() == 'offline-content') {
                        settings_fields( 'setting-offline-content' );
                        do_settings_sections( 'menu-offline-content' );
                      } elseif (self::admin_tabs_active() == 'notification') {
                        settings_fields( 'setting-notification' );
                        do_settings_sections( 'menu-notification' );
                      }
                  ?>
                      <?php submit_button($submit_text, 'primary'); ?>
                      <div class="spinner"></div>
                </form>
            </div>
        </div>
        <br class="clear">
    </div>
</div>
