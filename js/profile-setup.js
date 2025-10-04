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
        const percent = ((index + 1) / steps.length) * 100;
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

    // Form submit
    const form = document.getElementById('profileSetupForm');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Profile setup submitted successfully!');
    });
});
