<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;

class StudentController extends Controller
{
    private Collection $students;
    private Database $db;
    private Collection $studentAddress;

    function __construct()
    {
        $client = new Client(env('MONGO_DB_URL', 'mongodb://localhost:27017'));
        $this->db = $client->selectDatabase(env('MONGO_DB_NAME', 'student_api'));

        $this->students = $this->db->students;
        $this->studentAddress = $this->db->studentAddress;
    }
    public function store(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'gender' => 'required|in:Male,Female,Other',
                'dob' => 'required|date|date_format:Y-m-d',
                'address' => 'required',
                'address.street' => 'required|string',
                'address.city' => 'required|string',
                'address.pincode' => 'required|regex:/^[0-9]{6}$/',
            ],
            [
                'name.required' => 'The student name is required.',
                'gender.required' => 'The gender field is required.',
                'dob.required' => 'The date of birth is required.',
                'dob.date' => 'The date of birth must be a valid date.',
                'dob.date_format' => 'The date of birth must follow the format YYYY-MM-DD.',
                'address.required' => 'The address is required.',
            ]
        );

        if ($validation->fails()) {
            return response()->json(['message' => $validation->errors()->first()], 400);
        }


        $studentId = Helper::getNextStudentId($this->db);

        $this->students->insertOne([
            'studentId' => $studentId,
            'name' => $request->name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'createdAt' => new UTCDateTime
        ]);

        $this->studentAddress->insertOne([
            'studentId' => $studentId,
            'street' => $request->address['street'],
            'city' => $request->address['city'],
            'pincode' => $request->address['pincode'],
        ]);

        $response = [
            'message' => 'Student created successfully!',
            'studentId' => $studentId
        ];

        return response()->json($response, 201);
    }

    public function getStudents()
    {

        $data = iterator_to_array($this->students->aggregate(
            [
                [
                    '$lookup' => [
                        'from' => 'studentAddress',
                        'localField' => 'studentId',
                        'foreignField' => 'studentId',
                        'as' => 'address'
                    ]
                ],
                [
                    '$unwind' => '$address'
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'studentId' => 1,
                        'name' => 1,
                        'gender' => 1,
                        'dob' => 1,
                        'createdAt' => [
                            '$dateToString' => [
                                'format' => '%Y-%m-%d %H:%M:%S',
                                'date' => '$createdAt',
                                'timezone' => 'Asia/Kolkata'
                            ]
                        ],
                        'address' => [
                            'street' => '$address.street',
                            'city' => '$address.city',
                            'pincode' => '$address.pincode'
                        ]
                    ]
                ]
            ]
        ));

        return response()->json(['data' => $data], 200);
    }
}
