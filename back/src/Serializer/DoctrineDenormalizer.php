<?php

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Mapping\MappingException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DoctrineDenormalizer implements DenormalizerInterface, NormalizerInterface
{
    public const DOCTRINE_MAPPING = 'doctrine_mapping';

    /**
     * DoctrineDenormalizer constructor.
     */
    public function __construct(protected EntityManagerInterface $em)
    {
    }

    /**
     * Denormalizes data back into an object of the given class.
     *
     * @param mixed $data data to restore
     * @param string $type
     * @param string|null $format format the given data was extracted from
     * @param array $context options available to the denormalizer
     *
     * @return object
     *
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        return isset($context[self::DOCTRINE_MAPPING][$type])
            ? $this->em->getRepository($type)->findOneBy([$context[self::DOCTRINE_MAPPING][$type] => $data])
            : $this->em->getRepository($type)->find($data);
    }

    /**
     * Checks whether the given class is supported for denormalization by this normalizer.
     *
     * @param mixed $data Data to denormalize from
     * @param string $type The class to which the data should be denormalized
     * @param string|null $format The format being deserialized from
     * @throws MappingException
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return is_scalar($data) && !$this->em->getMetadataFactory()->isTransient($type);
    }

    /**
     * {@inheritDoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $uow = $this->em->getUnitOfWork();

        return $uow->getSingleIdentifierValue($object);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        if (is_object($data) and isset($context[self::DOCTRINE_MAPPING])) {
            foreach ($context[self::DOCTRINE_MAPPING] as $class => $_) {
                if ($data instanceof $class) {
                    return true;
                }
            }
        }

        return false;
    }
}
