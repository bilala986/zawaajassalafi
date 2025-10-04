document.getElementById("loginForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const email = document.getElementById("loginEmail").value.trim();
    const password = document.getElementById("loginPassword").value;
    const errorBox = document.getElementById("loginErrorBox");
    errorBox.classList.add("d-none");

    try {
        const res = await fetch("/zawaaj-as-salafi/assets/php/login.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ email, password })
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
