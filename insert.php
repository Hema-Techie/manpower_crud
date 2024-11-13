<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date_of_birth = $_POST['date_of_birth'];
    $skill_code = (int)$_POST['skill_code'];
    $address = $_POST['address'];
    $mobileno = $_POST['mobileno'];
    $email = $_POST['email'];
    $remarks = $_POST['remarks'];
    $errors = [];

    // Validate age
    $dob = new DateTime($date_of_birth);
    $now = new DateTime();
    $age = $now->diff($dob)->y;
    if ($age < 25) {
        $errors[] = "Age must be 25 years or older.";
    }

    // Validate mobile number
    if (!preg_match('/^[5-9][0-9]{9}$/', $mobileno)) {
        $errors[] = "Mobile number must start with 5-9 and be 10 digits long.";
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Check if skill_code exists in mst_skillsets
    $skill_check_query = "SELECT 1 FROM mst_skillsets WHERE sid = $1";
    $skill_check_result = pg_query_params($dbconn, $skill_check_query, [$skill_code]);

    if (pg_num_rows($skill_check_result) === 0) {
        $errors[] = "Invalid skill code. Please select a valid skill.";
    }

    if (empty($errors)) {
        $query = "INSERT INTO manpower (name, date_of_birth, skill_code, address, mobileno, email, remarks) VALUES ($1, $2, $3, $4, $5, $6, $7)";
        $result = pg_query_params($dbconn, $query, [$name, $date_of_birth, $skill_code, $address, $mobileno, $email, $remarks]);

        if ($result) {
            echo "Data stored successfully";
        } else {
            echo "Error: " . pg_last_error($dbconn);
        }
    } else {
        echo implode("<br>", $errors);
    }
}
?>
