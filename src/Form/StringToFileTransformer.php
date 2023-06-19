<?php
namespace App\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StringToFileTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        // Transforma el valor File en una cadena de texto (opcional)
        if ($value instanceof File) {
            return $value->getPath();
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        // Transforma la cadena de texto en un objeto File
        if (!empty($value)) {
            try {
                return new File($value);
            } catch (\Exception $e) {
                throw new TransformationFailedException($e->getMessage());
            }
        }

        return null;
    }
}
