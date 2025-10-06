<?php
require __DIR__ . '/../assets/php/auth-check.php';
require_login('../login.html');
require_once '../assets/php/profile-check.php';

$user_id = $_SESSION['user_id'];
$profile_data = get_user_profile($pdo, $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Setup - Zawaaj As-Salafi</title>

    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/profile-setup.css">
</head>
<body>
<div class="container py-5">
    <div class="card shadow mx-auto" style="max-width: 900px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-1">Profile Setup</h3>
            <p class="text-center text-muted mb-4" style="font-size: 0.9rem;">
                Answer the questions to help us find the right spouse for you.
            </p>

            <!-- Pass PHP profile data to JS -->
            <script>
                const profileData = <?= json_encode($profile_data ?? []) ?>;
            </script>

            <form id="profileSetupForm" method="post" action="../assets/php/save_profile.php">

                <!-- Progress Bar -->
                <div class="progress mb-4" style="height: 8px;">
                    <div id="profileSetupProgress" class="progress-bar bg-success" role="progressbar"
                         style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <!-- STEP 1: Gender -->
                <div class="step" id="step1">
                    <h4>Step 1: Gender</h4>
                    <div class="mb-3">
                        <label class="form-label">I am a:</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="" disabled selected>Select gender</option>
                            <option value="male" <?= (!empty($profile_data['gender']) && $profile_data['gender'] === 'male') ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= (!empty($profile_data['gender']) && $profile_data['gender'] === 'female') ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-success" id="nextStep1">Next</button>
                </div>

                <!-- STEP 2: About Yourself -->
                <div class="step d-none" id="step2">
                    <h4>Step 2: About Yourself</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="dob"
                                   value="<?= htmlspecialchars($profile_data['dob'] ?? '') ?>"
                                   max="<?= date('Y-m-d', strtotime('-18 years')) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Country of Residence</label>
                            <select class="form-select" id="countryResidenceSelect" name="country_residence" required>
                                <option value="" disabled selected>Select</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ethnicity</label>
                            <select class="form-select" id="ethnicitySelect" name="ethnicity" required>
                                <option value="" disabled selected>Select</option>
                            </select>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" id="ethnicityMultiple">
                                <label class="form-check-label" for="ethnicityMultiple">I have multiple ethnicities</label>
                            </div>
                            <div class="mt-2 d-none" id="ethnicityExtraContainer"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nationality</label>
                            <select class="form-select" id="nationalitySelect" name="nationality" required>
                                <option value="" disabled selected>Select</option>
                            </select>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" id="nationalityMultiple">
                                <label class="form-check-label" for="nationalityMultiple">I have multiple nationalities</label>
                            </div>
                            <div class="mt-2 d-none" id="nationalityExtraContainer"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Languages</label>
                            <select class="form-select" id="languagesSelect" name="languages" required>
                                <option value="" disabled selected>Select</option>
                            </select>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" id="languagesMultiple">
                                <label class="form-check-label" for="languagesMultiple">I speak multiple languages</label>
                            </div>
                            <div class="mt-2 d-none" id="languagesExtraContainer"></div>
                        </div>

                        <!-- Remaining inputs stay the same -->
                        <div class="col-md-6">
                            <label class="form-label">Education (Optional)</label>
                            <input type="text" class="form-control" name="education" value="<?= htmlspecialchars($profile_data['education'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Occupation</label>
                            <input type="text" class="form-control" name="occupation" placeholder="Leave blank if private"
                                   value="<?= htmlspecialchars($profile_data['occupation'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Income</label>
                            <input type="number" class="form-control" name="income"
                                   value="<?= htmlspecialchars($profile_data['income'] ?? '') ?>" placeholder="Optional">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Children?</label>
                            <select class="form-select" name="children" required>
                                <option value="" disabled selected>Select</option>
                                <option value="none" <?= (!empty($profile_data['children']) && $profile_data['children'] === 'none') ? 'selected' : '' ?>>None</option>
                                <option value="yes" <?= (!empty($profile_data['children']) && $profile_data['children'] === 'yes') ? 'selected' : '' ?>>Yes</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Height (cm)</label>
                            <input type="number" class="form-control" name="height" required
                                   value="<?= htmlspecialchars($profile_data['height'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" class="form-control" name="weight" required
                                   value="<?= htmlspecialchars($profile_data['weight'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Do you pray 5x a day?</label>
                            <select class="form-select" name="pray" required>
                                <option value="" disabled selected>Select</option>
                                <option value="yes" <?= (!empty($profile_data['pray']) && $profile_data['pray'] === 'yes') ? 'selected' : '' ?>>Yes</option>
                                <option value="no" <?= (!empty($profile_data['pray']) && $profile_data['pray'] === 'no') ? 'selected' : '' ?>>No</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Arabic Fluency</label>
                            <select class="form-select" name="arabic" required>
                                <option value="" disabled selected>Select</option>
                                <option value="basic" <?= (!empty($profile_data['arabic']) && $profile_data['arabic'] === 'basic') ? 'selected' : '' ?>>Basic</option>
                                <option value="intermediate" <?= (!empty($profile_data['arabic']) && $profile_data['arabic'] === 'intermediate') ? 'selected' : '' ?>>Intermediate</option>
                                <option value="fluent" <?= (!empty($profile_data['arabic']) && $profile_data['arabic'] === 'fluent') ? 'selected' : '' ?>>Fluent</option>
                            </select>
                        </div>

                        <div class="col-md-6 d-none" id="femaleDressDiv">
                            <label class="form-label">How do you dress?</label>
                            <select class="form-select" name="dress" required>
                                <option value="" disabled selected>Select</option>
                                <option value="niqab" <?= (!empty($profile_data['dress']) && $profile_data['dress'] === 'niqab') ? 'selected' : '' ?>>Niqab</option>
                                <option value="hijab_abaya" <?= (!empty($profile_data['dress']) && $profile_data['dress'] === 'hijab_abaya') ? 'selected' : '' ?>>Hijab & Abayah</option>
                                <option value="hijab_normal" <?= (!empty($profile_data['dress']) && $profile_data['dress'] === 'hijab_normal') ? 'selected' : '' ?>>Hijab & Normal Clothes</option>
                                <option value="no_hijab" <?= (!empty($profile_data['dress']) && $profile_data['dress'] === 'no_hijab') ? 'selected' : '' ?>>No Hijab</option>
                            </select>
                        </div>

                    </div>

                    <button type="button" class="btn btn-secondary me-2 mt-3" id="prevStep2">Previous</button>
                    <button type="button" class="btn btn-success mt-3" id="nextStep2">Next</button>
                </div>

                <!-- STEP 3: Preferences -->
                <div class="step d-none" id="step3">
                    <h4>Step 3: Your Preferences</h4>
                    <div class="row g-3">
                        <!-- Preferred Country / Region -->
                        <div class="col-md-6">
                            <label class="form-label">Preferred Country / Region</label>
                            <select class="form-select" id="prefCountrySelect" name="prefCountry" required>
                                <option value="" disabled selected>Select</option>
                            </select>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="prefCountryMultiple">
                                <label class="form-check-label" for="prefCountryMultiple">I have multiple preferred countries</label>
                            </div>
                            <div class="mt-2 d-none" id="prefCountryExtraContainer"></div>
                        </div>

                        <!-- Preferred Ethnicity -->
                        <div class="col-md-6">
                            <label class="form-label">Preferred Ethnicity</label>
                            <select class="form-select" id="prefEthnicitySelect" name="prefEthnicity" required>
                                <option value="" disabled selected>Select</option>
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
                            <input type="number" class="form-control" name="pref_min_age" min="18"
                                   value="<?= htmlspecialchars($profile_data['pref_min_age'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Preferred Max Age</label>
                            <input type="number" class="form-control" name="pref_max_age" min="18"
                                   value="<?= htmlspecialchars($profile_data['pref_max_age'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Preferred Marital Status</label>
                            <select class="form-select" name="pref_marital" required>
                                <option value="" disabled selected>Select</option>
                                <option value="single" <?= (!empty($profile_data['pref_marital']) && $profile_data['pref_marital'] === 'single') ? 'selected' : '' ?>>Single</option>
                                <option value="divorced" <?= (!empty($profile_data['pref_marital']) && $profile_data['pref_marital'] === 'divorced') ? 'selected' : '' ?>>Divorced</option>
                                <option value="widowed" <?= (!empty($profile_data['pref_marital']) && $profile_data['pref_marital'] === 'widowed') ? 'selected' : '' ?>>Widowed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Open to Relocation?</label>
                            <select class="form-select" name="pref_relocation" required>
                                <option value="" disabled selected>Select</option>
                                <option value="yes" <?= (!empty($profile_data['pref_relocation']) && $profile_data['pref_relocation'] === 'yes') ? 'selected' : '' ?>>Yes</option>
                                <option value="no" <?= (!empty($profile_data['pref_relocation']) && $profile_data['pref_relocation'] === 'no') ? 'selected' : '' ?>>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-3">
                            <label class="form-label">Preferred Arabic Fluency</label>
                            <select class="form-select" name="pref_arabic" required>
                                <option value="" disabled selected>Select</option>
                                <option value="basic" <?= (!empty($profile_data['pref_arabic']) && $profile_data['pref_arabic'] === 'basic') ? 'selected' : '' ?>>Basic</option>
                                <option value="intermediate" <?= (!empty($profile_data['pref_arabic']) && $profile_data['pref_arabic'] === 'intermediate') ? 'selected' : '' ?>>Intermediate</option>
                                <option value="fluent" <?= (!empty($profile_data['pref_arabic']) && $profile_data['pref_arabic'] === 'fluent') ? 'selected' : '' ?>>Fluent</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary me-2 mt-3" id="prevStep3">Previous</button>
                    <button type="button" class="btn btn-success mt-3" id="nextStep3">Next</button>
                </div>

                <!-- STEP 4: Additional Information -->
                <div class="step d-none" id="step4">
                    <h4>Step 4: Additional Information</h4>
                    <div class="mb-3">
                        <label class="form-label">Describe your relationship with Islam</label>
                        <textarea class="form-control" name="islam_relation"><?= htmlspecialchars($profile_data['islam_relation'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">What kind of marriage do you envision?</label>
                        <textarea class="form-control" name="marriage_type"><?= htmlspecialchars($profile_data['marriage_type'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tell us about yourself, hobbies, etc.</label>
                        <textarea class="form-control" name="hobbies"><?= htmlspecialchars($profile_data['hobbies'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">How do you see your role as a spouse?</label>
                        <textarea class="form-control" name="spouse_role"><?= htmlspecialchars($profile_data['spouse_role'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Qualities you value most in a spouse</label>
                        <textarea class="form-control" name="qualities"><?= htmlspecialchars($profile_data['qualities'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">How close would you want your spouse to be to Islam? Explain</label>
                        <textarea class="form-control" name="spouse_islam"><?= htmlspecialchars($profile_data['spouse_islam'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Scholars you take from (optional)</label>
                        <input type="text" class="form-control" name="scholars"
                               value="<?= htmlspecialchars($profile_data['scholars'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Texts/mutun studied (optional)</label>
                        <input type="text" class="form-control" name="texts"
                               value="<?= htmlspecialchars($profile_data['texts'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional notes (optional)</label>
                        <textarea class="form-control" name="notes"><?= htmlspecialchars($profile_data['notes'] ?? '') ?></textarea>
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
