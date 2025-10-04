// dashboard.js
(async function() {
    try {
        const res = await fetch("/zawaaj-as-salafi/assets/php/dashboard-access.php");
        const data = await res.json();

        if (data.authorized) {
            document.body.style.display = "block"; // show page
        } else {
            window.location.href = "/zawaaj-as-salafi/login.html"; // redirect if not logged in
        }
    } catch {
        window.location.href = "/zawaaj-as-salafi/login.html";
    }
})();
