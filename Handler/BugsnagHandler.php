<?php

namespace Allies\Bundle\OroBugsnagBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Bugsnag\Client as BugsnagClient;
use Bugsnag\Report as BugsnagReport;
use Oro\Bundle\ConfigBundle\Config\ConfigManager;

class BugsnagHandler extends AbstractProcessingHandler
{
    
/******************************************************************************
 * PROPERTIES
 ******************************************************************************/
    
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * @var BugsnagClient
     */
    protected $client;
    
    /**
     * @var ConfigManager
     */
    protected $configManager;
    
/******************************************************************************
 * STATICS
 ******************************************************************************/
    
    /**
     * @param integer $errorCode
     * @return string
     */
    public static function getSeverity($errorCode)
    {
        switch ($errorCode) {
            case Logger::EMERGENCY :
            case Logger::ALERT :
            case Logger::CRITICAL :
            case Logger::ERROR :
                return 'error';
                break;
            
            case Logger::WARNING :
                return 'warning';
                break;
            
            case Logger::NOTICE :
            case Logger::INFO :
            case Logger::DEBUG :
                return 'info';
                break;
            
            default :
                throw new \InvalidArgumentException(sprintf(
                    "Unknown errorCode %s passed",
                    (is_object($errorCode)) ? get_class($errorCode) : $errorCode
                ));
                break;
        }
    }
    
/******************************************************************************
 * MAGIC
 ******************************************************************************/
    
    /**
     * @param BugsnagClient $client
     * @param ConfigManager $configManager
     * @param integer $level
     * @param boolean $bubble
     */
    public function __construct(
        BugsnagClient $client,
        ConfigManager $configManager,
        $level = Logger::ERROR,
        $bubble = true
    ) {
        parent::__construct($level, $bubble);
        
        $this->client = $client;
        $this->configManager = $configManager;
    }
    
/******************************************************************************
 * ACTIONS
 ******************************************************************************/

    /**
     * @param array $record
     * @return void
     */
    protected function write(array $record)
    {        
        if (!in_array($record['level'], $this->configManager->get('allies_oro_bugsnag.reporting_level'))) {
            return;
        }
        $severity = self::getSeverity($record['level']);
        
        if (isset($record['context']['exception'])) {
            $this->client->notifyException(
                $record['context']['exception'],
                function (BugsnagReport $report) use ($record, $severity) {
                    $report->setSeverity($severity);
                    if (isset($record['extra'])) {
                        $report->setMetaData($record['extra']);
                    }
                }
            );
        } else {
            $this->client->notifyError(
                (string)$record['message'],
                (string)$record['formatted'],
                function (BugsnagReport $report) use ($record, $severity) {
                    $report->setSeverity($severity);
                    if (isset($record['extra'])) {
                        $report->setMetaData($record['extra']);
                    }
                }
            );
        }
    }
}
