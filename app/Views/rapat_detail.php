<h2>Rapat Detail</h2>
<p><strong>Nama Rapat:</strong> <?= esc($rapat['nama_rapat']) ?></p>
<p><strong>Ruangan:</strong> <?= esc($rapat['ruangan']) ?></p>

<?php if ($transcription): ?>
    <h3>Transkripsi Tersimpan</h3>
    <p><?= esc($transcription['text']) ?></p>
<?php else: ?>
    <p>No transcription available.</p>
<?php endif; ?>

<?php if (session()->get('user_role') == 2): // Only display if user is notulen 
?>
    <h1>Transkripsi Rapat</h1>
    <button id="start-btn">Mulai Rekam</button>
    <button id="stop-btn">Stop Rekam</button>
    <p id="output"></p>

    <form id="transcription-form" action="<?= base_url('/beranda/saveTranscription/' . $idRapat) ?>" method="post">
        <textarea name="transkripsi" id="transkripsi" style="display: none;"></textarea>
        <button type="submit" id="save-btn" disabled>Simpan Transkripsi</button>
    </form>

    <script>
        const output = document.getElementById('output');
        const startBtn = document.getElementById('start-btn');
        const stopBtn = document.getElementById('stop-btn');
        const transcriptionForm = document.getElementById('transcription-form');
        const transcriptionField = document.getElementById('transkripsi');
        const saveBtn = document.getElementById('save-btn');
        let recognition;
        let finalTranscript = '';

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

                output.innerHTML = `<strong>Final:</strong> ${finalTranscript}<br><strong>Interim:</strong> ${interimTranscript}`;
                transcriptionField.value = finalTranscript;
                saveBtn.disabled = false;
            };

            recognition.onerror = (event) => {
                console.error('Error occurred in recognition:', event.error);
                output.innerText = 'Error: ' + event.error;
            };

            recognition.onend = () => {
                console.log("Speech recognition ended.");
            };

            startBtn.addEventListener('click', () => {
                recognition.start();
                output.innerText = "Mendengarkan...";
            });

            stopBtn.addEventListener('click', () => {
                recognition.stop();
                output.innerText += "\nRecording stopped.";
            });
        } else {
            output.innerText = 'Web Speech API tidak didukung di browser ini.';
        }
    </script>
<?php endif; ?>