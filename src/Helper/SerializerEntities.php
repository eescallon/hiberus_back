<?php
namespace App\Helper;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class SerializerEntities {
    
    public static function convertCollectionToJson($data)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        // dd(get_object_vars(new CustomerSite()));
        $jsonData = $serializer->serialize(
            $data,
            'json',
            [
                'circular_reference_handler'=>function($object){
                    // return $object->getId();
                    $this_class = get_class($object);
                    $new_obj = new $this_class;
                    $new_obj->setId($object->getId());
                    $new_obj = SerializerEntities::convertCollectionToJson($new_obj);
                    $new_obj = json_decode($new_obj);
                    return $new_obj;
                },
                // 'enable_max_depth' => true
            ]
        );
        return $jsonData;
    }
}