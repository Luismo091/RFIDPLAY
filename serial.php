<!DOCTYPE html>
<html>
<head>
  <title>Lectura de Puerto Serial</title>
</head>
<body>
  <button id="connectButton">Conectar al Puerto Serial</button>
  <pre id="serialOutput"></pre>

  <script>
    const connectButton = document.getElementById('connectButton');
    const serialOutput = document.getElementById('serialOutput');
    let port;

    async function connectToSerial() {
      try {
        port = await navigator.serial.requestPort();
        await port.open({ baudRate: 9600 }); // Ajusta la velocidad de baudios según corresponda
        
        const textDecoder = new TextDecoderStream();
        const readableStreamClosed = port.readable.pipeTo(textDecoder.writable);
        const reader = textDecoder.readable.getReader();

        while (true) {
          const { value, done } = await reader.read();
          if (done) {
            break;
          }
          serialOutput.textContent += value;
        }
      } catch (error) {
        console.error('Error:', error);
      }
    }

    connectButton.addEventListener('click', connectToSerial);
  </script>
</body>
</html>
