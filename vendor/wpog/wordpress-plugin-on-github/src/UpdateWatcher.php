<?php
/**
 * Watches for Updates on GitHub
 *
 * @author Aaron Saray
 */

namespace WPOG;

/**
 * Class UpdateWatcher
 * @package WPOG
 */
class UpdateWatcher
{
    /**
     * @var string the plugin file from __FILE__ call
     */
    protected $pluginFile = '';
    
    /**
     * @var string the plugin file like 'my-plugin/my-plugin.php'
     */
    protected $pluginBaseName = '';

    /**
     * @var string the plugin slug like `my-plugin`
     */
    protected $pluginSlug = '';

    /**
     * @var array plugin data gathered from the header of the plugin
     */
    protected $pluginData = [];
    
    /**
     * @var string the namespace which is user or org
     */
    protected $githubNamespace = '';

    /**
     * @var string the project 
     */
    protected $githubProject = '';

    /**
     * @var array the information for the latest release
     */
    protected $githubLatestRelease = [];
    
    /**
     * UpdateWatcher constructor.
     * @param $pluginFile string The plugin file (__FILE__ most likely)
     * @param $githubNamespace string User or org name in github
     * @param $githubProject string The Project name
     */
    public function __construct($pluginFile, $githubNamespace, $githubProject)
    {
        $this->pluginFile = $pluginFile;
        $this->githubNamespace = $githubNamespace;
        $this->githubProject = $githubProject;

        \add_filter("pre_set_site_transient_update_plugins", array($this, "filterPreSetSiteTransientUpdatePlugins"));
        \add_filter("plugins_api", array($this, "filterPluginsApi"), 10, 3);
        \add_filter("upgrader_post_install", array($this, "filterUpgraderPostInstall" ), 10, 3);
    }

    /**
     * Add in plugin information in order to get updates
     * @param $transient object
     * @return object
     */
    public function filterPreSetSiteTransientUpdatePlugins($transient)
    {
        $this->updatePluginData();
        $this->updateGithubData();

        if (version_compare($this->githubLatestRelease['tag_name'], $transient->checked[$this->pluginBaseName]) === 1) {
            $update = new \stdClass();
            $update->slug = $this->pluginSlug;
            $update->new_version = $this->githubLatestRelease['tag_name'];
            $update->url = $this->pluginData['PluginURI'];
            $update->package = $this->githubLatestRelease['zipball_url'];
            
            $transient->response[$this->pluginBaseName] = $update;
        }
        
        return $transient;
    }

    /**
     * This updates the details for showing information in the modal
     * 
     * @param $false
     * @param $action
     * @param $response
     * @return mixed
     */
    public function filterPluginsApi($false, $action, $response)
    {
        // basically, verify we're working with our plugin
        if (empty($response->slug)) return false;
        
        $this->updatePluginData();
        if ($response->slug != $this->pluginSlug) return false;
        
        // it's ours, so update the data
        $this->updateGithubData();

        $response->last_updated = $this->githubLatestRelease['published_at'];
        $response->slug = $this->pluginSlug;
        $response->name  = $this->pluginData["Name"];
        $response->version = $this->githubLatestRelease['tag_name'];
        $response->author = $this->pluginData["AuthorName"];
        $response->homepage = $this->pluginData["PluginURI"];
        $response->download_link = $this->githubLatestRelease['zipball_url'];
        
        $parsedown = new \Parsedown();
        $response->sections = [
            'description'   =>  $this->pluginData['Description'],
            'changelog' =>  $parsedown->text($this->githubLatestRelease['body'])
        ];
        
        return $response;
    }

    /**
     * Handles the post upgrade moving of files and what not
     *
     * @param $true
     * @param $hookExtra
     * @param $result
     * @return mixed
     * @throws \Exception
     */
    public function filterUpgraderPostInstall($true, $hookExtra, $result) {
        $this->updatePluginData();
        $wasPluginActive = is_plugin_active($this->pluginBaseName);

        global $wp_filesystem;
        $pluginFolder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname($this->pluginBaseName);
        $wp_filesystem->move($result['destination'], $pluginFolder);
        $result['destination'] = $pluginFolder;
        
        if ($wasPluginActive) {
            $activation = activate_plugin($this->pluginBaseName);
            if (is_wp_error($activation)) {
                throw new \Exception('Unable to reactivate plugin: ' . $activation->get_error_message());
            }
        }
        
        return $result;
    }

    /**
     * Gather plugin data from the file
     * 
     * @note This is in a separate method because it doesn't need to be ran each time the constructor is initialized, only when callbacks are used
     */
    protected function updatePluginData()
    {
        $this->pluginBaseName = \plugin_basename($this->pluginFile);
        $this->pluginSlug = dirname($this->pluginBaseName);
        $this->pluginData = \get_plugin_data($this->pluginFile);
    }

    /**
     * Update the data from GitHub
     *
     * @return bool
     */
    protected function updateGithubData()
    {
        if (!empty($this->githubLatestRelease)) return false;  // this filter gets called twice, so the second time bail.

        $url = \esc_url_raw("https://api.github.com/repos/{$this->githubNamespace}/{$this->githubProject}/releases");
        $response = \wp_remote_retrieve_body(\wp_remote_get($url));
        if (is_wp_error($response)) {
            error_log($response->get_error_message());
            return false;
        }

        $jsonDecodedResponse = json_decode($response, true);
        if (is_null($jsonDecodedResponse)) {
            error_log('Unable to parse response: ' . json_last_error_msg());
            return false;
        }

        if (empty($jsonDecodedResponse[0])) {
            error_log('There is no release information gathered.');
            return false;
        }

        $this->githubLatestRelease = $jsonDecodedResponse[0];

        return true;
    }
}