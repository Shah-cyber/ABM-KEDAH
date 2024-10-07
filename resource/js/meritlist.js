document.addEventListener('DOMContentLoaded', function() {
    const rowsPerPage = 10;
    const tableBody = document.getElementById('tableBody');
    const pagingContainer = document.getElementById('pagingContainer');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const searchbar = document.getElementById('searchbarMeritlist');

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    function displayPage(page) {
        tableBody.innerHTML = '';
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageItems = merits.slice(start, end);

        pageItems.forEach((merit, index) => {
            const row = `
                <tr>
                    <th scope="row">${start + index + 1}</th>
                    <td>${merit.event_name}</td>
                    <td>${formatDate(merit.event_date)}</td>
                    <td>${merit.person_in_charge_name}</td>
                    <td>${merit.merit_point}</td>
                    <td>
                        <div class="btn-group-vertical" role="group">
                            <button class="btn btn-primary" onclick="window.location.href='admin-achievementmeritUpdate.php?merit_id=${merit.event_id}'">Update</button>
                            <button class="btn btn-danger" id="deleteBtn_${merit.merit_id}" data-merit-id="${merit.merit_id}">Delete</button>
                        </div>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    function setupPagination() {
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('span');
            pageItem.className = 'paging-item';
            pageItem.textContent = i;
            pageItem.setAttribute('data-page', i);
            pageItem.addEventListener('click', function() {
                currentPage = i;
                updatePagination();
                displayPage(currentPage);
            });
            pagingContainer.insertBefore(pageItem, nextPageButton);
        }
    }

    function updatePagination() {
        document.querySelectorAll('.paging-item').forEach(item => {
            item.classList.remove('active');
            if (parseInt(item.getAttribute('data-page')) === currentPage) {
                item.classList.add('active');
            }
        });

        prevPageButton.style.visibility = currentPage === 1 ? 'hidden' : 'visible';
        nextPageButton.style.visibility = currentPage === totalPages ? 'hidden' : 'visible';
    }

    prevPageButton.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
            displayPage(currentPage);
        }
    });

    nextPageButton.addEventListener('click', function() {
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
            displayPage(currentPage);
        }
    });

    searchbar.addEventListener('input', function() {
        const searchTerm = searchbar.value.toLowerCase();
        tableBody.innerHTML = '';
        const filteredMerits = merits.filter(merit => {
            const eventName = merit.event_name ? merit.event_name.toLowerCase() : '';
            const eventDate = merit.event_date ? merit.event_date.toLowerCase() : '';
            const PIC = merit.person_in_charge_name ? merit.person_in_charge_name.toLowerCase() : '';
            const meritPoint = merit.merit_point ? merit.merit_point.toString().toLowerCase() : '';
            const allocationDate = merit.allocation_date ? merit.allocation_date.toLowerCase() : '';
            const PICPhone = merit.person_in_charge_phone_number ? merit.person_in_charge_phone_number.toLowerCase() : '';
            return eventName.includes(searchTerm) || eventDate.includes(searchTerm) || PIC.includes(searchTerm) || meritPoint.includes(searchTerm) || allocationDate.includes(searchTerm) || PICPhone.includes(searchTerm);
        });

        if (filteredMerits.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No records found</td></tr>';
            return;
        }

        filteredMerits.forEach((merit, index) => {
            const row = `
                <tr>
                    <th scope="row">${index + 1}</th>
                    <td>${merit.event_name}</td>
                    <td>${formatDate(merit.event_date)}</td>
                    <td>${merit.person_in_charge_name}</td>
                    <td>${merit.merit_point}</td>
                    <td>
                        <div class="btn-group-vertical" role="group">
                            <button class="btn btn-primary" onclick="window.location.href='admin-achievementmeritUpdate.php?merit_id=${merit.event_id}'">Update</button>
                            <button class="btn btn-danger" id="deleteBtn_${merit.merit_id}" data-merit-id="${merit.merit_id}">Delete</button>
                        </div>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    });

    setupPagination();
    updatePagination();
    displayPage(currentPage);
});
