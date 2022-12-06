<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MyObj;
use App\Enum\Color;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BugController extends AbstractController
{
    /**
     * This route returns the normalized object as expected
     */
    #[Route('/working', name: 'working')]
    public function working(): JsonResponse
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);

        $obj = new MyObj('id', 'title', Color::RED);

        // {"id":"id", "title":"title", "color":{"name":"RED", "value":"red"}}
        return new JsonResponse(
            $serializer->normalize($obj, null, [])
        );
    }

    /**
     * This route tries to return the normalized objects properties
     * specified by groups but fails. The enums values are lost.
     */
    #[Route('/bug', name: 'bug')]
    public function bug(): JsonResponse
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);

        $obj = new MyObj('id', 'title', Color::RED);

        // {"title":"title", "color":[]}
        return new JsonResponse(
            $serializer->normalize($obj, null, [
                AbstractNormalizer::GROUPS => ['test']
            ])
        );
    }

    /**
     * This route tries to return the normalized objects properties
     * specified by attributes which works.
     */
    #[Route('/workaround', name: 'workaround')]
    public function workaround(): JsonResponse
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);

        $obj = new MyObj('id', 'title', Color::RED);

        // {"title":"title","color":{"name":"RED","value":"red"}}
        return new JsonResponse(
            $serializer->normalize($obj, null, [
                AbstractNormalizer::ATTRIBUTES => ['title', 'color']
            ])
        );
    }
}
