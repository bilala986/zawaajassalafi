<?php
header("Content-Type: application/json");

// Database connection (adjust with your Namecheap DB credentials)
$host = "localhost";
$dbname = "your_database_name";
$username = "your_db_username";
$password = "your_db_password";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit;
}

// Read JSON body
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid data received."]);
    exit;
}

// Map camelCase (from frontend) → snake_case (DB schema)
$mappedData = [
    "gender" => $data["gender"] ?? null,
    "age" => $data["age"] ?? null,
    "country_of_residence" => $data["countryOfResidence"] ?? null,
    "ethnicity" => $data["ethnicity"] ?? null,
    "nationality" => $data["nationality"] ?? null,
    "marital_status" => $data["maritalStatus"] ?? null,
    "education_level" => $data["education"] ?? null,
    "occupation" => $data["occupation"] ?? null,

    // Preferences
    "preferred_country" => $data["preferredCountry"] ?? null,
    "preferred_ethnicity" => $data["preferredEthnicity"] ?? null,
    "preferred_min_age" => $data["preferredMinAge"] ?? null,
    "preferred_max_age" => $data["preferredMaxAge"] ?? null,
    "preferred_marital_status" => $data["preferredMaritalStatus"] ?? null,
    "relocation_preference" => $data["relocation"] ?? null,

    // Additional Info
    "islam_relation" => $data["islamRelation"] ?? null,
    "marriage_vision" => $data["marriageVision"] ?? null,
    "hobbies" => $data["hobbies"] ?? null,
    "spouse_role" => $data["spouseRole"] ?? null,
    "spouse_qualities" => $data["spouseQualities"] ?? null,
    "religiosity_preference" => $data["religiosityPreference"] ?? null,
    "scholars" => $data["scholars"] ?? null,
    "texts_studied" => $data["textsStudied"] ?? null,
    "additional_notes" => $data["additionalNotes"] ?? null,
];

// Prepare SQL
$stmt = $conn->prepare("
    INSERT INTO users (
        gender, age, country_of_residence, ethnicity, nationality, marital_status,
        education_level, occupation, preferred_country, preferred_ethnicity,
        preferred_min_age, preferred_max_age, preferred_marital_status, relocation_preference,
        islam_relation, marriage_vision, hobbies, spouse_role, spouse_qualities,
        religiosity_preference, scholars, texts_studied, additional_notes
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "SQL prepare failed: " . $conn->error]);
    exit;
}

// Bind parameters
$stmt->bind_param(
    "sissssssssiissssssssss",
    $mappedData["gender"],
    $mappedData["age"],
    $mappedData["country_of_residence"],
    $mappedData["ethnicity"],
    $mappedData["nationality"],
    $mappedData["marital_status"],
    $mappedData["education_level"],
    $mappedData["occupation"],
    $mappedData["preferred_country"],
    $mappedData["preferred_ethnicity"],
    $mappedData["preferred_min_age"],
    $mappedData["preferred_max_age"],
    $mappedData["preferred_marital_status"],
    $mappedData["relocation_preference"],
    $mappedData["islam_relation"],
    $mappedData["marriage_vision"],
    $mappedData["hobbies"],
    $mappedData["spouse_role"],
    $mappedData["spouse_qualities"],
    $mappedData["religiosity_preference"],
    $mappedData["scholars"],
    $mappedData["texts_studied"],
    $mappedData["additional_notes"]
);

// Execute
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Profile saved successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Insert failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
