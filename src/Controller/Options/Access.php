<?php
/**
 * Access Options controller
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Controller\Options;
use iMoneza\WordPress\Controller\ControllerAbstract;
use iMoneza\WordPress\Service;
use iMoneza\WordPress\Model;

/**
 * Class Access
 * @package iMoneza\WordPress\Controller\Options
 */
class Access extends ControllerAbstract
{
    /**
     * @var Service\iMoneza
     */
    protected $iMonezaService;

    /**
     * @var Service\Post
     */
    protected $postService;

    /**
     * Options constructor.
     * @param \Aura\View\View $view
     * @param Service\iMoneza $iMonezaService
     */
    public function __construct(\Aura\View\View $view, Service\iMoneza $iMonezaService, Service\Post $postService)
    {
        parent::__construct($view);
        $this->iMonezaService = $iMonezaService;
        $this->postService = $postService;
    }

    /**
     * Show Options items
     */
    public function __invoke()
    {
        $view = $this->view;
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
            if (!in_array($postOptions['access-control'], [Model\Options::ACCESS_CONTROL_SERVER, Model\Options::ACCESS_CONTROL_CLIENT])) {
                $errors[] = __('The access control somehow is not a valid value.', 'iMoneza');
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
                    ->setPricingGroupsBubbleDefaultToTop($property->getPricingGroups())
                    ->setDynamicallyCreateResources($property->isDynamicallyCreateResources());
                $this->saveOptions($options);

                $results['success'] = true;
                $results['data']['message'] = __('Your settings have been saved!', 'iMoneza');
            }
            else {
                $results['success'] = false;
                $results['data']['message'] = array_reduce($errors, function($errorString, $error) {
                    if (empty($errorString)) {
                        $errorString = $error;
                    }
                    else {
                        // @todo figure out how to translate this
                        $concatAdverbish = ['Also', 'Then', 'In addition'];
                        $errorString .= ' ' . $concatAdverbish[array_rand($concatAdverbish)] . ', ' . lcfirst($error);
                    }
                    return $errorString;
                });
            }
            $view->setView('admin/options/json-response');
            $view->setData($results);
        }
        else {
            $postsQueuedForProcessing = 0;
            $remainingTimeIndication = '';

            if ($options->isDynamicallyCreateResources()) {
                $query = $this->postService->getWPQueryPostsNotPriced();

                $postsQueuedForProcessing = $query->found_posts;
                if ($postsQueuedForProcessing <= Service\Post::BATCH_SIZE_PROCESS_TO_IMONEZA) {
                    $remainingTimeIndication = __('These may take up to half an hour.', 'iMoneza');
                }
                else if ($postsQueuedForProcessing <= Service\Post::BATCH_SIZE_PROCESS_TO_IMONEZA * 2) {
                    $remainingTimeIndication = __('These should be done in a little over an hour.', 'iMoneza');
                }
                else {
                    $remainingTimeIndication = __('These will take a couple hours to finish up.  Check back once an hour for progress.', 'iMoneza');
                }
            }

            $parameters = [
                'firstTimeSuccess' => boolval($this->getGet('first-time')),
                'options' => $options,
                'postsQueuedForProcessing'  =>  $postsQueuedForProcessing,
                'remainingTimeIndication'   =>  $remainingTimeIndication
            ];

            $view->setData($parameters);
            $view->setView('admin/options/access');
        }

        echo $view();
    }
}