<?php

namespace App\Helpers;

use MongoDB\Operation\FindOneAndUpdate;

class Helper
{
    public static function getNextStudentId($db)
    {
        $countersCollection = $db->counters;

        $counter = $countersCollection->findOneAndUpdate(
            ['_id' => 'student_id'],
            ['$inc' => ['seq' => 1]],
            [
                'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER,
                'upsert' => true, 
                'projection' => ['seq' => 1]
            ]
        );

        $incrementedId = $counter['seq'];

        $currentYear = date('Y');

        return $currentYear . '-'. $incrementedId;
    }
}
