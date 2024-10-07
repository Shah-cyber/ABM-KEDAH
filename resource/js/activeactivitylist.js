document.addEventListener('DOMContentLoaded', function() {
    const searchbar = document.getElementById('searchbarActivitylist');
    const activityWrapper = document.getElementById('activeActivityWrapper');
    const eventFilter = document.getElementById('eventFilter');
    const totalActivityCounter = document.getElementById('totalActivityCounter');
    
    if (!searchbar || !activityWrapper || !eventFilter || !totalActivityCounter) {
        console.error('One or more required elements not found.');
        return;
    }

    function displayActivities(activities) {
        activityWrapper.innerHTML = '';
        if (activities.length === 0) {
            activityWrapper.innerHTML = '<div class="col"><p>No activities found.</p></div>';
            return;
        }
        activities.forEach(event => {
            const isJoined = joinedEvents.some(joinedEvent => joinedEvent.event_id === event.event_id);
            const activityHTML = `
                <div class="activeactivity">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="banner-container">
                                <div class="rounded-square">
                                    <img class="img-fluid" src="${event.banner}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg">
                            <div class="activeactivity-information">
                                <h4>${event.event_name}</h4>
                                <p><i>${event.event_description}</i></p>
                            </div>
                        </div>
                    </div>
                    <div class="row activeactivity-redirect-wrapper">
                        <div class="col">
                            <a href="member-activityinfo.php?event_id=${event.event_id}">
                                ${isJoined ? 'Joined' : 'Register'}
                            </a>
                        </div>
                    </div>
                </div>
            `;
            activityWrapper.insertAdjacentHTML('beforeend', activityHTML);
        });
        totalActivityCounter.textContent = activities.length;
    }
    
    
    

    function filterActivities() {
        const filter = eventFilter.value;
        const activities = filter === 'Joined Event' ? joinedEvents : activeEvents;
        displayActivities(activities);
    }

    searchbar.addEventListener('input', function() {
        const searchTerm = searchbar.value.toLowerCase();
        const filter = eventFilter.value;
        const activities = filter === 'Joined Event' ? joinedEvents : activeEvents;
        const filteredActivities = activities.filter(event => {
            const eventName = event.event_name ? event.event_name.toLowerCase() : '';
            const eventDescription = event.event_description ? event.event_description.toLowerCase() : '';
            return eventName.includes(searchTerm) || eventDescription.includes(searchTerm);
        });
        displayActivities(filteredActivities);
    });

    eventFilter.addEventListener('change', filterActivities);

    // Initial display of ongoing events
    displayActivities(activeEvents);
});
