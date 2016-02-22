<?php
/**
 * Refresh Options controller
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO\Controller;
use iMonezaPRO\Service\iMoneza;
use iMonezaPRO\View;

/**
 * Class RefreshOptions
 * @package iMonezaPRO\Controller
 */
class RefreshOptions extends ControllerAbstract
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
        $results = ['success'=>true, 'data'=>['message'=>'You have successfully refreshed your options.', 'title'=>'MY NEW TITLE']];
        View::render('options/json-response', $results);

    }
}