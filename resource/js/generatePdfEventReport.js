document.addEventListener('DOMContentLoaded', function() {
    const generatePdfButton = document.getElementById('reportgeneratepdf');

    generatePdfButton.addEventListener('click', function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Adding the logo
        const logo = new Image();
        logo.src = '../img/logoabm.png';  // Ensure this path is correct
        logo.onload = function() {
            doc.addImage(logo, 'PNG', 175, 10, 30, 30); // Adjust the position and size accordingly

           // Setting NGO name
            doc.setFontSize(16);
            doc.text('Angkatan Belia Malaysia Future Leaders School', 14, 22); // Adjusted Y position
            doc.text('(MFLS) Kedah', 14, 30); // Adjusted Y position below the first line

            // Adding title
            doc.setFontSize(14);
            doc.text('Event Report', 14, 40);

            // Underline
            let textWidth = doc.getStringUnitWidth('Event Report') * doc.internal.getFontSize() / doc.internal.scaleFactor;
            doc.line(14, 42, 14 + textWidth, 42); // Adjust Y position as needed


            // Adding event details
            doc.setFontSize(12);
            doc.text('Activity Name: ' + document.getElementById('namaAktiviti').textContent, 14, 50);
            doc.text('Session: ' + document.querySelector('#achievement-table tbody tr:nth-child(1) td:nth-child(4)').textContent, 14, 56);
            doc.text('Activity Date: ' + document.getElementById('tarikhAktiviti').textContent, 14, 62);
            doc.text('Member Involved: ' + attendees.length, 14, 68);

            // Adding a table
            const tableColumn = ["#", "Name", "Phone Number", "Member Status"];
            const tableRows = [];

            attendees.forEach((attendee, index) => {
                const attendeeData = [
                    index + 1,
                    attendee.Name,
                    attendee.phone_number,
                    attendee.Member_Status
                ];
                tableRows.push(attendeeData);
            });

            doc.autoTable({
                startY: 74,
                head: [tableColumn],
                body: tableRows,
                styles: { fontSize: 10 }
            });

            // Save the PDF
            doc.save('Event_Report.pdf');
        };
    });
});
