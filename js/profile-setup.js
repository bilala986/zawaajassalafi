document.addEventListener('DOMContentLoaded', () => {
    const steps = Array.from(document.querySelectorAll('.step'));
    const progressBar = document.getElementById('profileSetupProgress');
    let currentStep = 0;

    const showStep = (index) => {
        steps.forEach((step, i) => {
            if (i === index) {
                step.classList.remove('d-none');
                step.classList.add('fade-in');
            } else {
                step.classList.add('d-none');
                step.classList.remove('fade-in');
            }
        });

        const percent = (index / (steps.length - 1)) * 100;
        progressBar.style.width = percent + '%';
        progressBar.setAttribute('aria-valuenow', percent);
    };

    showStep(currentStep);

    const nextStep1 = document.getElementById('nextStep1');
    const nextStep2 = document.getElementById('nextStep2');
    const nextStep3 = document.getElementById('nextStep3');
    const prevStep2 = document.getElementById('prevStep2');
    const prevStep3 = document.getElementById('prevStep3');
    const prevStep4 = document.getElementById('prevStep4');

    function validateRequiredFields(stepElement) {
        let firstInvalid = null;
        let allValid = true;

        const requiredFields = stepElement.querySelectorAll('input[required], select[required]');

        requiredFields.forEach(field => {
            // ✅ Skip validation if the field OR its parent is hidden
            if (field.offsetParent === null || field.closest('.d-none')) {
                return;
            }

            const errorMsg = field.parentElement.querySelector('.error-msg');

            if (!field.value || field.value === "") {
                field.classList.add('is-invalid');
                if (errorMsg) {
                    errorMsg.textContent = "This field is required";
                } else {
                    const small = document.createElement('small');
                    small.classList.add('error-msg', 'text-danger');
                    small.textContent = "This field is required";
                    field.parentElement.appendChild(small);
                }

                if (!firstInvalid) firstInvalid = field;
                allValid = false;
            } else {
                field.classList.remove('is-invalid');
                if (errorMsg) errorMsg.textContent = "";
            }
        });

        if (!allValid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.classList.add('shake');
            setTimeout(() => firstInvalid.classList.remove('shake'), 500);
            return false;
        }

        return true;
    }

    // ✅ STEP 1 Validation (Gender)
    nextStep1.addEventListener('click', () => {
        const gender = document.getElementById('gender');
        const femaleDiv = document.getElementById('femaleDressDiv');

        if (!gender.value) {
            gender.classList.add('is-invalid', 'shake');
            const label = gender.parentElement.querySelector('.error-msg');
            if (label) {
                label.textContent = "Please select your gender";
            } else {
                const small = document.createElement('small');
                small.classList.add('error-msg', 'text-danger');
                small.textContent = "Please select your gender";
                gender.parentElement.appendChild(small);
            }
            setTimeout(() => gender.classList.remove('shake'), 500);
            gender.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        gender.classList.remove('is-invalid');
        femaleDiv.classList.toggle('d-none', gender.value !== 'female');

        currentStep++;
        showStep(currentStep);
    });

    // ✅ STEP 2 Validation
    nextStep2.addEventListener('click', () => {
        const step2 = document.querySelector('#step2');
        if (!validateRequiredFields(step2)) return;
        currentStep++;
        showStep(currentStep);
    });

    // ✅ STEP 3 Validation
    nextStep3.addEventListener('click', () => {
        const step3 = document.querySelector('#step3');
        if (!validateRequiredFields(step3)) return;

        const minAge = parseInt(document.querySelector('[name="pref_min_age"]').value) || 18;
        const maxAge = parseInt(document.querySelector('[name="pref_max_age"]').value) || 100;

        if (minAge > maxAge) {
            const field = document.querySelector('[name="pref_min_age"]');
            field.classList.add('is-invalid', 'shake');
            field.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => field.classList.remove('shake'), 500);
            return;
        }

        currentStep++;
        showStep(currentStep);
    });

    // ✅ STEP 4 Validation (if exists)
    if (typeof nextStep4 !== "undefined") {
        nextStep4.addEventListener('click', () => {
            const step4 = document.querySelector('#step4');
            if (!validateRequiredFields(step4)) return;
            // ✅ Submit or finish
        });
    }

    prevStep2.addEventListener('click', () => { currentStep--; showStep(currentStep); });
    prevStep3.addEventListener('click', () => { currentStep--; showStep(currentStep); });
    prevStep4.addEventListener('click', () => { currentStep--; showStep(currentStep); });



    function calculateAge(dob) {
        const diff = Date.now() - new Date(dob).getTime();
        return new Date(diff).getUTCFullYear() - 1970;
    }


    // Multi-selection fields
    const multiFields = [
        { id: 'ethnicity', sourceSelect: 'ethnicitySelect' },
        { id: 'nationality', sourceSelect: 'nationalitySelect' },
        { id: 'languages', sourceSelect: 'languagesSelect' },
        { id: 'prefCountry', sourceSelect: 'prefCountrySelect' },
        { id: 'prefEthnicity', sourceSelect: 'prefEthnicitySelect' }
    ];


    multiFields.forEach(({ id, sourceSelect }) => {
        const checkbox = document.getElementById(id + 'Multiple');
        const container = document.getElementById(id + 'ExtraContainer');

        if (checkbox) {
            checkbox.addEventListener('change', () => {
                container.classList.toggle('d-none', !checkbox.checked);
                container.innerHTML = '';
                if (checkbox.checked) {
                    addDropdownClone(sourceSelect, container, id);

                    const addBtn = document.createElement('button');
                    addBtn.type = 'button';
                    addBtn.textContent = '+ Add another';
                    addBtn.classList.add('btn', 'btn-sm', 'btn-outline-secondary', 'mb-2', 'add-btn');
                    addBtn.addEventListener('click', () => addDropdownClone(sourceSelect, container, id));
                    container.appendChild(addBtn);
                }
            });
        }
    });

    function addDropdownClone(sourceSelectId, container, fieldName) {
        const currentClones = container.querySelectorAll('select').length;
        const addBtn = container.querySelector('.add-btn');

        // Limit to 4 extra (1 main + 4 = 5 total)
        if (currentClones >= 4) {
            if (addBtn) addBtn.style.display = 'none';
            return;
        }

        const original = document.getElementById(sourceSelectId);
        const wrapper = document.createElement('div');
        wrapper.classList.add('d-inline-flex', 'align-items-center', 'me-2', 'mb-2');

        const clone = original.cloneNode(true);
        clone.name = fieldName + 'Extra[]';

        // Small remove button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.textContent = '×';
        removeBtn.classList.add('btn', 'btn-sm', 'btn-outline-danger', 'ms-1');
        removeBtn.style.padding = '0.2rem 0.4rem';

        removeBtn.addEventListener('click', () => {
            wrapper.remove();
            // Show "Add another" again if below max
            if (container.querySelectorAll('select').length < 4 && addBtn) {
                addBtn.style.display = 'inline-block';
            }
        });

        wrapper.appendChild(clone);
        wrapper.appendChild(removeBtn);

        container.insertBefore(wrapper, addBtn);

        // Hide button if limit reached after adding
        if (container.querySelectorAll('select').length >= 4 && addBtn) {
            addBtn.style.display = 'none';
        }
    }




    
    // Load Countries and Languages from JSON
    function loadSelectOptions() {
        // Load Countries (for Country of Residence, Ethnicity, Nationality, Preferred Country, Preferred Ethnicity)
        fetch('../json/countries.json')
            .then(res => res.json())
            .then(countries => {
                // Sort alphabetically by name
                countries.sort((a, b) => a.name.localeCompare(b.name));

                const countryOptions = countries.map(c => ({
                    value: c.iso2,
                    label: c.name
                }));

                const ethnicityOptions = countries.map(c => ({
                    value: c.name,
                    label: c.name
                }));

                const nationalityOptions = countries.map(c => ({
                    value: c.nationality || c.name,
                    label: c.nationality || c.name
                }));

                populateSelect('countryResidenceSelect', countryOptions);
                populateSelect('ethnicitySelect', ethnicityOptions);
                populateSelect('nationalitySelect', nationalityOptions);

                // ✅ ALSO populate Step 3 dropdowns:
                populateSelect('prefCountrySelect', [{ value: 'any', label: 'Any' }, ...countryOptions]);
                populateSelect('prefEthnicitySelect', [{ value: 'any', label: 'All' }, ...ethnicityOptions]);
            });

        // Load Languages (for Languages)
        fetch('../json/languages.json')
            .then(res => res.json())
            .then(languages => {
                const languageArray = Object.entries(languages).map(([code, name]) => ({
                    value: code,
                    label: name
                }));

                // Sort alphabetically
                languageArray.sort((a, b) => a.label.localeCompare(b.label));

                populateSelect('languagesSelect', languageArray);
            });
    }


    function populateSelect(selectId, items) {
        const select = document.getElementById(selectId);
        select.innerHTML = '<option value="" selected disabled>Select</option>';
        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.value;
            option.textContent = item.label;
            select.appendChild(option);
        });
    }

    loadSelectOptions();



    // Form submit
    const form = document.getElementById('profileSetupForm');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Profile setup submitted successfully!');
    });
});
