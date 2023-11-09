<?php

namespace App\Trait\Controller;

use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Добавляет rout получения сущности GET ".../{controller}/{id|slug}"
 */
trait ReadTrait
{
    #[Route('/{controller}/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[Route('/{controller}/{slug}', requirements: ['slug' => '\w+'], methods: ['GET'])]
    public function read(Request $request, ?string $slug, ?int $id): JsonResponse
    {
        $entity = $id
            ? $this->repo->find($id)
            : $this->repo->findOneBy(['slug' => $slug]);

        if (!$entity) {
            throw new NotFoundException();
        }

        $fields = $this->getParameterFields($request);
        $item   = $this->prepareItem($entity, $fields);
        return $this->json($item);
    }
}