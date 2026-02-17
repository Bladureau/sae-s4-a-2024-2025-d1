document.addEventListener("DOMContentLoaded", function () {
    // S'assure que l'élément est bien caché au chargement
    document.getElementById("add-url-div").style.display = "none";
});

function toggleInput() {
    let inputDiv = document.getElementById("add-url-div");
    inputDiv.style.display = (inputDiv.style.display === "none") ? "block" : "none";
}