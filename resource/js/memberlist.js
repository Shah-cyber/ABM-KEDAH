document.addEventListener('DOMContentLoaded', function() {
    const rowsPerPage = 10;
    const tableBody = document.getElementById('tableBody');
    const pagingContainer = document.getElementById('pagingContainer');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const searchbar = document.getElementById('searchbar');

    function displayPage(page) {
        tableBody.innerHTML = '';
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageItems = members.slice(start, end);

        pageItems.forEach((member, index) => {
            const row = `
                <tr>
                    <th scope="row">${start + index + 1}</th>
                    <td>${member.username}</td>
                    <td>${member.email}</td>
                    <td><a href="admin-memberDisplayAchievement.php?ic_number=${member.ic_number}">Report</a></td>
                    <td>Member</td>
                    <td>
                        <div class="btn-group-vertical" role="group">
                            <button class="btn btn-primary" onclick="window.location.href='admin-memberDisplayInfo.php?ic_number=${member.ic_number}'">View</button>
                            <button class="btn btn-primary" onclick="window.location.href='admin-memberUpdateInfo.php?ic_number=${member.ic_number}'">Update</button>
                            <button class="btn btn-danger" id="deleteBtn_${member.ic_number}" data-ic-number="${member.ic_number}">Delete</button>
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
        const filteredMembers = members.filter(member => {
            return member.username.toLowerCase().includes(searchTerm) || member.email.toLowerCase().includes(searchTerm);
        });

        if (filteredMembers.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center no-record-message">No record with the prompt information could be found.</td></tr>';
        } else {
            filteredMembers.forEach((member, index) => {
                const row = `
                    <tr>
                        <th scope="row">${index + 1}</th>
                        <td>${member.username}</td>
                        <td>${member.email}</td>
                        <td><a href="admin-memberDisplayAchievement.php?ic_number=${member.ic_number}">Report</a></td>
                        <td>Member</td>
                        <td>
                            <div class="btn-group-vertical" role="group">
                                <button class="btn btn-primary" onclick="window.location.href='admin-memberDisplayInfo.php?ic_number=${member.ic_number}'">View</button>
                                <button class="btn btn-primary" onclick="window.location.href='admin-memberUpdateInfo.php?ic_number=${member.ic_number}'">Update</button>
                                <button class="btn btn-danger" id="deleteBtn_${member.ic_number}" data-ic-number="${member.ic_number}">Delete</button>
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
 