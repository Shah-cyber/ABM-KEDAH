document.addEventListener('DOMContentLoaded', function() {
    const searchbar = document.getElementById('searchbarPaymentList');
    const tableBody = document.getElementById('tableBody');
    const paymentFilter = document.getElementById('paymentFilter');
    const totalPaymentCounter = document.getElementById('totalPaymentCounter');
    const totalTitle = document.getElementById('totalTitle');

    if (!searchbar || !tableBody || !paymentFilter || !totalPaymentCounter || !totalTitle) {
        console.error('One or more required elements not found.');
        return;
    }

    function displayPayments(payments) {
        tableBody.innerHTML = '';
        if (payments.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6">No payments found.</td></tr>';
            totalPaymentCounter.textContent = 0;
            return;
        }
        payments.forEach((payment, index) => {
            const linkHTML = payment.payment_status === 'pending'
                ? `<a class="paymentdetailsbtn py-2" href="membershippayment.php?payment_id=${payment.payment_id}">Pay</a>`
                : `<a class="paymentdetailsbtn py-2" href="member-paymentdetails.php?payment_id=${payment.payment_id}">Details</a>`;
            const paymentHTML = `
                <tr>
                    <th scope="row">${index + 1}</th>
                    <td>${payment.payment_name}</td>
                    <td>RM${payment.payment_fee}</td>
                    <td>${payment.payment_date}</td>
                    <td>${payment.payment_status}</td>
                    <td class='text-center'>${linkHTML}</td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', paymentHTML);
        });
        totalPaymentCounter.textContent = payments.length;
    }

    function filterPayments() {
        const filter = paymentFilter.value;
        let payments;
        if (filter === 'History Payment') {
            payments = completedPayments;
            totalTitle.textContent = 'History Payment:';
        } else {
            payments = pendingPayments;
            totalTitle.textContent = 'Pending Payment:';
        }
        displayPayments(payments);
    }

    searchbar.addEventListener('input', function() {
        const searchTerm = searchbar.value.toLowerCase();
        const filter = paymentFilter.value;
        const payments = filter === 'History Payment' ? completedPayments : pendingPayments;
        const filteredPayments = payments.filter(payment => {
            const paymentName = payment.payment_name ? payment.payment_name.toLowerCase() : '';
            return paymentName.includes(searchTerm);
        });
        displayPayments(filteredPayments);
    });

    paymentFilter.addEventListener('change', filterPayments);

    // Initial display of pending payments
    filterPayments();
});
