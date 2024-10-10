function topBar() {
    const mainFilters = document.querySelectorAll('#navTitles');
    mainFilters.forEach(filter => {
        filter.addEventListener('click', async function() {
            const filterValue = filter.textContent.trim(); 
            const field = document.querySelector(`.filter_type form input[name=${filterValue}]`);
            const fields = document.querySelectorAll('.filter_type input#Gender ~ form label input');
            for (const f of fields) {
                f.checked = false;
            }
            field.checked = true;
            fetchFilteredItems();
        })
    });
}

topBar();

// reset filter
function resetFilter() {
    const reset = document.querySelector('.filter_sidebar > button');
    if (reset) {
        reset.addEventListener('click', async function() {
            const fields = document.querySelectorAll('.filter_type form input');
            for (const field of fields) {
                field.checked = false;
            }
            await fetchFilteredItems();
            const filterTypes = document.querySelectorAll('.filter_type > input');
            for (const filterType of filterTypes) {
                filterType.checked = false;
            }
        })
    }
}

resetFilter();

// responsive filter button
function filter_button() {
    const filterButton = document.querySelector('#filter');
    if (filterButton) {
        filterButton.addEventListener('click', function() {
            filterButton.nextElementSibling.classList.toggle('toggle');
        })
    }
}

filter_button();


// Filter
function filterSidebar() {
    const filter_fields = document.querySelectorAll('.filter_type form input');
    filter_fields.forEach(field => {field.addEventListener('change', fetchFilteredItems);});
}

async function fetchFilteredItems() {
    const filter = [];
    const parameters = document.querySelectorAll('.filter_type > label');

    parameters.forEach(parameter => {
        const parameterName = parameter.getAttribute('for');
        const fields = document.querySelectorAll('#' + parameterName + ' ~ form input:checked');
        
        if (fields.length !== 0) {
            const parameterFilter = [].map.call(fields, f => f.parentElement.textContent).join(',')
            filter.push(parameterName + '=' + encodeURIComponent(parameterFilter));
            parameter.textContent = `${parameterName} (${fields.length})`;
        } else {
            parameter.textContent = parameterName;
        }
    })

    await filterItems(filter.join('&'));
}

async function filterItems(filter) {
    const response = await fetch('../api/fetch_filtered_items.php?' + filter);
    const itemsUserInfo = await response.json();
    
    drawItemMiniatures(itemsUserInfo);
}

filterSidebar();

function drawItemMiniatures(items) {
    const section = document.querySelector('.items');
    section.innerHTML = '';

    for (const item of items) {
        const anchor = document.createElement('a');
        anchor.href = "../pages/item.php?item=" + encodeURIComponent(item.id);

        const article = document.createElement('article');

        const imgUsername = document.createElement('div');
        imgUsername.classList.add('img_username');

        const imgUser = document.createElement('img');
        imgUser.src = "/../uploads/small_" + item.user_image_path;
        const user = document.createElement('h3');
        user.textContent = escapeHtml(item.username);
        imgUsername.appendChild(imgUser);
        imgUsername.appendChild(user);

        const imgCrop = document.createElement('div');
        imgCrop.classList.add('crop');

        const img = document.createElement('img');
        img.src = "/../uploads/medium_" + item.image_path;
        imgCrop.appendChild(img);

        const title = document.createElement('h4');
        title.textContent = escapeHtml(item.title);
        const price = document.createElement('h3');

        price.textContent = escapeHtml(item.price + "€");

        const size = document.createElement('p');
        size.textContent = escapeHtml("Size: " + item.size);

        article.appendChild(imgUsername);
        article.appendChild(imgCrop);
        article.appendChild(title);
        article.appendChild(price);
        article.appendChild(size);

        anchor.appendChild(article)

        section.appendChild(anchor);
    }
}

// search
function search() {
    const searchInput = document.querySelector('.search input[type="text"]');

    if (searchInput) {
            searchInput.addEventListener('input', async function() {
            const response = await fetch('../api/api_items.php?search=' + encodeURIComponent(this.value));
            const items = await response.json();
            drawItemMiniatures(items);

        })
    }
}

search();