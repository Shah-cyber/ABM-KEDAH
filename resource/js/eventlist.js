document.addEventListener('DOMContentLoaded', function() {
    const rowsPerPage = 10;
    const tableBody = document.getElementById('tableBody');
    const pagingContainer = document.getElementById('pagingContainer');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const searchbar = document.getElementById('searchbarActivitylist');

    
    

    function displayPage(page) {
        tableBody.innerHTML = '';
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageItems = events.slice(start, end);

        pageItems.forEach((event, index) => {
            const row = `
                <tr>
                    <th scope="row">${start + index + 1}</th>
                    <td>${event.event_name}</td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#imageModal" data-image="${event.banner}"><img src="${event.banner}" alt="Banner" style="width: 35%; height: auto;"></a></td>
                    <td>${event.event_date}</td>
                    <td><a href="admin-activityReport.php?event_id=${event.event_id}">Report</a></td>
                    <td>${event.event_status}</td>
                    <td>
                        <div class="btn-group-vertical" role="group">
                            <button class="btn btn-primary" onclick="window.location.href='admin-activityUpdate.php?event_id=${event.event_id}'">Update</button>
                            <button class="btn btn-danger" id="deleteBtn_${event.event_id}" data-event-id="${event.event_id}">Delete</button>

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
        const filteredEvents = events.filter(event => {
            // Check for properties before calling toLowerCase()
            const eventName = event.event_name ? event.event_name.toLowerCase() : '';
            const eventStatus = event.event_status ? event.event_status.toLowerCase() : '';
            const eventYear = event.event_date ? event.event_date.toLowerCase() : '';
            return eventName.includes(searchTerm) || eventStatus.includes(searchTerm) || eventYear.includes(searchTerm);
        });

        if (filteredEvents.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No records found</td></tr>';
            return;
        }

        filteredEvents.forEach((event, index) => {
            const row = `
                <tr>
                    <th scope="row">${index + 1}</th>
                    <td>${event.event_name}</td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#imageModal" data-image="${event.banner}"><img src="${event.banner}" alt="Banner" style="width: 35%; height: auto;"></a></td>
                    <td>${event.event_date}</td>
                    <td><a href="admin-activityReport.php?event_id=${event.event_id}">Report</a></td>
                    <td>${event.event_status}</td>
                    <td>
                        <div class="btn-group-vertical" role="group">   
                            <button class="btn btn-primary" onclick="window.location.href='admin-activityUpdateInfo.php?event_id=${event.event_id}'">Update</button>
                            <button class="btn btn-danger" id="deleteBtn_${event.event_id}" data-event-id="${event.event_id}">Delete</button>

                        </div>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    });

    // Event listener for modal
    const imageModal = document.getElementById('imageModal');
    imageModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const imageSrc = button.getAttribute('data-image');
        const modalImage = imageModal.querySelector('.modal-body img');
        modalImage.src = imageSrc;
    });

    setupPagination();
    updatePagination();
    displayPage(currentPage);
});
