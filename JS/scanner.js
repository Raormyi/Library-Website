document.addEventListener("DOMContentLoaded", function () {
    const scannerContainer = document.getElementById('scanner-container');
    let activeInput = null; // Keeps track of which input field to update

    // Get buttons and inputs
    const scanIsbnButton = document.getElementById('scan-isbn');
    const scanBarcodeButton = document.getElementById('scan-barcode');
    const stopScannerButton = document.getElementById('stop-scanner'); // New stop button

    // Function to start the scanner
    function startScanner(inputFieldId) {
        activeInput = document.getElementById(inputFieldId); // Set the active input field
        scannerContainer.style.display = 'block'; // Show the scanner container

        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: scannerContainer,
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "upc_reader"],
            },
        }, (err) => {
            if (err) {
                console.error("Error initializing Quagga:", err);
                return;
            }
            Quagga.start();
        });

        // Handle barcode detection
        Quagga.onDetected((data) => {
            const scannedCode = data.codeResult.code;
            console.log("Barcode detected:", scannedCode);

            // Populate the active input field with the scanned code
            if (activeInput) {
                activeInput.value = scannedCode;
            }

            // Stop the scanner and hide the container
            Quagga.stop();
            scannerContainer.style.display = 'none';
            activeInput = null; // Reset the active input field
        });
    }

    function stopScanner() {
        console.log("Stopping scanner...");
        Quagga.stop();
        scannerContainer.style.display = 'none';
        activeInput = null;
    }

    // Add event listeners to the scan buttons
    if (scanIsbnButton){
    document.getElementById('scan-isbn').addEventListener('click', (event) => {
        event.preventDefault(); // Prevent form submission
        startScanner('isbn'); // Start scanner for Input 1
    });
    }
    document.getElementById('scan-barcode').addEventListener('click', (event) => {
        event.preventDefault(); // Prevent form submission
        startScanner('barcode'); // Start scanner for Input 2
    });

    if (stopScannerButton) {
        stopScannerButton.addEventListener('click', (eventsc) => {
            event.preventDefault();
            stopScanner();
        });
    }

});

