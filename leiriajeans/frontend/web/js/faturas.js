document.addEventListener("DOMContentLoaded", function () {
    const paymentOptions = document.querySelectorAll(".payment__type-shop");

    paymentOptions.forEach(option => {
        option.addEventListener("click", function () {
            // Remove a classe 'active-shop' de todos os métodos de pagamento
            paymentOptions.forEach(opt => opt.classList.remove("active-shop"));

            // Adiciona a classe 'active-shop' ao método de pagamento clicado
            this.classList.add("active-shop");
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const paymentOptions = document.querySelectorAll(".payment__type-shop");
    const creditCardForm = document.querySelector(".credit-card-form");
    const mbwayForm = document.querySelector(".mbway-form");
    const paypalForm = document.querySelector(".paypal-form");

    // Função para resetar os formulários visíveis
    function resetForms() {
        creditCardForm.classList.add("hidden");
        mbwayForm.classList.add("hidden");
        paypalForm.classList.add("hidden");
    }

    // Adiciona um listener a cada opção de pagamento
    paymentOptions.forEach(option => {
        option.addEventListener("click", function () {
            // Remove a classe 'active-shop' de todas as opções de pagamento
            paymentOptions.forEach(opt => opt.classList.remove("active-shop"));
            this.classList.add("active-shop");

            // Exibe o formulário correspondente
            resetForms();
            if (this.classList.contains("payment__type--multibanco-shop")) {
                creditCardForm.classList.remove("hidden");
            } else if (this.classList.contains("payment__type--mbway-shop")) {
                mbwayForm.classList.remove("hidden");
            } else if (this.classList.contains("payment__type--paypal-shop")) {
                paypalForm.classList.remove("hidden");
            }
        });
    });
});
