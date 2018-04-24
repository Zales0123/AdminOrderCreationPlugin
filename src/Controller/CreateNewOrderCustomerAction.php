<?php

declare(strict_types=1);

namespace Sylius\AdminOrderCreationPlugin\Controller;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CreateNewOrderCustomerAction
{
    /** @var FactoryInterface */
    private $customerFactory;

    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(
        FactoryInterface $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(Request $request): Response
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerFactory->createNew();
        $customer->setEmail($request->request->get('customer'));

        $this->customerRepository->add($customer);

        return new Response(
            $this->urlGenerator->generate(
                'sylius_admin_order_creation_order_create',
                ['customerEmail' => $customer->getEmail()],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            Response::HTTP_OK
        );
    }
}
