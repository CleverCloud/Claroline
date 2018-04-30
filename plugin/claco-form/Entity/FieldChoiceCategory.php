<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\ClacoFormBundle\Entity;

use Claroline\CoreBundle\Entity\Facet\FieldFacetChoice;
use Claroline\CoreBundle\Entity\Model\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="claro_clacoformbundle_field_choice_category")
 */
class FieldChoiceCategory
{
    use UuidTrait;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Claroline\ClacoFormBundle\Entity\Field",
     *     inversedBy="fieldChoiceCategories"
     * )
     * @ORM\JoinColumn(name="field_id", nullable=false, onDelete="CASCADE")
     */
    protected $field;

    /**
     * @ORM\ManyToOne(targetEntity="Claroline\ClacoFormBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", nullable=false, onDelete="CASCADE")
     */
    protected $category;

    /**
     * @ORM\Column(name="field_value")
     * @Assert\NotBlank()
     */
    protected $value;

    /**
     * @ORM\ManyToOne(targetEntity="Claroline\CoreBundle\Entity\Facet\FieldFacetChoice")
     * @ORM\JoinColumn(name="field_facet_choice_id", nullable=true, onDelete="CASCADE")
     */
    protected $fieldFacetChoice;

    public function __construct()
    {
        $this->refreshUuid();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getField()
    {
        return $this->field;
    }

    public function setField(Field $field)
    {
        $this->field = $field;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getFieldFacetChoice()
    {
        return $this->fieldFacetChoice;
    }

    public function setFieldFacetChoice(FieldFacetChoice $fieldFacetChoice = null)
    {
        $this->fieldFacetChoice = $fieldFacetChoice;
    }
}
