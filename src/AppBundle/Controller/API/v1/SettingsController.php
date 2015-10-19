<?php

namespace AppBundle\Controller\API\v1;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Parser;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;

class SettingsController extends FOSRestController
{
    /**
     * @var object
     */
    private $settings;

    /**
     * @Get("/api/v1.0/settings/")
     * @return array
     */
    public function getSettings(){
        $settings = $this->getPublicSettings();

        $view = $this
                    ->view($settings, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Get("/api/v1.0/settings/google/tracking_id")
     */
    public function getGoogleAnalyticsID(){
        $settings = $this->getPublicSettings();

        $trackingID = array('tracking_id' => $settings['google']['tracking_id']);

        $view = $this
                    ->view($trackingID, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @return object
     */
    public function getPublicSettings(){
        if(isset($this->settings)){
            return $this->settings['public'];
        }

        $yaml = new Parser();
        $settings = $yaml->parse(file_get_contents(__DIR__.'/../../../Config/settings.yml'));

        $this->settings = $settings;

        return $this->settings['public'];
    }

    /**
     * @return object
     */
    public function getPrivateSettings(){
        if(isset($this->settings)){
            return $this->settings['private'];
        }

        $yaml = new Parser();
        $settings = $yaml->parse(file_get_contents(__DIR__.'/../../../Config/settings.yml'));

        $this->settings = $settings;

        return $this->settings['private'];
    }
}
