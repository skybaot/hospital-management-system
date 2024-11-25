
// Function to fetch data from the backend and display it
async function fetchData() {
    try {
        // Make a GET request to the backend route
        const response = await fetch('/get-data');
        
        // Check if the request was successful
        if (!response.ok) {
            throw new Error(`Error fetching data: ${response.statusText}`);
        }
        
        // Parse the JSON response
        const data = await response.json();
        
        // Get the container where the data will be displayed
        const dataDisplay = document.getElementById('data-display');
        
        // Clear any existing content
        dataDisplay.innerHTML = '';

        // Check if there is data to display
        if (data.length === 0) {
            dataDisplay.innerHTML = '<p>No records found.</p>';
            return;
        }

        // Create a table to display the data
        const table = document.createElement('table');
        table.setAttribute('border', '1');
        table.style.width = '100%';

        // Add table headers
        const headerRow = document.createElement('tr');
        const headers = ['ID', 'Name'];
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            headerRow.appendChild(th);
        });
        table.appendChild(headerRow);

        // Populate the table with data
        data.forEach(row => {
            const tr = document.createElement('tr');
            row.forEach(cellData => {
                const td = document.createElement('td');
                td.textContent = cellData;
                tr.appendChild(td);
            });
            table.appendChild(tr);
        });

        // Append the table to the data display section
        dataDisplay.appendChild(table);

    } catch (error) {
        // Handle any errors
        console.error(error);
        document.getElementById('data-display').innerHTML = `<p>Error loading data: ${error.message}</p>`;
    }
}

// Add event listener for form submission
document.querySelector('form').addEventListener('submit', async function (event) {
    event.preventDefault(); // Prevent the default form submission

    // Collect form data
    const formData = new FormData(this);

    try {
        // Make a POST request to the backend
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });

        // Check if the request was successful
        if (!response.ok) {
            throw new Error(`Error submitting form: ${response.statusText}`);
        }

        // Handle the successful response
        const result = await response.text();
        alert(result); // Notify the user of success

        // Optionally, reset the form
        this.reset();
    } catch (error) {
        // Handle any errors
        console.error(error);
        alert(`Error: ${error.message}`);
    }
});
