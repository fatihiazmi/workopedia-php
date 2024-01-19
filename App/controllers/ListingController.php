<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController
{
    protected $db;
    public function __construct()
    {
        $config = require basePath("config/db.php");
        $this->db = new Database($config);
    }

    public function index()
    {
        $listings = $this->db->query("SELECT * FROM listings")->fetchAll();

        loadView("listings/listingsView", ['listings' => $listings]);
    }
    public function create()
    {
        loadView("listings/createView");
    }
    public function show($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        loadView('listings/showView', ['listing' => $listing]);
    }
    public function store()
    {
        $allowedFields = [
            'title', 'description', 'salary', 'tags', 'company', 'address',
            'city', 'state', 'phone', 'email', 'requirements', 'benefits'
        ];

        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        $newListingData['user_id'] = 1;

        $newListingData = array_map('sanitize', $newListingData);

        $requiredFields = ['title', 'description', 'salary', 'city', 'state', 'email'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {
            loadView('listings/createView', [
                'errors' => $errors,
                'listing' => $newListingData,
            ]);
        } else {
            $fields = [];

            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }
            $values = [];

            $fields = implode(', ', $fields);

            foreach ($newListingData as $field => $value) {
                if ($value === '') {
                    $newListingData[$field] = null;
                }
                $values[] = ':' . $field;
            }

            $values = implode(', ', $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

            $this->db->query($query, $newListingData);

            redirect('listings/create');
        }
    }

    public function edit($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        loadView('listings/editView', ['listing' => $listing]);
    }

    public function destroy($params)
    {
        $id = $params['id'];

        $params = [
            'id' => $id,
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
        }

        $this->db->query('DELETE FROM listings WHERE id = :id', $params);

        $_SESSION['success_message'] = 'Listing deleted successfully';

        redirect('/listings');
    }

    public function update($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        $allowedFields = [
            'title', 'description', 'salary', 'tags', 'company', 'address',
            'city', 'state', 'phone', 'email', 'requirements', 'benefits'
        ];

        $updateValues = [];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['title', 'description', 'salary', 'city', 'state', 'email'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field . ' is required');
            }
        }

        if (!empty($errors)) {
            loadView('/listings/editView', [
                'listing' => $listing,
                'errors' => $errors,
            ]);
            exit;
        } else {
            $updateFields = [];

            foreach (array_keys($updateValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }

            $updateFields = implode(', ', $updateFields);


            $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";

            $updateValues['id'] = $id;
            $this->db->query($updateQuery, $updateValues);

            $_SESSION['success_message'] = 'Listing updated';

            redirect('/listings/' . $id);
        }
    }
}
