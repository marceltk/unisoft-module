<?php

namespace Unisoft\Crawler\Service;

ignore_user_abort(true);
set_time_limit(0);

use Application\Entity\Approbo;
use Unisoft\Crawler\Interfaces\CrawlerInterface;
use Unisoft\Crawler\Util\Util;
use Zend\Console\Console;
use Zend\ServiceManager\ServiceManager;

class CrawlerService
{

    use \Unisoft\Crawler\Traits\CrawlerOutputTrait;

    public static $serviceManager;
    public static $entityManager;
    public static $crawlerCronService;
    public static $loggerService;

    const ENTITY_MANAGER_NAME_DEFAULT = "doctrine.entitymanager.orm_default";

    public function __construct(ServiceManager $serviceManager)
    {
        self::$serviceManager = $serviceManager;
        self::$entityManager = $serviceManager->get(self::ENTITY_MANAGER_NAME_DEFAULT);
        self::$crawlerCronService = $serviceManager->get("CrawlerCronService");
        self::$loggerService = $serviceManager->get("LoggerService")->getLogger();

        self::setWriterLogCrawler();
    }

    private static function setWriterLogCrawler()
    {
        $logName = "log_crawler_browser_" . date("d_m_Y") . ".log";
        if (Console::isConsole()) {
            $logName = "log_crawler_console_" . date("d_m_Y") . ".log";
        }

        $file = __PUBLIC_PATH__ . '/sys/logs/' . $logName;

        $writer2 = new \Zend\Log\Writer\Stream($file);

        self::$loggerService->addWriter($writer2);
    }

    private function canExecute(Approbo $Approbo)
    {
        if ($Approbo->getAtivo() != 'S') {
            return false;
        }

        return self::$crawlerCronService->validate($Approbo, self::$entityManager);
    }

    public function execute()
    {
        $figlet = new \Zend\Text\Figlet\Figlet();
        echo $figlet->render('unisoft');

        $crawlers = $this->getImplementedCrawlers();

        self::$loggerService->info("[Start Crawlers] - Total (" . count($crawlers) . ")");

        if (count($crawlers)) {
            foreach ($crawlers as $Crawler) {
                $Approbo = $Crawler->getApprobo();

                $Approbo->setIdeExecutando('S');
                $Approbo->setDatExecucao(new \DateTime());
                self::$entityManager->persist($Approbo);
                self::$entityManager->flush();

                self::$entityManager->getConnection()->beginTransaction();

                try {
                    //                    $crawlerName = strtoupper(Util::stripAccents($Crawler->getName()));
                    $crawlerName = $Crawler->getName();
                    self::$loggerService->info("[" . $crawlerName . "] Start Running");

                    $Crawler->run();

                    self::$loggerService->info("[" . $crawlerName . "] Finished Running Success!");

                    self::$entityManager->getConnection()->commit();
                    $Approbo->setIdeExecutando('N');
                } catch (\Exception $e) {
                    self::$entityManager->getConnection()->rollback();
                    self::$loggerService->err("[" . $crawlerName . "] Finished Running With Error! - " . $e->getMessage());
                }

                self::$entityManager->persist($Approbo);
                self::$entityManager->flush();

                self::$loggerService->info($this->strPad("Peak Memory Usage: " . Util::formatBytes(memory_get_peak_usage(true)), STR_PAD_BOTH));
            }
        } else {
            self::$loggerService->notice("No schedule crawlers for execution");
        }

        self::$loggerService->info("[End Execution Crawlers]");
    }

    /**
     * Validações somente do novo painel de robôs.
     * Classes que implementam CrawlerInterface
     * @return mixed array
     */
    private function getImplementedCrawlers()
    {
        $crawlers = $this->getAllCrawlers();

        $i = 1;
        foreach ($crawlers as $crawler) {
            $namespace = UcFirst($crawler->getModulo()) . "\\Crawler";
            $file = str_replace(".php", "", $crawler->getArquivo());

            $kclass = $namespace . "\\" . $file;

            if (class_exists($kclass)) {
                if (true !== $this->canExecute($crawler)) {
                    continue;
                }

                $crawlerScheduler = new $kclass(self::$serviceManager);
                if ($crawlerScheduler instanceof CrawlerInterface) {
                    $crawlerScheduler->setName($crawler->getNome());
                    $crawlerScheduler->setApprobo($crawler);
                    $schedulers[] = $crawlerScheduler;
                    self::$loggerService->info("[Crawler Scheduled][" . $i++ . "] " . $crawlerScheduler->getName());
                }
            }
        }

        return $schedulers;
    }

    private function getAllCrawlers()
    {
        $listCrawlers = [];

        try {
            $dql = "SELECT u FROM Application\Entity\Approbo u WHERE u.ativo = :ativo AND u.ideExecutando != 'S' ORDER BY u.nome ASC";

            $query = self::$entityManager->createQuery($dql);
            $query->setParameter("ativo", "S");
            $listCrawlers = $query->getResult();
        } catch (\Exception $e) {
            print "<pre>";
            print_r($e->getMessage());
            print "</pre>";
        }

        return $listCrawlers;
    }
}
