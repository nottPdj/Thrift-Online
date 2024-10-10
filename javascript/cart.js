const entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "'": '&#39;',
    "/": '&#x2F;'
  };
function escapeHtml(string) {
return String(string).replace(/[&<>"'\/]/g, function (s) {
    return entityMap[s];
});
}

function openCart() {
    const openCartButton = document.querySelector('.session_options .cart_button');
    if (openCartButton) {
        openCartButton.addEventListener('click', showShoppingCart);
    }
}

openCart();

function closeCart() {
    const closeCartButton = document.querySelector('#shopping_cart header button');
    if (closeCartButton) {
        closeCartButton.addEventListener('click', hideShoppingCart);
    }
}
closeCart();

function showShoppingCart() {
    const cart = document.querySelector('#shopping_cart');
    cart.classList.remove('hide');
}

function hideShoppingCart() {
    const cart = document.querySelector('#shopping_cart');
    cart.classList.add('hide');
}

function addToCartButton() {
    const button = document.querySelector('.addToCart');
    if (button) {
        button.addEventListener('click', async function() {
            button.classList.toggle('inCart');
            if (button.classList.contains('inCart')) {
                const item = await addItemToCart(button.getAttribute("data-id"));
                button.textContent = "Remove from cart";
                drawCartItem(item);
            } else {
                button.textContent = "Add to cart";
                const itemsInCart = document.querySelectorAll('#cart_items #cart_remove');
                if (itemsInCart) {
                    for (const item of itemsInCart) {
                        if (item.getAttribute('data-id') === button.getAttribute('data-id')) {
                            removeItemFromCartButton.call(item);
                        }
                    }
                }
            }
        })
    }
}

addToCartButton();

// cross removes item from cart
function removeInsideCart() {
    const removeFromCart = document.querySelectorAll('#cart_remove');
    if (removeFromCart) {
        for (const button of removeFromCart) {
            button.addEventListener('click', removeItemFromCartButton);
        }
    }
}

removeInsideCart();

async function removeItemFromCartButton() {
    await removeItemFromCart(this.getAttribute('data-id'));
    const total = document.querySelector('#shopping_cart #total h3:last-child');
    total.textContent = (parseFloat(total.textContent) - parseFloat(this.previousElementSibling.lastElementChild.textContent)).toFixed(2);
    this.parentElement.remove();

    // if is in the removed item's page
    const currentItemButton = document.querySelector('.addToCart');
        if (currentItemButton) {
            if (currentItemButton.getAttribute('data-id') === this.getAttribute('data-id')) {
                currentItemButton.classList.remove('inCart');
                currentItemButton.textContent = "Add to cart";
            }
        }

    showShoppingCart();
}

async function addItemToCart(itemId) {
    const response = await fetch("../api/api_cart.php?add=" + encodeURIComponent(itemId));
    const item = await response.json();

    return item;
}

async function removeItemFromCart(itemId) {
    const response = await fetch('../api/api_cart.php', {
        method: "POST",
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: "remove=" + encodeURIComponent(itemId)
    });
}

function drawCartItem(item) {
    const cart = document.querySelector('#cart_items');

    const itemA = document.createElement('div');
    itemA.classList.add('horizontal_item');

    const img = document.createElement('img');
    img.src = '../uploads/medium_' + item.image_path;

    const itemDetails = document.createElement('div');
    itemDetails.id = "item_details";
    
    const title = document.createElement('h4');
    title.textContent = escapeHtml(item.title);
    const size = document.createElement('p');
    size.textContent = "Size: " + item.size;
    const price = document.createElement('h3');
    price.textContent = escapeHtml(item.price);

    itemDetails.appendChild(title);
    itemDetails.appendChild(size);
    itemDetails.appendChild(price);

    const remove = document.createElement('button');
    remove.id = "cart_remove";
    remove.setAttribute('type', 'submit');
    remove.setAttribute('data-id', item.id);
    const i = document.createElement('i');
    i.classList.add('fa-solid', 'fa-xmark');
    remove.appendChild(i);

    itemA.appendChild(img);
    itemA.appendChild(itemDetails);
    itemA.appendChild(remove);

    cart.appendChild(itemA);

    const total = document.querySelector('#shopping_cart #total h3:last-child');
    total.textContent = (parseFloat(total.textContent) + parseFloat(item.price)).toFixed(2);

    showShoppingCart();

    remove.addEventListener('click', removeItemFromCartButton);
}