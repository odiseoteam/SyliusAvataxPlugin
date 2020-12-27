<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Mapping;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Odiseo\SyliusAvataxPlugin\Entity\AvataxAwareInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;

final class AvataxAwareListener implements EventSubscriber
{
    /** @var RegistryInterface */
    private $resourceMetadataRegistry;

    /** @var string */
    private $productClass;

    public function __construct(
        RegistryInterface $resourceMetadataRegistry,
        string $productClass
    ) {
        $this->resourceMetadataRegistry = $resourceMetadataRegistry;
        $this->productClass = $productClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $reflection = $classMetadata->reflClass;

        if ($reflection->isAbstract()) {
            return;
        }

        if (
            $reflection->implementsInterface(ProductInterface::class) &&
            $reflection->implementsInterface(AvataxAwareInterface::class)
        ) {
            $this->mapAvataxAware($classMetadata);
        }
    }

    /**
     * @param ClassMetadata $metadata
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    private function mapAvataxAware(ClassMetadata $metadata): void
    {
        if (!$metadata->hasField('avataxCode')) {
            $metadata->mapField([
                'fieldName' => 'avataxCode',
                'columnName' => 'avatax_code',
                'type' => 'string',
                'nullable' => true
            ]);
        }
    }
}
