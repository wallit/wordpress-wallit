<?php
/**
 * Main App
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress;
use Pimple\Container;
use iMoneza\WordPress\Traits;


/**
 * Class App
 * @package iMonezaPRO
 */
class App
{
    use Traits\Options;
    
    /**
     * @var Container
     */
    protected $di;

    /**
     * App constructor.
     * @param Container $di
     */
    public function __construct(Container $di)
    {
        $this->di = $di;
    }

    /**
     * Invoke the APP
     */
    public function __invoke()
    {
        if (is_admin()) {
            $this->initAdminItems();
            $this->registerAdminAjax();
            $this->enqueueAdminScripts();
        }
        else {
            $this->addPremiumIndicator();
            $this->addAdblockNotification();
            $this->addSupportingUserCSS();
        }
    }

    /**
     * @return string the base directory
     */
    public static function getPluginBaseDir()
    {
        return sprintf('%s/%s', WP_PLUGIN_URL, basename(realpath(__DIR__ . '/../')));
    }

    /**
     * Add admin items like menu and and settings
     */
    protected function initAdminItems()
    {
        $di = $this->di;

        add_action('admin_init', function () {
            register_setting(self::$optionsKey, self::$optionsKey);
        });

        add_action('admin_menu', function () use ($di) {
            add_menu_page('iMoneza Settings', 'iMoneza', 'manage_options', 'imoneza', $di['controller.options.display'],
                'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjAiIHk9IjAiIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwLCAwLCAxNTAsIDE1MCI+CiAgPGcgaWQ9IkxheWVyXzEiPgogICAgPGc+CiAgICAgIDxwYXRoIGQ9Ik0yNS44MzEsMTExLjc4NiBMNTQuOTcyLDY0LjcyIEw0OS41NjQsNjQuNzIgTDQ5LjU2NCw1MC41IEw3OC4zMDUsNTAuNSBMNzguMzA1LDEwMS4xNzEgTDEwMS41MzcsNjQuNzIgTDk3LjAzMSw2NC43MiBMOTcuNTMxLDUwLjUgTDEyNS4xNyw1MC41IEwxMjUuMTcsMTExLjc4NiBMMTMyLjE4LDExMS43ODYgTDEzMi4xOCwxMjUuNjA1IEwxMDYuNTQ0LDEyNS42MDUgTDEwNi41NDQsMTExLjc4NiBMMTExLjE1MSwxMTEuNzg2IEwxMTEuMTUxLDc1LjIzNSBMNzkuMjA2LDEyNS42MDUgTDY0LjQ4NSwxMjUuNjA1IEw2NC40ODUsNzUuMjM1IEw0Mi4xNTQsMTExLjc4NiBMNDguMzYzLDExMS43ODYgTDQ4LjM2MywxMjUuNjA1IEwxNy44MiwxMjUuNjA1IEwxNy44MiwxMTEuNzg2IHoiIGZpbGw9IiM0NTQ2NDMiLz4KICAgICAgPHBhdGggZD0iTTc1LjA1MywzNS40MDcgQzc1LjA1Myw0MS40ODcgNzAuMTI2LDQ2LjQxOSA2NC4wNDEsNDYuNDE5IEM1Ny45NjEsNDYuNDE5IDUzLjAzMiw0MS40ODcgNTMuMDMyLDM1LjQwNyBDNTMuMDMyLDI5LjMyNyA1Ny45NjEsMjQuMzk1IDY0LjA0MSwyNC4zOTUgQzcwLjEyNiwyNC4zOTUgNzUuMDUzLDI5LjMyNyA3NS4wNTMsMzUuNDA3IiBmaWxsPSIjNDU0NjQzIi8+CiAgICA8L2c+CiAgPC9nPgo8L3N2Zz4K'
                , 100);
        });
    }

    /**
     * Registers the admin ajax functionality
     */
    protected function registerAdminAjax()
    {
        $di = $this->di;
        add_action('wp_ajax_options_display', function () use ($di) {
            /** @var \iMoneza\WordPress\Controller\Options\Display $controller */
            $controller = $di['controller.options.display'];
            $controller();
        });
    }

    /**
     * Add the admin scripts
     */
    protected function enqueueAdminScripts()
    {
        add_action('admin_enqueue_scripts', function () {
            wp_register_style('imoneza-admin-css', self::getPluginBaseDir() . '/assets/css/admin.css');
            wp_enqueue_style('imoneza-admin-css');
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-form');
            wp_enqueue_script('imoneza-admin-js', self::getPluginBaseDir() . '/assets/js/admin.js', [], false, true);
        });
    }

    /**
     * Adds the premium indicator filter if need be
     */
    protected function addPremiumIndicator()
    {
        $options = $this->getOptions();

        if ($options->isIndicatePremiumContent()) {
            add_filter('the_title', function($title) use ($options) {
                if (has_tag('premium')) {
                    $title = '<span class="imoneza-premium-indicator ' . $options->getPremiumIndicatorIconClass() . '"></span> ' . $title;
                }

                return $title;
            });
        }
    }

    /**
     * add adblock notification if need be
     */
    protected function addAdblockNotification()
    {
        $options = $this->getOptions();
        if ($options->isNotifyAdblocker()) {
            add_action('wp_enqueue_scripts', function() {
                wp_enqueue_script('imoneza-abd', self::getPluginBaseDir() . '/assets/js/abd.js', ['jquery'], false, true);
            });
            add_action('wp_footer', function() use ($options) {
                View::render('abd-execution-js', ['jsDir' => self::getPluginBaseDir() . '/assets/js', 'message'=>$options->getAdblockNotification()]);
            });
        }
    }

    /**
     * only add our CSS declaration if we need it
     */
    protected function addSupportingUserCSS()
    {
        if ($this->getOptions()->isNotifyAdblocker() || $this->getOptions()->isIndicatePremiumContent()) {
            add_action('wp_enqueue_scripts', function() {
                wp_register_style('imoneza-user-css', self::getPluginBaseDir() . '/assets/css/user.css');
                wp_enqueue_style('imoneza-user-css');
            });
        }
    }
}