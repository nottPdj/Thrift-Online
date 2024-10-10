function shippingCost() {
    const countrySelect = document.getElementById('country');
    const shippingCostInput = document.getElementById('shipping_cost');
    const totalPriceElement = document.querySelector('#total_price p');

    const total_line = document.querySelector('#total_price p');
    if (total_line) {
        const baseTotal = parseFloat(total_line.textContent.split(" ")[3]);
    }

    const shippingRates = {
        "Portugal": 2,
        "Spain": 5,
        "France": 5,
        "Andorra": 8,
        "Belgium": 8,
        "Luxembourg": 8,
        "Netherlands": 8,
        "Germany": 8,
        "Italy": 8,
        "Switzerland": 8,
        "Austria": 8,
        "Denmark": 15,
        "Poland": 15,
        "Czech Republic": 15,
        "Slovakia": 15,
        "Hungary": 15,
        "Slovenia": 15,
        "Croatia": 15,
        "Bosnia and Herzegovina": 15,
        "Montenegro": 15,
        "Serbia": 15,
        "Bulgaria": 15,
        "Romania": 15,
        "Greece": 15,
        "Finland": 15,
        "Norway": 15,
        "Sweden": 15,
        "Albania": 20,
        "North Macedonia": 20,
        "Kosovo": 20,
        "Moldova": 20,
        "Ukraine": 22,
        "Belarus": 22,
        "Lithuania": 22,
        "Latvia": 22,
        "Estonia": 22,
        "Russia": 25,
        "Turkey": 25,
        "Armenia": 25,
        "Azerbaijan": 25,
        "Georgia": 25
    };

    if (countrySelect) {
        countrySelect.addEventListener('change', () => {
            const selectedCountry = countrySelect.value;
            const shippingCost = shippingRates[selectedCountry] || 0;
            shippingCostInput.textContent = `Shipping Cost: ${shippingCost.toFixed(2)}`;
            const newTotal = baseTotal + shippingCost;
            totalPriceElement.textContent = `Total to pay: ${newTotal.toFixed(2)}`;
        });
    }
}

shippingCost();


function alertMessages() {
    const alerts = document.querySelectorAll('#alert_msgs article button');
    if (alerts) {
        for (const alert of alerts) {
            alert.addEventListener('click', function() {
                alert.parentElement.remove();
            })
        }
    }
}

alertMessages();