<?php

namespace AppBundle\Controller\API\v1;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Parser;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;

use JMS\Serializer\SerializationContext;

class SettingsController extends FOSRestController
{
    /**
     * @Get("/api/v1.0/settings/")
     * @return array
     */
    public function getSettings(){
        $yaml = new Parser();
        $settings = $yaml->parse(file_get_contents(__DIR__.'/../../../Config/settings.yml'));

        $view = $this
                    ->view($settings, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Get("/api/v1.0/settings/google/tracking_id")
     */
    public function getGoogleAnalyticsID(){
        $yaml = new Parser();
        $settings = $yaml->parse(file_get_contents(__DIR__.'/../../../Config/settings.yml'));

        $trackingID = array('tracking_id' => $settings['google']['tracking_id']);

        $view = $this
                    ->view($trackingID, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }
}
