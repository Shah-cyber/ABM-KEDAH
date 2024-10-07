document.addEventListener('DOMContentLoaded', function() {
    const rowsPerPage = 10;
    const tableBody = document.getElementById('tableBody');
    const pagingContainer = document.getElementById('pagingContainer');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const searchbar = document.getElementById('searchbar');

    function displayPage(page) {
        tableBody.innerHTML = '';
        const start = (page -1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageItems = users.slice(start, end);

       
        pageItems.forEach((user, index) => {

            const row = `
                <tr>
                    <th scope="row">${start + index + 1}</th>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td>${user.status}</td>
                    <td>
                        <div class="btn-group-vertical" role="group">
                            <button class="btn btn-primary" onclick="window.location.href='admin-usersettingsUpdateInfo.php?ic_number=${user.ic_number}'">Update</button>
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
        const filteredUsers = users.filter(user => {
            return user.username.toLowerCase().includes(searchTerm) || user.email.toLowerCase().includes(searchTerm) || user.role.toLowerCase().includes(searchTerm) || user.status.toLowerCase().includes(searchTerm);
        });

        if (filteredUsers.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center no-record-message">No record with the prompt information could be found.</td></tr>';
        } else {
            filteredUsers.forEach((user, index) => {
                const row = `
                    <tr>
                    <th scope="row">${index + 1}</th>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td>${user.status}</td>
                    <td>
                        <div class="btn-group-vertical" role="group">
                            <button class="btn btn-primary" onclick="window.location.href='admin-usersettingsUpdateInfo.php?ic_number=${user.ic_number}'">Update</button>
                        </div>
                    </td>
                </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        }
    });

    displayPage(currentPage);
    setupPagination();
    updatePagination();

});
