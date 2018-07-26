<?php

namespace PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;

class StoryEditType extends AbstractType
{
    public function getParent()
    {
        return StoryType::class;
    }   
}