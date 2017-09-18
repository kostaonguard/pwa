<?php

namespace pwa;

/**
 * The code used in the admin.
 */
class Admin
{
    private $plugin_slug;
    private $version;
    private $option_name;
    private $settings;
    private $settings_group;
    private $tabs;
    private $current;

    public static $options_page_id = 'pwa-options';

    public function __construct($plugin_slug, $version, $option_name)
    {
        $this->plugin_slug = $plugin_slug;
        $this->version = $version;
        $this->option_name = $option_name;
        $this->settings = get_option($this->option_name);
        $this->settings_group = $this->option_name.'_group';
        $this->tabs = array(
          'manifest' => __('Manifest', 'pwa'),
          'offline-content' => __('Offline Content', 'pwa'),
          'notification' => __('Notification', 'pwa'),
        );
    }


    private function custom_settings_fields($field_args, $settings)
    {
    }

    public function assets()
    {
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style($this->plugin_slug, plugin_dir_url(__FILE__).'css/pwa-admin.css', [], $this->version);
        wp_enqueue_script($this->plugin_slug, plugin_dir_url(__FILE__).'js/pwa-admin.js', ['jquery','wp-color-picker'], $this->version, true);
    }

    /**
     * Generate settings fields by passing an array of data (see the render method).
     *
     * @param array $field_args The array that helps build the settings fields
     * @param array $settings   The settings array from the options table
     *
     * @return string The settings fields' HTML to be output in the view
     */
    public function register_settings()
    {

      add_settings_section(
        'page_manifest',
        __('Manifest','pwa'),
        function () {
        },
        'menu-manifest'
      );

      add_settings_section(
        'page_offline_content',
        __('Offline Content','pwa'),
        function () {
        },
        'menu-offline-content'
      );

      add_settings_section(
        'page_notification',
        __('Notification','pwa'),
        function () {
        },
        'menu-notification'
      );

      /* ----------------------------------------------------------------------------- */
      /* Manifest */
      /* ----------------------------------------------------------------------------- */

      add_settings_field (
          'manifest[dir]',              // ID used to identify the field throughout the theme
          __('Direction', 'pwa'),       // The label to the left of the option interface element
          [$this, 'echo_input_field'],  // The name of the function responsible for rendering the option interface
          'menu-manifest',              // The page on which this option will be displayed
          'page_manifest',              // The name of the section to which this field belongs
          [
            'label_for' => 'manifest[dir]',
            'type'  => 'select',
            'options'=>
            [
              'auto'=> __('Auto', 'pwa'),
              'ltr'=> __('Left to Right Text', 'pwa'),
              'rtl'=> __('Right to Left Text', 'pwa'),
            ],
            'default'=> (is_rtl()) ? 'rtl' : 'ltr',
          ]
      );

      add_settings_field(
        'manifest[lang]',
        __('Language Tag', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[lang]',
          'slug'  => 'manifest[lang]',
          'placeholder' => get_bloginfo('language'),
          'type'  => 'text',
          'default' => get_bloginfo('language')
        ]
      );

      add_settings_field(
        'manifest[name]',
        __('Name', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[name]',
          'slug'  => 'manifest[name]',
          'placeholder' => 'Name',
          'type'  => 'text',
          'default' => get_bloginfo('sitename')
        ]
      );

      add_settings_field(
      'manifest[short_name]',
      __('Short Name', 'pwa'),
      [$this, 'echo_input_field'],
      'menu-manifest',
      'page_manifest',
      [
        'label_for' => 'manifest[short_name]',
        'slug'  => 'manifest[short_name]',
        'placeholder' => 'Short Name',
        'type'  => 'text'
      ]
      );

      add_settings_field(
        'manifest[description]',
        __('Description', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[description]',
          'slug'  => 'manifest[description]',
          'placeholder' => 'description',
          'type'  => 'text',
          'default' => get_bloginfo('description')
        ]
      );

      add_settings_field(
        'manifest[scope]',
        __('Scope', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[scope]',
          'type'  => 'text',
          'default' => '/'
        ]
      );

      add_settings_field(
        'manifest[icons][0][src]',
        __('Icons', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[icons][0][src]',
          'type'  => 'media'
        ]
      );

      add_settings_field(
        'manifest[icons][0][sizes]',
        '',
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[icons][0][sizes]',
          'type'  => 'hidden'
        ]
      );

      add_settings_field(
        'manifest[icons][0][type]',
        '',
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for'  => 'manifest[icons][0][type]',
          'type'  => 'hidden'
        ]
      );

      add_settings_field(
        'manifest[display]',
        __('Display', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for'  => 'manifest[display]',
          'type'  => 'select',
          'options'=>
          [
            'fullscreen'=> __('Fullscreen', 'pwa'),
            'standalone'=> __('Standalone', 'pwa'),
            'minimal-ui'=> __('Minimal UI', 'pwa'),
            'browser'=> __('Browser', 'pwa'),
          ]
        ]
      );

      add_settings_field(
        'manifest[orientation]',
        __('Orientation', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for'  => 'manifest[orientation]',
          'type'  => 'select',
          'options'=>
          [
            'any' => 'Any',
            'natural' => __('Natural', 'pwa'),
            'landscape' => __('Landscape', 'pwa'),
            'landscape-primary' => __('Landscape Primary', 'pwa'),
            'landscape-secondary' => __('Landscape Secondary', 'pwa'),
            'portrait' => __('Portrait', 'pwa'),
            'portrait-primary' => __('Portrait Primary', 'pwa'),
            'portrait-secondary' => __('Portrait Secondary', 'pwa')
          ]
        ]
      );

      add_settings_field(
        'manifest[start_url]',
        __('Start URL', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[start_url]',
          'type'  => 'text',
          'default' => '/'
        ]
      );

      add_settings_field(
        'manifest[theme_color]',
        __('Theme Color', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[theme_color]',
          'type'  => 'color'
        ]
      );

      add_settings_field(
        'manifest[related_applications][0][platform]',
        '',
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[related_applications][0][platform]',
          'type'  => 'hidden',
          'default' => 'web',
        ]
      );

      add_settings_field(
        'manifest[related_applications][0][url]',
        '',
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[related_applications][0][url]',
          'type'  => 'hidden',
          'default' => site_url(),
        ]
      );

      add_settings_field(
        'manifest[related_applications][1][platform]',
        '',
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[related_applications][1][platform]',
          'type'  => 'hidden',
          'default' => 'play',
        ]
      );

      add_settings_field(
        'manifest[related_applications][1][url]',
        __('Google Play URL', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[related_applications][1][url]',
          'type'  => 'url',
          'placeholder' => 'https://play.google.com/store/apps/details?id=com.example.app1',
        ]
      );

      add_settings_field(
        'manifest[related_applications][1][id]',
        __('Google Play ID', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[related_applications][1][id]',
          'type'  => 'text',
          'placeholder' => 'com.example.app1',
        ]
      );

      add_settings_field(
        'manifest[related_applications][2][platform]',
        '',
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[related_applications][2][platform]',
          'type'  => 'hidden',
          'default' => 'itunes',
        ]
      );

      add_settings_field(
        'manifest[related_applications_itunes]',
        __('iTunes URL', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[related_applications][2][url]',
          'type'  => 'url',
          'placeholder' => 'https://itunes.apple.com/app/example-app1/id123456789',
        ]
      );


      add_settings_field(
        'manifest[prefer_related_applications]',
        __('Prefer Related Applications', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[prefer_related_applications]',
          'type'  => 'checkbox',
        ]
      );

      add_settings_field(
        'manifest[background_color]',
        __('Background Color', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[background_color]',
          'type'  => 'color'
        ]
      );

      add_settings_field(
        'manifest[categories]',
        __('Categories', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for'  => 'manifest[categories]',
          'type'  => 'select',
          'options'=>
          [
            'books' => __('Books', 'pwa'),
            'business' => __('Business', 'pwa'),
            'education' => __('Education', 'pwa'),
            'entertainment' => __('Entertainment', 'pwa'),
            'finance' => __('Finance', 'pwa'),
            'fitness' => __('Fitness', 'pwa'),
            'food' => __('Food', 'pwa'),
            'games' => __('Games', 'pwa'),
            'government' => __('Government', 'pwa'),
            'health' => __('Health', 'pwa'),
            'kids' => __('Kids', 'pwa'),
            'lifestyle' => __('Lifestyle', 'pwa'),
            'magazines' => __('Magazines', 'pwa'),
            'medical' => __('Medical', 'pwa'),
            'music' => __('Music', 'pwa'),
            'navigation' => __('Navigation', 'pwa'),
            'news' => __('News', 'pwa'),
            'personalization' => __('Personalization', 'pwa'),
            'photo' => __('Photo', 'pwa'),
            'politics' => __('Politics', 'pwa'),
            'productivity' => __('Productivity', 'pwa'),
            'security' => __('Security', 'pwa'),
            'shopping' => __('Shopping', 'pwa'),
            'social' => __('Social', 'pwa'),
            'sports' => __('Sports', 'pwa'),
            'travel' => __('Travel', 'pwa'),
            'utilities' => __('Utilities', 'pwa'),
            'weather' => __('Weather', 'pwa'),
          ]
        ]
      );

      add_settings_field(
        'manifest[iarc_rating_id]',
        __('IARC Rating Id', 'pwa'),
        [$this, 'echo_input_field'],
        'menu-manifest',
        'page_manifest',
        [
          'label_for' => 'manifest[iarc_rating_id]',
          'type'  => 'text',
          'placeholder' => 'e84b072d-71b3-4d3e-86ae-31a8ce4e53b7',
        ]
      );

      register_setting(
          'setting-manifest',
          'manifest'
      );

      /* ----------------------------------------------------------------------------- */
      /* Offline Content */
      /* ----------------------------------------------------------------------------- */

      add_settings_field(
        'offline-content[offline_network_timeout]',
        __('Seconds before serving cached content', 'pwa'),
        array($this, 'echo_input_field'),
        'menu-offline-content',
        'page_offline_content',
        [
             'label_for' => 'offline-content[offline_network_timeout]',
             'placeholder' => '',
             'type'  => 'number',
             'step' => '100',
             'min'=> '1000',
             'max'=> '3500',
             'default' => '1000'
        ]
      );

      add_settings_field(
        'offline-content[debug_sw]',
        __('Debug service worker', 'pwa'),
        array($this, 'echo_input_field'),
        'menu-offline-content',
        'page_offline_content',
        [
          'label_for' => 'offline-content[debug_sw]',
          'type'  => 'checkbox',
        ]
      );

      add_settings_field(
        'offline-content[offline_precache]',
        __('Precache published pages', 'pwa'),
        array($this, 'echo_input_field'),
        'menu-offline-content',
        'page_offline_content',
        [
          'label_for' => 'offline-content[offline_precache]',
          'type'  => 'checkbox',
        ]
      );

      register_setting(
          'setting-offline-content',
          'offline-content'
      );

    }

    public function admin_tabs_active($current=null)
    {
        if (is_null($current)) {
            if (isset($_GET['tab'])) {
                $current = $_GET['tab'];
            } else {
                $current = array_keys($this->tabs)[0];
            }
        }
        return  $current;
    }

    public function admin_tabs()
    {
        $current = Admin::admin_tabs_active();
        $html = '';
        foreach ($this->tabs as $location => $tabname) {
            if ($current == $location) {
                $class = ' nav-tab-active';
            } else {
                $class = '';
            }
            $html .= '<a class="nav-tab'.$class.'" href="?page='.self::$options_page_id.'&tab='.$location.'">'.$tabname.'</a>';
        }
        return $html;
    }

    public function add_menus()
    {
        $plugin_name = Info::get_plugin_title();
        add_menu_page(
            $plugin_name,
            __('PWA','pwa'),
            'manage_options',
            self::$options_page_id,
            [$this, 'render']
            // get_template_directory_uri() . '/ico/theme-option-menu-icon.png'
        );
    }

    public function debug_sw_input()
    {
        $debug_sw = $this->options->get('offline_debug_sw'); ?>
        <label>
          <input id="offline-debug-sw" type="checkbox" name="offline_debug_sw"
           value="true" <?php echo $debug_sw ? 'checked="checked"' : ''; ?>/>
          <?php _e('Enable debug traces from the service worker in the console.', 'pwa'); ?>
        </label>
        <?php

    }

    public function precache_input()
    {
        $precache = $this->options->get('offline_precache'); ?>
         <label>
           <input id="offline-precache" type="checkbox" name="offline_precache[pages]"
            value="pages" <?php echo $precache['pages'] ? 'checked="checked"' : ''; ?>/>
           <?php _e('Precache published pages.', 'offline-content'); ?>
         </label>
         <?php

    }

    public function print_precache_info()
    {
        ?>
         <p><?php _e('Precache options allows you to customize which content will be available even if the user never visit it before.', 'pwa'); ?></p>
         <?php

    }

    /**
     * Render the view using MVC pattern.
     */

     public function echo_input_field(array $args)
     {

         $html = '';
         $value = '';
         $slug = $args['label_for'];
         $value_active = get_option(Admin::admin_tabs_active());
         $slug_active = str_replace([Admin::admin_tabs_active(),'[',']'], ['$value_active','[\'','\']'], $slug);
         eval("if (isset($slug_active)) { \$value = $slug_active; }");
         $name = $slug;
         $id = str_replace(['[',']'], ['_',''], $slug);

         if ($value) {
             $value = $value;
         } elseif (isset($args['default'])) {
             $value = $args['default'];
         } else {
             $value = null;
         }

         $placeholder = (isset($args['placeholder'])) ? esc_attr__($args['placeholder'], 'pwa') : null;

         switch ($args['type']) {
          case 'textarea':
            $html .= '<input type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" placeholder="'.$placeholder.'">';
          break;
          case 'select':
            $html .= '<select id="'.$id.'" name="'.$name.'">';
            foreach ($args['options'] as $option => $name) {
                $html .= '<option value="'.$option.'" '.selected($value, $option, false).'>'.$name.'</option>';
            }
            $html .= '</select>';
          break;
          case 'media':
            $html .= '<input type="text" id="'.$id.'" class="'.$name.'" name="'.$name.'" value="'.$value.'">';
            $html .= '<span>&nbsp;<input id="'.$id.'" type="button" class="upload_'.$name.'  button-secondary" value="'. __('Upload image', 'pwa').'" /></span>';
            if (! empty($value)) {
                $html .= '<div id="preview_image_'.$id.'">';
                $html .= '<img src="'.esc_attr(stripslashes($value)).'" />';
                $html .= '</div>';
            } else {
                $html .= '<div id="preview_image_'.$id.'" style="display: none;"></div>';
            }
          break;
          case 'checkbox':
            $current = 'true';
            $html .= '<input type="'. $args['type'].'" id="'.$id.'" class="'.$slug.'" value="true" name="'.$name.'" '.checked($value, $current, false).'>';
          break;
          case 'number':
            $step = (isset($args['step'])) ? 'step="'.$args['step'].'"' : null ;
            $min = (isset($args['min'])) ? 'min="'.$args['min'].'"' : null ;
            $max = (isset($args['max'])) ? 'max="'.$args['max'].'"' : null ;
            $html .= '<input type="'. $args['type'].'" id="'.$id.'" class="'.$slug.'" name="'.$name.'" value="'.$value.'" placeholder="'.$placeholder.'" '.$step.' '.$min.' '.$max.'>';
          break;
          default:
          $html .= '<input type="'. $args['type'].'" id="'.$id.'" class="'.$slug.'" name="'.$name.'" value="'.$value.'" placeholder="'.$placeholder.'">';
          }

         echo $html;
     }

    public function render()
    {
        $heading = Info::get_plugin_title();
        $submit_text = esc_attr__('Save Changes');

        // View
        require_once plugin_dir_path(dirname(__FILE__)).'admin/partials/view.php';
    }
}
