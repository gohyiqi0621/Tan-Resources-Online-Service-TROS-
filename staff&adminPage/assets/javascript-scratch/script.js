document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addTechnicianForm');
    const tableBody = document.getElementById('technicianTableBody');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        // Simulate adding a technician
        const name = form.elements['name'].value;
        const specialty = form.elements['specialty'].value;
        const location = form.elements['location'].value;
        const rating = form.elements['rating'].value;
        const image = form.elements['image'].files[0];

        // Create a new row in the table
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>New ID</td>
            <td>${name}</td>
            <td>${specialty}</td>
            <td>${location}</td>
            <td>⭐ ${rating}</td>
            <td><img src='uploads/${image.name}' width='80'></td>
            <td>
                <a href='#' class='btn btn-warning btn-sm'>Edit</a>
                <a href='#' class='btn btn-danger btn-sm' onclick='return confirm("Are you sure?")'>Delete</a>
            </td>
        `;
        tableBody.appendChild(newRow);

        // Clear the form
        form.reset();
    });
});