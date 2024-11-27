import './bootstrap';
import './search';
import 'flowbite';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();


document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const sortSelect = document.getElementById('sortSelect');
    const productsContainer = document.getElementById('productsContainer');

    // Function to perform AJAX request
    const fetchFilteredProducts = () => {
        const formData = new FormData(filterForm);
        const queryString = new URLSearchParams(formData).toString();

        fetch(`${filterForm.action}?${queryString}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            productsContainer.innerHTML = html;
        })
        .catch(error => console.error('Error fetching products:', error));
    };

    // Event listeners for filters
    searchInput.addEventListener('input', fetchFilteredProducts);
    categorySelect.addEventListener('change', fetchFilteredProducts);
    sortSelect.addEventListener('change', fetchFilteredProducts);
});
document.addEventListener('DOMContentLoaded', function () {
    const loadMoreButton = document.getElementById('loadMoreButton');
    const productsContainer = document.getElementById('productsContainer');

    if (loadMoreButton) {
        loadMoreButton.addEventListener('click', function () {
            const formData = new FormData(document.getElementById('filterForm'));
            const queryString = new URLSearchParams(formData).toString();
            const currentCount = productsContainer.querySelectorAll('.product-card').length;

            fetch(`${filterForm.action}?${queryString}&offset=${currentCount}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                productsContainer.innerHTML += html;

                // Hide button if no more products
                if (html.trim() === '') {
                    loadMoreButton.style.display = 'none';
                }
            })
            .catch(error => console.error('Error fetching more products:', error));
        });
    }
});
