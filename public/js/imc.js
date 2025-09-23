// Calcul IMC
function calculIMC(weight, height) {
    if (!weight || !height) return null;
    const heightInMeters = height / 100;
    return (weight / (heightInMeters * heightInMeters)).toFixed(1);
}

// Catégories IMC
function categorieIMC(imc) {
    if (imc === null) return "Pas de donnée";
    if (imc < 18.5) return "Insuffisance pondérale";
    if (imc < 25) return "Poids normal";
    if (imc < 30) return "Surpoids";
    return "Obésité";
}

// Au chargement
document.addEventListener("DOMContentLoaded", () => {
    console.log("✅ imc.js loaded");

    const container = document.querySelector(".goal-container");
    if (!container) {
        console.error("⚠️ Pas de .goal-container trouvé");
        return;
    }

    const height = parseFloat(container.dataset.height);
    const weight = parseFloat(container.dataset.weight);

    console.log("📏 height:", height, "⚖️ weight:", weight);

    const imc =
        !isNaN(height) && !isNaN(weight) ? calculIMC(weight, height) : null;

    const category = categorieIMC(imc);

    document.getElementById("current-imc").textContent = imc ?? "N/A";
    document.getElementById("imc-category").textContent = category;
});
