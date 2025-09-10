const datadiv = document.getElementById("test");
console.log(datadiv);
const userJson = datadiv.dataset.imc;
console.log(userJson);
const objetJson = JSON.parse(userJson);
console.log(objetJson);
let userHeight = objetJson.height; // cm
console.log(objetJson.height);
console.log(userHeight + " cm");
let userWeight = objetJson.weight; // kg
console.log(objetJson.weight);
console.log(userWeight + " kg");

document.addEventListener("DOMContentLoaded", function () {
    // Récupération des données
    const userDataElement = document.getElementById("user-data");
    const user = JSON.parse(userDataElement.dataset.user);

    const weight = user.weight; // kg
    const height = user.height / 100; // cm → m
    const bmi = weight / (height * height);

    console.log("Poids:", weight, "Taille:", height, "IMC:", bmi.toFixed(2));

    // Écoute le formulaire
    const form = document.querySelector(".goal-form");
    form.addEventListener("submit", function (e) {
        const selectedGoal = document.querySelector(
            "input[name='goal']:checked"
        );

        if (!selectedGoal) {
            alert("Veuillez choisir un objectif.");
            e.preventDefault();
            return;
        }

        const goal = selectedGoal.value;

        if (goal === "muscle" && bmi >= 25) {
            alert(
                "⚠️ Vous êtes en surpoids (IMC " +
                    bmi.toFixed(1) +
                    "). Il est conseillé de perdre du poids avant."
            );
            e.preventDefault();
        } else if (goal === "weight-loss" && bmi < 18.5) {
            alert(
                "⚠️ Vous êtes déjà trop maigre (IMC " +
                    bmi.toFixed(1) +
                    "). Visez plutôt à maintenir ou à prendre du poids."
            );
            e.preventDefault();
        } else {
            alert("✅ Objectif accepté !");
        }
    });
});
