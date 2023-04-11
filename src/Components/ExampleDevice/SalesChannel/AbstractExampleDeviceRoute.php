<?php
declare(strict_types=1);

namespace B2bExamplePlugin\Components\ExampleDevice\SalesChannel;

use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SalesChannel\SuccessResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractExampleDeviceRoute
{
    abstract public function load($id, SalesChannelContext $context): ExampleDeviceRouteResponse;
    abstract public function delete($id, SalesChannelContext $context): SuccessResponse;
    abstract public function list(Request $request, SalesChannelContext $context, Criteria $criteria): ExampleDeviceRouteResponse;
    abstract public function create(RequestDataBag $requestDataBag, SalesChannelContext $context): SuccessResponse;
    abstract public function update($id, RequestDataBag $requestDataBag, SalesChannelContext $context): SuccessResponse;

}