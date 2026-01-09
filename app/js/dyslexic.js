function toggleDyslexic() {
    const isActive = document.body.classList.toggle("dyslexic");
    localStorage.setItem("dyslexic", isActive);

    updateDyslexicIcons();
}

// met à jour le bouton et les icones
function updateDyslexicIcons() {
    const iconLight = document.getElementById("icon-light");
    const iconDark = document.getElementById("icon-dark");

    if (document.body.classList.contains("dyslexic")) {
        iconLight.style.display = "none";
        iconDark.style.display = "inline";
    } else {
        iconLight.style.display = "inline";
        iconDark.style.display = "none";
    }
}

// applique le mode au chargement de la page
document.addEventListener("DOMContentLoaded", () => {
    if (localStorage.getItem("dyslexic") === "true") {
        document.body.classList.add("dyslexic");
    }
    updateDyslexicIcons(); 
});
