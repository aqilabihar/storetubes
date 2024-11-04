<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transkripsi Rapat</title>
</head>

<body>
    <h2>Rapat Detail</h2>
    <p><strong>Nama Rapat:</strong> <?= esc($rapat['nama_rapat']) ?></p>
    <p><strong>Ruangan:</strong> <?= esc($rapat['ruangan']) ?></p>

    <h3>Transkripsi Rapat</h3>
    <button id="start-btn">Mulai Rekam</button>
    <button id="stop-btn">Stop Rekam</button>
    <p id="output"></p>

    <form id="transcription-form" action="<?= base_url('/beranda/saveTranscription/' . $rapat['id']) ?>" method="post">
        <textarea name="transkripsi" id="transkripsi" style="width: 100%; height: 150px;" placeholder="Hasil transkripsi akan muncul di sini..."></textarea>
        <button type="submit" id="save-btn" disabled>Simpan Transkripsi</button>
    </form>

    <script>
        const output = document.getElementById('output');
        const startBtn = document.getElementById('start-btn');
        const stopBtn = document.getElementById('stop-btn');
        const transcriptionField = document.getElementById('transkripsi');
        const saveBtn = document.getElementById('save-btn');
        let recognition;
        let finalTranscript = '';

        // Check for Web Speech API support
        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
            recognition.lang = 'id-ID';
            recognition.continuous = true;
            recognition.interimResults = true;

            recognition.onresult = (event) => {
                let interimTranscript = '';

                for (let i = event.resultIndex; i < event.results.length; i++) {
                    let transcript = event.results[i][0].transcript;
                    if (event.results[i].isFinal) {
                        finalTranscript += transcript + ' ';
                    } else {
                        interimTranscript += transcript;
                    }
                }

                // Update the output display and the textarea
                output.innerHTML = `<strong>Final:</strong> ${finalTranscript}<br><strong>Interim:</strong> ${interimTranscript}`;
                transcriptionField.value = finalTranscript; // Update the textarea with final transcript
                saveBtn.disabled = false; // Enable the save button
            };

            recognition.onerror = (event) => {
                console.error('Error occurred in recognition:', event.error);
                output.innerText = 'Error: ' + event.error;
            };

            recognition.onend = () => {
                console.log("Speech recognition ended.");
            };

            startBtn.addEventListener('click', () => {
                finalTranscript = ''; // Reset the transcript
                output.innerText = "Mendengarkan...";
                recognition.start(); // Start speech recognition
            });

            stopBtn.addEventListener('click', () => {
                recognition.stop(); // Stop speech recognition
                output.innerText += "\nRecording stopped.";
            });
        } else {
            output.innerText = 'Web Speech API tidak didukung di browser ini.';
        }
    </script>
</body>

</html>