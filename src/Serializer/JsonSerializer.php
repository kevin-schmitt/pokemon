<?php

namespace App\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonSerializer
{
    private $serializer;

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $encoders = [new JsonEncoder()];
        $normalizers = [
            new ObjectNormalizer($classMetadataFactory),
        ];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * serialize data to json.
     *
     * @param entity|object|array $data
     * @param array               $groups groups of attributes for serialization eq: pokemon_read
     *
     * @return string json
     */
    public function serialize($data, array $groups = []): string
    {
        $attributes = [];
        $attributes['ignored_attributes'] = ['ignored_attributes' => ['__initializer__', '__cloner__', '__isInitialized__']];
        if (!empty($groups)) {
            $attributes['groups'] = $groups;
        }

        return $this->serializer->serialize($data, 'json', $attributes);
    }

    /**
     * deserialize json entity.
     *
     * @param string $data       json to deserialize
     * @param string $typeEntity ex 'App\Dao\ContactInformation'
     *
     * @return array|object
     */
    public function deserialize($data, ?string $typeEntity)
    {
        $serializer = $this->serializer;

        return $serializer->deserialize($data, $typeEntity, 'json');
    }
}
