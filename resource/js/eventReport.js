document.addEventListener('DOMContentLoaded', function() {
    const rowsPerPage = 10;
    const tableBody = document.getElementById('tableBody');
    const pagingContainer = document.getElementById('pagingContainer');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const searchbar = document.getElementById('searchbarMemberAttendees');
 

    function displayPage(page) {
        tableBody.innerHTML = '';
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageItems = attendees.slice(start, end);

        pageItems.forEach((attendee, index) => {
            const row = `
                <tr>
                    <th scope="row">${start + index + 1}</th>
                    <td>${attendee.Name}</td>
                    <td>${attendee.phone_number}</td>
                    <td>${attendee.Member_Status}</td>
                    <td><input type="checkbox" class="checkbox" data-user-id="${attendee.user_id}" data-event-id="${eventId}"></td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);

            // Check if attendee is already allocated merits for the event
            CheckIfAllocatedMember(attendee.user_id, eventId);
        });
    }

    function CheckIfAllocatedMember(userId, eventId) {
        $.ajax({
            type: 'POST',
            url: '../sql/admin-functions.php',
            data: { action: 'checkAllocation', user_id: userId, event_id: eventId },
            dataType: 'json',
            success: function(response) {
                if (response.allocated) {
                    $(`.checkbox[data-user-id="${userId}"]`).prop('checked', true).prop('disabled', true);
                    console.log(`Merit already allocated for user ${userId} in event ${response.event_name}`);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText); // Log raw response text
                Swal.fire('Error', 'An error occurred while checking allocation', 'error');
            }
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

    prevPageButton.addEventListener('click', function ()  {
        if (currentPage > 1) {
            currentPage--;
             updatePagination();
            displayPage(currentPage);           
        }
    });

    nextPageButton.addEventListener('click', function ()  {
        if (currentPage < totalPages) {
            currentPage++;            
            updatePagination();
            displayPage(currentPage);
        }
    });

    searchbar.addEventListener('input', function() {
        const searchTerm = searchbar.value.toLowerCase();
        tableBody.innerHTML = '';
        const filteredAttendees = attendees.filter(attendee => {
            // Check for properties before calling toLowerCase()
            const attendeeName = attendee.Name ? attendee.Name.toLowerCase() : '';
            const attendeePhoneNumber = attendee.phone_number ? attendee.phone_number.toLowerCase() : '';
            const attendeeStatus = attendee.Member_Status ? attendee.Member_Status.toLowerCase() : '';
            return attendeeName.includes(searchTerm) || attendeePhoneNumber.includes(searchTerm) || attendeeStatus.includes(searchTerm);
        });
    
        if (filteredAttendees.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No records found</td></tr>';
            return;
        }
    
        filteredAttendees.forEach((attendee, index) => {
            const row = `
                <tr>
                    <th scope="row">${index + 1}</th>
                    <td>${attendee.Name}</td>
                    <td>${attendee.phone_number}</td>
                    <td>${attendee.Member_Status}</td>
                    <td><input type="checkbox" class="checkbox" data-user-id="${attendee.user_id}" data-event-id="${eventId}"></td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
    
            // Check if attendee is already allocated merits for the event
            CheckIfAllocatedMember(attendee.user_id, eventId);
        });
    });
    
    displayPage(currentPage);
    setupPagination();
    updatePagination();

    $('#allocatemerit').on('click', function() {
        const selectedUsers = [];
        $('.checkbox:checked').each(function() {
            const userId = $(this).data('user-id');
            const eventId = $(this).data('event-id');
            selectedUsers.push({ user_id: userId, event_id: eventId });
        });
    
        if (selectedUsers.length > 0) {
            $.ajax({
                type: 'POST',
                url: '../sql/admin-functions.php',
                data: { action: 'allocateMerit', attendees: selectedUsers },
                dataType: 'json',
                success: function(responses) {
                    console.log('Raw responses:', responses); // Log raw responses for debugging
    
                    let errorOccurred = false;
                    responses.forEach((resp) => {
                        if (resp.message.includes('Error')) {
                            Swal.fire('Error', `Merit already allocated for users in event ${resp.event_name}`, 'error');
                            errorOccurred = true;
                        } else {
                            Swal.fire('Success', resp.message, 'success');
                            $(`.checkbox[data-user-id="${resp.user_id}"]`).prop('checked', true).prop('disabled', true);
                        }
                    });
                    if (!errorOccurred) {
                        Swal.fire('Success', 'All merit points allocated successfully', 'success');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText); // Log raw response text
                    Swal.fire('Error', 'An error occurred while processing the request', 'error');
                }
            });
        } else {
            Swal.fire('Warning', 'No users selected', 'warning');
        }
    });
    
    
});