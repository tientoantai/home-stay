<?php

namespace App\HomeStay\Apartment;


use App\User;
use App\UserFactory;
use Illuminate\Support\Collection;

class ApartmentFactory
{
    /**
     * @param $rawApartment
     * @return Apartment
     */
    public function factory($rawApartment)
    {
        $owner = User::findOrFail($rawApartment->user_id);
        $apartment = new Apartment(new Location($rawApartment->lat, $rawApartment->lng), $owner);
        return $apartment
            ->setId($rawApartment->id)
            ->setCapacity($rawApartment->capacity_from, $rawApartment->capacity_to)
            ->setCity($rawApartment->city);
    }

    public function factoryList($rawApartments)
    {
        return new Collection(array_map(
            function ($rawApartment) {
                return $this->factory($rawApartment) ;
            }, $rawApartments));
    }
}