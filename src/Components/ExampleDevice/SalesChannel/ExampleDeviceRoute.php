<?php

namespace B2bExamplePlugin\Components\ExampleDevice\SalesChannel;

use B2bExamplePlugin\Components\ExampleDevice\ExampleDeviceDefinition;
use B2bSellersCore\Components\B2bPlatform\Traits\B2bContextTrait;
use B2bSellersCore\Components\Framework\Routing\Annotation\B2bPlatformContextRequired;
use Shopware\Core\Framework\Api\Exception\ResourceNotFoundException;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Plugin\Exception\DecorationPatternException;
use Shopware\Core\Framework\Routing\Annotation\Entity;
use Shopware\Core\Framework\Routing\Annotation\LoginRequired;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SalesChannel\SuccessResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"store-api"})
 */
class ExampleDeviceRoute extends AbstractExampleDeviceRoute
{
    use B2bContextTrait;
    private ExampleDeviceDefinition $deviceDefinition;
    private EntityRepositoryInterface $devicesRepository;

    public function __construct(
        EntityRepositoryInterface $costCenterRepository,
        ExampleDeviceDefinition $costCenterDefinition
    )
    {
        $this->devicesRepository = $costCenterRepository;
        $this->deviceDefinition = $costCenterDefinition;
    }

    /**
     * @throws DecorationPatternException
     */
    public function getDecorated(): AbstractExampleDeviceRoute
    {
        throw new DecorationPatternException(self::class);
    }

    /**
     * @LoginRequired()
     * @Entity("b2b_example_devices")
     * @Route("/store-api/example-devices/get/{id}", name="store-api.example-device.get", methods={"GET"})
     */
    public function load($id, SalesChannelContext $context): ExampleDeviceRouteResponse
    {
        $criteria = new Criteria([$id]);
        $criteria->addAssociation('employee');

        $device = $this->devicesRepository->search($criteria, $context->getContext())->get($id);

        if($device === null){
            throw new ResourceNotFoundException($this->deviceDefinition->getEntityName(), ['id' => $id]);
        }

        return new ExampleDeviceRouteResponse($device);
    }

    /**
     * @LoginRequired()
     * @Entity("b2b_example_devices")
     * @Route("/store-api/example-devices/{id}", name="store-api.example-device.delete", methods={"DELETE"})
     */
    public function delete($id, SalesChannelContext $context): SuccessResponse
    {
        $this->devicesRepository->delete([['id' => $id]], $context->getContext());
        return new SuccessResponse();
    }

    /**
     * @LoginRequired()
     * @Entity("b2b_example_devices")
     * @Route("/store-api/example-devices/list", name="store-api.example-device.list", methods={"GET"})
     */
    public function list(Request $request, SalesChannelContext $context, Criteria $criteria): ExampleDeviceRouteResponse
    {
        $criteria = new Criteria();
        $criteria->addAssociation('employee');

        $devices = $this->devicesRepository->search($criteria, $context->getContext());

        return new ExampleDeviceRouteResponse($devices);
    }

    /**
     * @LoginRequired()
     * @Entity("b2b_example_devices")
     * @Route("/store-api/example-devices", name="store-api.example-device.create", methods={"POST"})
     */
    public function create(RequestDataBag $requestDataBag, SalesChannelContext $context): SuccessResponse
    {
        $data = $requestDataBag->all();
        $this->devicesRepository->create([$data], $context->getContext());
        return new SuccessResponse();
    }

    /**
     * @LoginRequired()
     * @Entity("b2b_example_devices")
     * @Route("/store-api/example-devices/{id}", name="store-api.example-device.update", methods={"PATCH"})
     */
    public function update($id, RequestDataBag $requestDataBag, SalesChannelContext $context): SuccessResponse
    {
        $requestDataBag->set('id', $id);
        $this->devicesRepository->upsert([$requestDataBag->all()], $context->getContext());

        return new SuccessResponse();
    }
}