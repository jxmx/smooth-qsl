window.addEventListener("load", async function(){
    // Restore saved theme on load
    const saved = localStorage.getItem("theme");
    if (saved) {
        document.documentElement.setAttribute("data-theme", saved);
        document.documentElement.setAttribute("data-bs-theme", saved);
    }

    document.getElementById("theme-light").addEventListener("click", () => {
        document.documentElement.setAttribute("data-theme", "light");
        document.documentElement.setAttribute("data-bs-theme", "light");
        localStorage.setItem("theme", "light");
    });

    document.getElementById("theme-dark").addEventListener("click", () => {
        document.documentElement.setAttribute("data-theme", "dark");
        document.documentElement.setAttribute("data-bs-theme", "dark");
        localStorage.setItem("theme", "dark");
    });
});
