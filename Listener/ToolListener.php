<?php

namespace Innova\PathBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use JMS\DiExtraBundle\Annotation as DI;

use Claroline\CoreBundle\Form\TextType;
use Claroline\CoreBundle\Entity\Resource\Text;
use Claroline\CoreBundle\Entity\Resource\Revision;
use Claroline\CoreBundle\Event\OpenResourceEvent;
use Claroline\CoreBundle\Event\DisplayToolEvent;
use Claroline\CoreBundle\Library\Resource\ResourceCollection;



/**
 * @DI\Service
 */
class ToolListener implements ContainerAwareInterface
{
    private $container;

    /**
     * @DI\InjectParams({
     *     "container" = @DI\Inject("service_container")
     * })
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

     /**
     * @DI\Observe("open_path")
     */
    public function onPathOpen(OpenResourceEvent $event)
    {   
        $em = $this->container->get('doctrine.orm.entity_manager');
        $pathId = $event->getResource()->getResourceNode()->getId();
        $workspaceId = $event->getResource()->getResourceNode()->getWorkspace()->getId();
        
        $subRequest = $this->container->get('request')->duplicate( array(), array('pathId' => $pathId, 'workspaceId' => $workspaceId), array("_controller" => 'InnovaPathBundle:Path:showPath'));
        $response = $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);

        $event->setResponse($response);
        $event->stopPropagation();
    }

    public function onWorkspaceOpen(DisplayToolEvent $event)
    {
        $id = $event->getWorkspace()->getId();
        $subRequest = $this->container->get('request')->duplicate(array('id'=>$id), array(), array("_controller" => 'innova.path.controller:fromWorkspaceAction'));
        $response = $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        $event->setContent($response->getContent());
    }
}
