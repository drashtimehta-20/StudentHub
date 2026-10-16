let events = [];
let currentPage = 1;
const eventsPerPage = 4;

const eventsContainer = document.getElementById("eventsContainer");
const searchInput =  document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");
const sortOption = document.getElementById("sortOption");
const pagination = document.getElementById("pagination");

fetch("../JSON/events.json")
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        events = data;
        displayEvents();
    })

    .catch(function(error) {
        console.log("Error loading data:", error);
    });


function displayEvents() {
    let filteredEvents = [...events];
    const searchText =
        searchInput.value.toLowerCase();
    filteredEvents = filteredEvents.filter(function(event) {
        return event.title
            .toLowerCase()
            .includes(searchText);
    });

    const selectedCategory =
        categoryFilter.value;

    if (selectedCategory !== "all") {
        filteredEvents = filteredEvents.filter(function(event) {
            return event.category === selectedCategory;
        });
    }

    if (sortOption.value === "title") {
        filteredEvents.sort(function(a, b) {
            return a.title.localeCompare(b.title);
        });

    }


    if (sortOption.value === "date") {
        filteredEvents.sort(function(a, b) {
            return new Date(a.date) - new Date(b.date);
        });
    }

    const startIndex = (currentPage - 1) * eventsPerPage;

    const endIndex = startIndex + eventsPerPage;

    const pageEvents = filteredEvents.slice(startIndex, endIndex);

    eventsContainer.innerHTML = "";

    pageEvents.forEach(function(event) {

        const eventCard = document.createElement("div");

        eventCard.className = "event-card";
        eventCard.innerHTML = ` <h2>${event.title}</h2>
            <p>
                <strong>Category:</strong>
                ${event.category}
            </p>

            <p>
                <strong>Date:</strong>
                ${event.date}
            </p>

            <p>
                <strong>Venue:</strong>
                ${event.venue}
            </p>

            <p>
                ${event.description}
            </p>
        `;
        eventsContainer.appendChild(eventCard);
    });

    createPagination(filteredEvents.length);

}

function createPagination(totalEvents) {
    pagination.innerHTML = "";
    const totalPages =
        Math.ceil(totalEvents / eventsPerPage);

    for (let i = 1; i <= totalPages; i++) {
        const button =  document.createElement("button");
        button.textContent = i;
        button.addEventListener("click", function() {
            currentPage = i;
            displayEvents();
        });
        pagination.appendChild(button);
    }
}

searchInput.addEventListener("input", function() {
    currentPage = 1;
    displayEvents();
});

categoryFilter.addEventListener("change", function() {
    currentPage = 1;
    displayEvents();
});

sortOption.addEventListener("change", function() {
    currentPage = 1;
    displayEvents();
});