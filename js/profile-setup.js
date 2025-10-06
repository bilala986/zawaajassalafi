console.log("✅ profile-setup.js loaded");

document.addEventListener('DOMContentLoaded', () => {
    // ---- DOM References ----
    const steps = Array.from(document.querySelectorAll('.step'));
    const progressBar = document.getElementById('profileSetupProgress');
    let currentStep = 0;

    // Injected profile data from PHP
    const profileData = window.profileData || {};

    // ---- Step Navigation ----
    const nextBtns = {
        step1: document.getElementById('nextStep1'),
        step2: document.getElementById('nextStep2'),
        step3: document.getElementById('nextStep3')
    };
    const prevBtns = {
        step2: document.getElementById('prevStep2'),
        step3: document.getElementById('prevStep3'),
        step4: document.getElementById('prevStep4')
    };

    const showStep = index => {
        steps.forEach((step, i) => {
            step.classList.toggle('d-none', i !== index);
        });
        const percent = (index / (steps.length - 1)) * 100;
        progressBar.style.width = percent + '%';
        progressBar.setAttribute('aria-valuenow', percent);
    };
    showStep(currentStep);

    // ---- Validation ----
    const validateStep = stepEl => {
        let valid = true;
        const requiredFields = stepEl.querySelectorAll('input[required], select[required]');
        requiredFields.forEach(f => {
            if (!f.value || f.value === '') {
                f.classList.add('is-invalid');
                valid = false;
            } else f.classList.remove('is-invalid');
        });
        return valid;
    };

    // ---- Step 1: Gender ----
    nextBtns.step1.addEventListener('click', () => {
        const gender = document.getElementById('gender');
        const femaleDiv = document.getElementById('femaleDressDiv');
        if (!gender.value) return gender.classList.add('is-invalid');

        femaleDiv.classList.toggle('d-none', gender.value !== 'female');
        const dressField = femaleDiv.querySelector('select[name="dress"]');
        if (gender.value === 'female') dressField.setAttribute('required', 'required');
        else dressField.removeAttribute('required');

        currentStep++; showStep(currentStep);
    });

    // ---- Step 2 ----
    nextBtns.step2.addEventListener('click', () => {
        if (!validateStep(steps[1])) return;
        currentStep++; showStep(currentStep);
    });

    // ---- Step 3 ----
    nextBtns.step3.addEventListener('click', () => {
        if (!validateStep(steps[2])) return;

        const minAge = parseInt(document.querySelector('[name="pref_min_age"]').value) || 18;
        const maxAge = parseInt(document.querySelector('[name="pref_max_age"]').value) || 100;
        if (minAge > maxAge) {
            const field = document.querySelector('[name="pref_min_age"]');
            field.classList.add('is-invalid');
            field.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        currentStep++; showStep(currentStep);
    });

    // ---- Previous Buttons ----
    Object.values(prevBtns).forEach(btn => {
        btn.addEventListener('click', () => { currentStep--; showStep(currentStep); });
    });

    // ---- Multi-Select Handling ----
    const multiFields = [
        { id: 'ethnicity', selectId: 'ethnicitySelect' },
        { id: 'nationality', selectId: 'nationalitySelect' },
        { id: 'languages', selectId: 'languagesSelect' },
        { id: 'prefCountry', selectId: 'prefCountrySelect' },
        { id: 'prefEthnicity', selectId: 'prefEthnicitySelect' }
    ];

    const setupMultiSelect = ({ id, selectId }) => {
        const checkbox = document.getElementById(id + 'Multiple');
        const container = document.getElementById(id + 'ExtraContainer');
        const saved = profileData[id] ? profileData[id].split(',') : [];

        const addClone = val => {
            const wrapper = document.createElement('div');
            wrapper.className = 'd-inline-flex align-items-center me-2 mb-2';
            const clone = document.getElementById(selectId).cloneNode(true);
            clone.name = id + 'Extra[]';
            if (val) clone.value = val;
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.textContent = '×';
            removeBtn.className = 'btn btn-sm btn-outline-danger ms-1';
            removeBtn.addEventListener('click', () => wrapper.remove());
            wrapper.append(clone, removeBtn);
            container.appendChild(wrapper);
        };

        // Prefill existing
        if (saved.length > 1) {
            checkbox.checked = true;
            container.classList.remove('d-none');
            saved.slice(1).forEach(v => addClone(v));
        }
        if (saved.length >= 1) document.getElementById(selectId).value = saved[0];

        // Checkbox toggle
        checkbox.addEventListener('change', () => {
            container.classList.toggle('d-none', !checkbox.checked);
            container.innerHTML = '';
            if (checkbox.checked) addClone();
        });
    };
    multiFields.forEach(setupMultiSelect);

    // ---- Load Select Options ----
    const loadOptions = async () => {
        const countries = await (await fetch('../json/countries.json')).json();
        const langs = await (await fetch('../json/languages.json')).json();

        const countryOpts = countries.map(c => ({ value: c.iso2, label: c.name })).sort((a,b)=>a.label.localeCompare(b.label));
        const ethnicityOpts = countries.map(c => ({ value: c.name, label: c.name }));
        const nationalityOpts = countries.map(c => ({ value: c.nationality || c.name, label: c.nationality || c.name }));

        const languageOpts = Object.entries(langs).map(([code,name]) => ({ value: code, label: name })).sort((a,b)=>a.label.localeCompare(b.label));

        const populate = (selectId, items, val) => {
            const sel = document.getElementById(selectId);
            sel.innerHTML = '<option value="" disabled selected>Select</option>';
            items.forEach(i => sel.append(new Option(i.label, i.value)));
            if (val) sel.value = val;
        };

        populate('countryResidenceSelect', countryOpts, profileData['country_residence']);
        populate('ethnicitySelect', ethnicityOpts, profileData['ethnicity']?.split(',')[0]);
        populate('nationalitySelect', nationalityOpts, profileData['nationality']?.split(',')[0]);
        populate('prefCountrySelect', [{value:'any',label:'Any'}, ...countryOpts], profileData['prefCountry']?.split(',')[0]);
        populate('prefEthnicitySelect', [{value:'any',label:'All'}, ...ethnicityOpts], profileData['prefEthnicity']?.split(',')[0]);
        populate('languagesSelect', languageOpts, profileData['languages']?.split(',')[0]);
    };
    loadOptions();

    // ---- Form Submission ----
    document.getElementById('profileSetupForm').addEventListener('submit', e => {
        e.preventDefault();
        const formData = new FormData(e.target);

        // Merge multi-select extras
        multiFields.forEach(f => {
            const main = formData.get(f.id);
            const extras = formData.getAll(f.id + 'Extra[]');
            formData.set(f.id, [main, ...extras].filter(Boolean).join(','));
            formData.delete(f.id + 'Extra[]');
        });

        fetch(e.target.action, { method: 'POST', body: formData })
            .then(res => res.text())
            .then(r => {
                if (r.trim() === 'success') window.location.href = '../private/dashboard.php';
                else alert('❌ Error saving profile: ' + r);
            }).catch(console.error);
    });

});
