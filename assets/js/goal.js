document.querySelector(".goal-form")?.addEventListener("submit", function (e) {
    e.preventDefault();
    const choice = document.querySelector('input[name="goal"]:checked');
    if (choice) {
        console.log("Selected goal:", choice.value);
        this.submit(); // submit normally if you want
    } else {
        alert("Veuillez choisir un objectif !");
    }
});
