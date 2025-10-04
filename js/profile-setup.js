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

        // Update progress bar
        const percent = (index / (steps.length - 1)) * 100;
        progressBar.style.width = percent + '%';
        progressBar.setAttribute('aria-valuenow', percent);
    };

    showStep(currentStep);

    // Navigation buttons
    const nextStep1 = document.getElementById('nextStep1');
    const nextStep2 = document.getElementById('nextStep2');
    const nextStep3 = document.getElementById('nextStep3');
    const prevStep2 = document.getElementById('prevStep2');
    const prevStep3 = document.getElementById('prevStep3');
    const prevStep4 = document.getElementById('prevStep4');

    nextStep1.addEventListener('click', () => {
        const gender = document.getElementById('gender').value;
        if (!gender) { alert('Please select your gender'); return; }

        const femaleDiv = document.getElementById('femaleDressDiv');
        femaleDiv.classList.toggle('d-none', gender !== 'female');

        currentStep++;
        showStep(currentStep);
    });

    nextStep2.addEventListener('click', () => { currentStep++; showStep(currentStep); });
    nextStep3.addEventListener('click', () => {
        // Age validation
        const minAge = parseInt(document.querySelector('[name="pref_min_age"]').value) || 18;
        const maxAge = parseInt(document.querySelector('[name="pref_max_age"]').value) || 100;
        if (minAge > maxAge) { alert('Preferred min age cannot be greater than max age.'); return; }
        if (maxAge < minAge) { alert('Preferred max age cannot be less than min age.'); return; }
        currentStep++;
        showStep(currentStep);
    });

    prevStep2.addEventListener('click', () => { currentStep--; showStep(currentStep); });
    prevStep3.addEventListener('click', () => { currentStep--; showStep(currentStep); });
    prevStep4.addEventListener('click', () => { currentStep--; showStep(currentStep); });

    // Multi-selection fields
    const multiFields = ['ethnicity', 'nationality', 'languages', 'prefCountry', 'prefEthnicity'];
    multiFields.forEach(field => {
        const checkbox = document.getElementById(field + 'Multiple');
        const container = document.getElementById(field + 'ExtraContainer');

        if (checkbox) {
            checkbox.addEventListener('change', () => {
                container.classList.toggle('d-none', !checkbox.checked);
                container.innerHTML = '';
                if (checkbox.checked) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = field + 'Extra[]';
                    input.classList.add('form-control', 'mb-2');
                    input.placeholder = 'Enter additional ' + field;
                    container.appendChild(input);

                    const addBtn = document.createElement('button');
                    addBtn.type = 'button';
                    addBtn.textContent = '+ Add another';
                    addBtn.classList.add('btn', 'btn-sm', 'btn-outline-secondary', 'mb-2');
                    addBtn.addEventListener('click', () => {
                        const extraInput = document.createElement('input');
                        extraInput.type = 'text';
                        extraInput.name = field + 'Extra[]';
                        extraInput.classList.add('form-control', 'mb-2');
                        extraInput.placeholder = 'Enter additional ' + field;
                        container.appendChild(extraInput);
                    });
                    container.appendChild(addBtn);
                }
            });
        }
    });
    
    // Load Countries and Languages from JSON
    function loadSelectOptions() {
        // Load Countries (for Country of Residence, Ethnicity, Nationality)
        fetch('../json/countries.json')
            .then(res => res.json())
            .then(countries => {
                // Sort alphabetically by name
                countries.sort((a, b) => a.name.localeCompare(b.name));

                populateSelect('countryResidenceSelect', countries.map(c => ({
                    value: c.iso2,
                    label: `${c.name}`
                })));

                populateSelect('ethnicitySelect', countries.map(c => ({
                    value: c.name,
                    label: c.name
                })));

                populateSelect('nationalitySelect', countries.map(c => ({
                    value: c.nationality || c.name,
                    label: c.nationality || c.name
                })));
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
