<?php

namespace app\Repository;

use app\models\Persons;

class GuestRepository
{

    // по personId
    public static function byPersonId(int $personId): ?Persons
    {
        return Persons::find()->where(['id' => $personId])->one();
    }



    // YOO KASSA

    // персон преобразованный в customer для yandex-kassa
    public static function customerByPersonId(int $personId): ?array
    {
        $guest = self::byPersonId($personId);

        if (empty($guest) or empty($guest->second_name) or empty($guest->mail) or empty($guest->phone)) {
            return null;
        }

        return array(
            'full_name' => $guest->second_name ?? null . ' ' . $guest->name ?? null . ' ' . $guest->middle_name ?? null,
            'email' => $guest->mail,
            'phone' => $guest->phone,
        );
    }

}
