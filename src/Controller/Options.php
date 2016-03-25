<?php
/**
 * Options controller
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO\Controller;
use iMonezaPRO\Service\iMoneza;
use iMonezaPRO\View;

/**
 * Class Options
 * @package iMonezaPRO\Controller
 */
class Options extends ControllerAbstract
{
    /**
     * @var iMoneza
     */
    protected $iMonezaService;

    /**
     * Options constructor.
     * @param iMoneza $iMonezaService
     */
    public function __construct(iMoneza $iMonezaService)
    {
        parent::__construct();
        $this->iMonezaService = $iMonezaService;
    }

    /**
     * Show Options items
     */
    public function __invoke()
    {
        $options = $this->getOptions();

        if ($this->isPost()) {
            check_ajax_referer('imoneza-options');

            $postOptions = array_filter($this->getPost('imoneza-options', []), 'trim');

            $errors = [];
            $this->iMonezaService
                ->setManagementApiKey($postOptions['management-api-key'])
                ->setManagementApiSecret($postOptions['management-api-secret'])
                ->setAccessApiKey($postOptions['access-api-key'])
                ->setAccessApiSecret($postOptions['access-api-secret']);

            if (!($property = $this->iMonezaService->getProperty())) {
                $errors[] = $this->iMonezaService->getLastError();
            }
            if (!in_array($postOptions['access-control'], [\iMonezaPRO\Model\Options::ACCESS_CONTROL_SERVER, \iMonezaPRO\Model\Options::ACCESS_CONTROL_CLIENT])) {
                $errors[] = 'The access control somehow is not a valid value.';
            }
            if (!$this->iMonezaService->validateResourceAccessApiCredentials()) {
                $errors[] = $this->iMonezaService->getLastError();
            }

            $results = $this->getGenericAjaxResultsObject();

            if (empty($errors)) {
                $options->setAccessControl($postOptions['access-control'])
                    ->setAccessApiKey($postOptions['access-api-key'])
                    ->setAccessApiSecret($postOptions['access-api-secret'])
                    ->setManagementApiKey($postOptions['management-api-key'])
                    ->setManagementApiSecret($postOptions['management-api-secret'])
                    ->setPricingGroups($property->getPricingGroups())
                    ->setDynamicallyCreateResources($property->isDynamicallyCreateResources());
                $this->saveOptions($options);

                $results['success'] = true;
                $results['data']['message'] = 'Your settings have been saved!';
            }
            else {
                $results['success'] = false;
                $results['data']['message'] = array_reduce($errors, function($errorString, $error) {
                    if (empty($errorString)) {
                        $errorString = $error;
                    }
                    else {
                        $concatAdverbish = ['Also', 'Then', 'In addition'];
                        $errorString .= ' ' . $concatAdverbish[array_rand($concatAdverbish)] . ', ' . lcfirst($error);
                    }
                    return $errorString;
                });
            }

            View::render('admin/options/json-response', $results);
        }
        else {
            $postsQueuedForProcessing = 0;

            if ($options->isDynamicallyCreateResources()) {
                // @todo this is dupe code
                $query = new \WP_Query([
                    'post_type' =>  'post',
                    'posts_per_page'    =>  20,
                    'meta_query'    =>  [
                        ['key'=>'_pricing-group-id', 'value'=>'', 'compare'=>'NOT EXISTS']
                    ]
                ]);

                $postsQueuedForProcessing = $query->found_posts;
            }

            $parameters = [
                'firstTimeSuccess' => boolval($this->getGet('first-time')),
                'options' => $options,
                'postsQueuedForProcessing'  =>  $postsQueuedForProcessing
            ];

            View::render('admin/options/dashboard', $parameters);
        }
    }
}