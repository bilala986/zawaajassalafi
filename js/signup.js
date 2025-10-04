document.getElementById("signupForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const email = document.getElementById("signupEmail").value.trim();
    const password = document.getElementById("signupPassword").value;
    const confirm = document.getElementById("signupConfirmPassword").value;
    const errorBox = document.getElementById("errorBox");
    errorBox.classList.add("d-none");

    if (password !== confirm) {
        errorBox.textContent = "Passwords do not match.";
        errorBox.classList.remove("d-none");
        return;
    }

    if (password.length < 8 || !/\d/.test(password)) {
        errorBox.textContent = "Password must be at least 8 characters and contain a number.";
        errorBox.classList.remove("d-none");
        return;
    }

    try {
        const res = await fetch("/zawaaj-as-salafi/assets/php/signup.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ email, password, confirm })
        });

        const data = await res.json();

        if (data.status === "success") {
            // Redirect to dashboard in private folder
            window.location.href = "/zawaaj-as-salafi/private/dashboard.php";
        } else {
            errorBox.textContent = data.message;
            errorBox.classList.remove("d-none");
        }
    } catch (err) {
        errorBox.textContent = "Server error. Please try again.";
        errorBox.classList.remove("d-none");
    }
});
