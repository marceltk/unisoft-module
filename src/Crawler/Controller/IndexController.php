<?php

namespace Unisoft\Crawler\Controller;

use Unisoft\Crawler\Controller\Abstracts\AbstractActionCrawlerController;
use Zend\Console\Console;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionCrawlerController
{

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        parent::__construct($serviceLocator);
    }

    public function indexAction()
    {
        $crawlerService = $this->getServiceLocator()->get("CrawlerService");

        if (!Console::isConsole()) {
            ob_start();
            $crawlerService->execute();
            $conteudo = ob_get_contents();
            ob_end_clean();
            ob_flush();

            $view = new ViewModel(['conteudo' => $conteudo]);
            $view->setTerminal(true);

            return $view;
        } else {
            $crawlerService->execute();

            return;
        }
    }
}
