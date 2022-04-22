<?php

class PropertyDetails extends BasicPage
{
    private $property_id;

    public function __construct($id)
    {
        parent::__construct();

        $this->property_id = $id;
    }

    public function render()
    {
        $this->setTitle('Property');

        Renderer::render("property.php", [
            'property' => PropertyService::getPropertyDetails($this->property_id)
        ]);
    }
}
