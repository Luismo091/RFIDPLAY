#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <WiFiManager.h>

// Configuración de conexión WiFi
const char* nodeMCUId = "RCAIR2023"; 
const char* defaultServerUrl = "http://192.168.211.41/rfidplay/main/demo55/dist/account/search.php";
char serverUrl[100] = "";

// Configuración de pines para el lector RFID
#define SS_PIN D2
#define RST_PIN D1

// Pines para los LEDs
#define RED_LED D5
#define YELLOW_LED D6
#define GREEN_LED D7
const int buzzerPin = D8;

// Crear instancia del lector RFID
MFRC522 mfrc522(SS_PIN, RST_PIN);

void setup() {
    WiFi.mode(WIFI_STA);
    Serial.begin(9600);
    WiFiManager wm;
    bool res;
    pinMode(buzzerPin, OUTPUT); 
    
    // Configurar los pines de los LEDs como salidas
    pinMode(RED_LED, OUTPUT);
    pinMode(YELLOW_LED, OUTPUT);
    pinMode(GREEN_LED, OUTPUT);

    res = wm.autoConnect("R-AIR", "RFIDPLAY");

    if (!res) {
        Serial.println("Conexion Fallida");
        digitalWrite(YELLOW_LED, HIGH); // Encender LED amarillo al intentar conectarse
    } else {
        Serial.println("Estás Conectado");
        digitalWrite(GREEN_LED, HIGH); // Encender LED verde para indicar conexión exitosa

        SPI.begin();
        mfrc522.PCD_Init();
        delay(4);

        Serial.println("Listo, conexión exitosa!");
        strcpy(serverUrl, defaultServerUrl); // Inicializar la dirección del servidor con la dirección predeterminada
    }
}

void loop() {
    if (WiFi.status() == WL_CONNECTED) {
        digitalWrite(YELLOW_LED, LOW); // Apagar el LED amarillo cuando esté conectado
        if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
            String uid = "";
            for (byte i = 0; i < mfrc522.uid.size; i++) {
                uid += String(mfrc522.uid.uidByte[i], HEX);
            }

            tone(buzzerPin, 1000); // El primer argumento es el pin del buzzer, el segundo es la frecuencia en Hz
            delay(500); // Mantener el sonido durante 0.5 segundos
            noTone(buzzerPin); // Detener el sonido del buzzer

            Serial.print("UID:");
            Serial.println(uid);

            String url = String(serverUrl) + "?serial=" + uid + "&nodeMCUId=" + nodeMCUId;

            HTTPClient http;
            WiFiClient client;
            http.begin(client, url);
            int httpCode = http.GET();

            if (httpCode > 0) {
                Serial.println("Server response:");
                String payload = http.getString();
                Serial.println(payload);
                // Hacer parpadear el LED verde al escanear una tarjeta
                for (int i = 0; i < 3; i++) {
                    digitalWrite(GREEN_LED, HIGH);
                    delay(500);
                    digitalWrite(GREEN_LED, LOW);
                    delay(500);
                }
            } else {
                Serial.println("Error al enviar la solicitud");
                Serial.println(http.errorToString(httpCode).c_str());
                digitalWrite(RED_LED, HIGH); // Encender LED rojo en caso de error de solicitud
                // Hacer parpadear el LED rojo
                for (int i = 0; i < 3; i++) {
                    digitalWrite(RED_LED, HIGH);
                    delay(100); // Reduce el retardo a 100 ms
                    digitalWrite(RED_LED, LOW);
                    delay(100); // Reduce el retardo a 100 ms
                }
            }

            http.end();
            delay(2000);
        }

        // Leer comandos desde la consola serial
        if (Serial.available() > 0) {
            String command = Serial.readStringUntil('\n'); // Leer el comando hasta que se presione Enter (salto de línea)

            // Ejecutar acciones en función del comando recibido
            if (command == "/srvrchange") {
                Serial.println("Introduzca la nueva dirección del servidor:");
                while (!Serial.available()) {
                    // Esperar hasta que se introduzca la nueva dirección
                }
                String newServerUrl = Serial.readStringUntil('\n');
                strcpy(serverUrl, newServerUrl.c_str()); // Actualizar la dirección del servidor
                Serial.print("Dirección del servidor actualizada a: ");
                Serial.println(serverUrl);

                Serial.print("Realize una prueba de conexion para verificar el correcto funcionamiento");
            } else if (command == "/wnetstate") {
                // Mostrar detalles de la red
                Serial.print("SSID de la red:");
                Serial.println(WiFi.SSID());
                Serial.print("Dirección IP asignada: ");
                Serial.println(WiFi.localIP());

                // Comprobar si el servidor está funcionando correctamente
                HTTPClient http;
                WiFiClient client;
                http.begin(client, serverUrl);
                int httpCode = http.GET();

                if (httpCode > 0) {
                    Serial.println("El servidor está funcionando correctamente.");
                } else {
                    Serial.println("Error al conectar con el servidor.");
                    Serial.println(http.errorToString(httpCode).c_str());
                }

                http.end();
            } else if (command == "/wifidisconnect") {
                Serial.println("Desconectando del WiFi...");
                WiFi.disconnect(true);
                delay(1000); // Esperar un momento antes de intentar reconectar
                Serial.println("Desconexión completa.");
                delay(1000);
                Serial.println("Fisicamente Reinicie el Sensor para volver a conectar el AP RPLAY");
            } else {
                Serial.println("Comando no reconocido");
                delay(1000);
                Serial.println("Use el auto completado del sensor R-PLAY ");
            }
        }
    } else {
        digitalWrite(RED_LED, LOW); // Apagar el LED rojo si no hay conexión
        // Hacer parpadear el LED amarillo rápidamente al intentar conectarse
        for (int i = 0; i < 5; i++) {
            digitalWrite(YELLOW_LED, HIGH);
            delay(200); // Retardo de 200 ms para un parpadeo rápido
            digitalWrite(YELLOW_LED, LOW);
            delay(200); // Retardo de 200 ms para un parpadeo rápido
        }
    }
}