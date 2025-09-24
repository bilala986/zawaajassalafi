document.addEventListener("DOMContentLoaded", () => {
  const steps = document.querySelectorAll(".form-step");
  const nextBtns = document.querySelectorAll(".next-step");
  const prevBtns = document.querySelectorAll(".prev-step");
  const progressBar = document.getElementById("progress-bar");
  let currentStep = 0;

  // Populate Age Dropdown (18–100)
  const ageSelect = document.getElementById("ageSelect");
  if (ageSelect) {
    for (let i = 18; i <= 100; i++) {
      const option = document.createElement("option");
      option.value = i;
      option.textContent = i;
      ageSelect.appendChild(option);
    }
  }

  // Populate Preferred Min & Max Age Dropdowns
  const preferredMinAge = document.getElementById("preferredMinAge");
  const preferredMaxAge = document.getElementById("preferredMaxAge");
  if (preferredMinAge && preferredMaxAge) {
    for (let i = 18; i <= 100; i++) {
      const minOption = document.createElement("option");
      minOption.value = i;
      minOption.textContent = i;
      preferredMinAge.appendChild(minOption);

      const maxOption = document.createElement("option");
      maxOption.value = i;
      maxOption.textContent = i;
      preferredMaxAge.appendChild(maxOption);
    }
  }

  // Real-time validation for Preferred Age Range
  function validateAgeRange() {
    const minAge = parseInt(preferredMinAge.value);
    const maxAge = parseInt(preferredMaxAge.value);
    const feedbackDiv = preferredMaxAge.nextElementSibling; // assumes invalid-feedback div exists

    if (!isNaN(minAge) && !isNaN(maxAge) && maxAge < minAge) {
      preferredMaxAge.classList.add("is-invalid");
      if (feedbackDiv) feedbackDiv.textContent = "Max age must be greater than or equal to min age.";
      return false;
    } else {
      preferredMaxAge.classList.remove("is-invalid");
      if (feedbackDiv) feedbackDiv.textContent = "";
      return true;
    }
  }

  preferredMinAge.addEventListener("change", validateAgeRange);
  preferredMaxAge.addEventListener("change", validateAgeRange);

  // Fetch countries and populate selects
  async function loadCountries() {
    try {
      const response = await fetch("json/countries.json");
      const countries = await response.json();

      populateSelect("ethnicitySelect", countries, "name");
      populateSelect("nationalitySelect", countries, "nationality");
      populateSelect("preferredCountrySelect", countries, "name");
      populateSelect("countryOfResidenceSelect", countries, "name");
      populateSelect("preferredEthnicitySelect", countries, "name"); // ✅ new


      // Location modal logic (optional, can comment out hiding modal if already accepted)
      if (!localStorage.getItem("declinedLocation") && !localStorage.getItem("acceptedLocation")) {
        const locationModal = new bootstrap.Modal(document.getElementById("locationModal"));
        locationModal.show();

        document.getElementById("confirmLocation").addEventListener("click", () => {
          // locationModal.hide(); // Commented out to test
          localStorage.setItem("acceptedLocation", "true");
          requestLocation();
        });

        document.getElementById("declineLocation").addEventListener("click", () => {
          localStorage.setItem("declinedLocation", "true");
        });
      } else if (localStorage.getItem("acceptedLocation")) {
        requestLocation();
      }

    } catch (error) {
      console.error("Error loading countries:", error);
    }
  }

  function populateSelect(selectId, data, key) {
    const select = document.getElementById(selectId);
    if (select && Array.isArray(data)) {
      let values = data
        .map(country => country[key])
        .filter(v => v && v.trim() !== "");

      values = [...new Set(values)].sort((a, b) => a.localeCompare(b));

      const placeholder = document.createElement("option");
      placeholder.value = "";
      placeholder.textContent = "Select...";
      placeholder.disabled = true;
      placeholder.selected = true;
      select.appendChild(placeholder);

      values.forEach(value => {
        const option = document.createElement("option");
        option.value = value;
        option.textContent = value;
        select.appendChild(option);
      });
    }
  }

  async function requestLocation() {
    if ("geolocation" in navigator) {
      navigator.geolocation.getCurrentPosition(async (pos) => {
        const lat = pos.coords.latitude;
        const lon = pos.coords.longitude;

        try {
          const geoResponse = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`
          );
          const geoData = await geoResponse.json();
          const userCountry = geoData.address?.country;

          if (userCountry) {
            autoSelectCountry(userCountry);
          }
        } catch (error) {
          console.warn("Could not fetch location country:", error);
        }
      });
    }
  }

  // Only set "Country of Residence" from geolocation
  function autoSelectCountry(userCountry) {
    const select = document.getElementById("countryOfResidenceSelect");
    if (select) {
      for (let option of select.options) {
        if (option.text.toLowerCase() === userCountry.toLowerCase()) {
          option.selected = true;
          break;
        }
      }
    }
  }

  loadCountries();

  // Step control
  function showStep(step) {
    steps.forEach((s, i) => s.classList.toggle("active", i === step));
    progressBar.style.width = `${((step + 1) / steps.length) * 100}%`;
  }

  function validateStep(step) {
    const inputs = steps[step].querySelectorAll("input, select, textarea");
    let valid = true;

    inputs.forEach(input => {
      if (input.hasAttribute("required") && !input.value.trim()) {
        input.classList.add("is-invalid");
        valid = false;
      } else {
        input.classList.remove("is-invalid");
      }
    });

    // Custom validation for Preferred Min/Max Age on Step 2
    if (step === 1) {
      if (!validateAgeRange()) valid = false;
    }

    return valid;
  }

  nextBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      if (validateStep(currentStep)) {
        currentStep++;
        showStep(currentStep);
      }
    });
  });

  prevBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      currentStep--;
      showStep(currentStep);
    });
  });

  showStep(currentStep);
});
