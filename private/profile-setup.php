<?php
require __DIR__ . '/../assets/php/auth-check.php';
require_login('../login.html'); // Redirect to login if not logged in
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile Setup - Zawaaj As-Salafi</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/profile-setup.css">
    </head>
    <body>
        <div class="container py-5">
            <div class="card shadow mx-auto" style="max-width: 900px;">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Profile Setup</h3>
                    <form id="profileSetupForm">

                        <!-- Progress Bar -->
                        <div class="progress mb-4">
                            <div id="profileSetupProgress" class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <!-- STEP 1: Gender -->
                        <div class="step" id="step1">
                            <h4>Step 1: Gender</h4>
                            <div class="mb-3">
                                <label class="form-label">I am a:</label>
                                <select class="form-select" id="gender" required>
                                    <option value="" selected disabled>Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" id="nextStep1">Next</button>
                        </div>

                        <!-- STEP 2: About Yourself -->
                        <div class="step d-none" id="step2">
                            <h4>Step 2: About Yourself</h4>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Age</label>
                                    <input type="number" class="form-control" name="age" min="18" max="100" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Marital Status</label>
                                    <select class="form-select" name="marital_status">
                                        <option value="" selected disabled>Select</option>
                                        <option value="single">Single</option>
                                        <option value="divorced">Divorced</option>
                                        <option value="widowed">Widowed</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Religious Affiliation</label>
                                    <input type="text" class="form-control" value="Salafi" readonly>
                                </div>
                            </div>

                            <!-- Ethnicity / Nationality / Languages (with multiple options) -->
                            <?php
                            $multiFields = ['ethnicity', 'nationality', 'languages'];
                            foreach ($multiFields as $field) {
                                echo '<div class="mb-3 mt-3">';
                                echo '<label class="form-label">'.ucfirst($field).'</label>';
                                echo '<select class="form-select" name="'.$field.'">';
                                echo '<option value="" selected disabled>Select '.$field.'</option>';
                                if ($field === 'ethnicity') {
                                    echo '<option value="arab">Arab</option><option value="asian">Asian</option><option value="african">African</option><option value="european">European</option>';
                                } elseif ($field === 'nationality') {
                                    echo '<option value="saudi">Saudi</option><option value="egyptian">Egyptian</option><option value="pakistani">Pakistani</option><option value="indian">Indian</option>';
                                } elseif ($field === 'languages') {
                                    echo '<option value="arabic">Arabic</option><option value="english">English</option><option value="french">French</option>';
                                }
                                echo '</select>';
                                echo '<div class="form-check mt-2">';
                                echo '<input class="form-check-input" type="checkbox" id="'.$field.'Multiple">';
                                echo '<label class="form-check-label" for="'.$field.'Multiple">I have multiple '.$field.'</label>';
                                echo '</div>';
                                echo '<div class="mt-2 d-none" id="'.$field.'ExtraContainer"></div>';
                                echo '</div>';
                            }
                            ?>

                            <!-- Female-specific dress -->
                            <div class="mb-3 d-none" id="femaleDressDiv">
                                <label class="form-label">How do you dress?</label>
                                <select class="form-select" name="dress">
                                    <option value="" selected disabled>Select</option>
                                    <option value="niqab">Niqab</option>
                                    <option value="hijab_abaya">Hijab & Abayah</option>
                                    <option value="hijab_normal">Hijab & Normal Clothes</option>
                                    <option value="no_hijab">No Hijab</option>
                                </select>
                            </div>

                            <!-- Additional personal info -->
                            <div class="row g-3 mt-3">
                                <div class="col-md-3">
                                    <label class="form-label">Education (Optional)</label>
                                    <input type="text" class="form-control" name="education">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" class="form-control" name="occupation" placeholder="Leave blank if private">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Income</label>
                                    <input type="number" class="form-control" name="income" placeholder="Optional">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Children?</label>
                                    <select class="form-select" name="children">
                                        <option value="" selected disabled>Select</option>
                                        <option value="none">None</option>
                                        <option value="yes">Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mt-3">
                                <div class="col-md-3">
                                    <label class="form-label">Height (cm)</label>
                                    <input type="number" class="form-control" name="height">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Weight (kg)</label>
                                    <input type="number" class="form-control" name="weight">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Do you pray 5x a day?</label>
                                    <select class="form-select" name="pray">
                                        <option value="" selected disabled>Select</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Arabic fluency</label>
                                    <select class="form-select" name="arabic">
                                        <option value="" selected disabled>Select</option>
                                        <option value="basic">Basic</option>
                                        <option value="intermediate">Intermediate</option>
                                        <option value="fluent">Fluent</option>
                                    </select>
                                </div>
                            </div>

                            <button type="button" class="btn btn-secondary me-2 mt-3" id="prevStep2">Previous</button>
                            <button type="button" class="btn btn-primary mt-3" id="nextStep2">Next</button>
                        </div>

                        <!-- STEP 3: Preferences -->
                        <div class="step d-none" id="step3">
                            <h4>Step 3: Your Preferences</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Preferred Country / Region</label>
                                    <select class="form-select" name="pref_country">
                                        <option value="" selected disabled>Select</option>
                                        <option value="any">Any</option>
                                        <option value="saudi">Saudi</option>
                                        <option value="egypt">Egypt</option>
                                        <option value="pakistan">Pakistan</option>
                                    </select>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="prefCountryMultiple">
                                        <label class="form-check-label" for="prefCountryMultiple">I have multiple preferred countries</label>
                                    </div>
                                    <div class="mt-2 d-none" id="prefCountryExtraContainer"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Preferred Ethnicity</label>
                                    <select class="form-select" name="pref_ethnicity">
                                        <option value="" selected disabled>Select</option>
                                        <option value="any">All</option>
                                        <option value="arab">Arab</option>
                                        <option value="asian">Asian</option>
                                    </select>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="prefEthnicityMultiple">
                                        <label class="form-check-label" for="prefEthnicityMultiple">I have multiple preferred ethnicities</label>
                                    </div>
                                    <div class="mt-2 d-none" id="prefEthnicityExtraContainer"></div>
                                </div>
                            </div>

                            <div class="row g-3 mt-3">
                                <div class="col-md-3">
                                    <label class="form-label">Preferred Min Age</label>
                                    <input type="number" class="form-control" name="pref_min_age" min="18">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Preferred Max Age</label>
                                    <input type="number" class="form-control" name="pref_max_age" min="18">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Preferred Marital Status</label>
                                    <select class="form-select" name="pref_marital">
                                        <option value="" selected disabled>Select</option>
                                        <option value="single">Single</option>
                                        <option value="divorced">Divorced</option>
                                        <option value="widowed">Widowed</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Open to Relocation?</label>
                                    <select class="form-select" name="pref_relocation">
                                        <option value="" selected disabled>Select</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mt-3">
                                <div class="col-md-3">
                                    <label class="form-label">Preferred Arabic Fluency</label>
                                    <select class="form-select" name="pref_arabic">
                                        <option value="" selected disabled>Select</option>
                                        <option value="basic">Basic</option>
                                        <option value="intermediate">Intermediate</option>
                                        <option value="fluent">Fluent</option>
                                    </select>
                                </div>
                            </div>

                            <button type="button" class="btn btn-secondary me-2 mt-3" id="prevStep3">Previous</button>
                            <button type="button" class="btn btn-primary mt-3" id="nextStep3">Next</button>
                        </div>

                        <!-- STEP 4: Additional Information -->
                        <div class="step d-none" id="step4">
                            <h4>Step 4: Additional Information</h4>
                            <div class="mb-3">
                                <label class="form-label">Describe your relationship with Islam</label>
                                <textarea class="form-control" name="islam_relation"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">What kind of marriage do you envision?</label>
                                <textarea class="form-control" name="marriage_type"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tell us about yourself, hobbies, etc.</label>
                                <textarea class="form-control" name="hobbies"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">How do you see your role as a spouse?</label>
                                <textarea class="form-control" name="spouse_role"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Qualities you value most in a spouse</label>
                                <textarea class="form-control" name="qualities"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">How close would you want your spouse to be to Islam? Explain</label>
                                <textarea class="form-control" name="spouse_islam"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Scholars you take from (optional)</label>
                                <input type="text" class="form-control" name="scholars">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Texts/mutun studied (optional)</label>
                                <input type="text" class="form-control" name="texts">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Additional notes (optional)</label>
                                <textarea class="form-control" name="notes"></textarea>
                            </div>

                            <button type="button" class="btn btn-secondary me-2 mt-3" id="prevStep4">Previous</button>
                            <button type="submit" class="btn btn-success mt-3">Finish</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    <script src="../js/profile-setup.js"></script>
    </body>
</html>
