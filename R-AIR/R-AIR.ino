#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <WiFiManager.h>
#include <EEPROM.h> // Incluir la librería para la EEPROM

// Configuración de conexión WiFi
const char* nodeMCUId = "RAIR-2310-01"; 
const char* defaultServerUrl = "https://192.168.1.12/rfidplay/main/demo55/dist/account/search.php";
char serverUrl[100] = "";

// Configuración de pines para el lector RFID
#define SS_PIN D2
#define RST_PIN D1

// Pines para los LEDs
#define RED_LED D5
#define YELLOW_LED D6
#define GREEN_LED D7
const int buzzerPin = D8;

// Dirección de memoria en la EEPROM donde se almacenará la dirección del servidor
int eepromAddress = 0;

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

    turnOffRedLED();
    turnOffYellowLED();
    turnOffGreenLED();

    res = wm.autoConnect("R-AIR (RAIR231001)", "RAIR-2310-01");

    if (!res) {
        Serial.println("Conexion Fallida");
        turnOnYellowLED();
    } else {
        Serial.println("Estás Conectado");
        turnOnGreenLED();

        SPI.begin();
        mfrc522.PCD_Init();
        delay(4);

        Serial.println("Listo, conexión exitosa!");

        // Leer la dirección del servidor almacenada en la EEPROM
        EEPROM.begin(512); // Inicializar la EEPROM
        EEPROM.get(eepromAddress, serverUrl);
        EEPROM.end();

        if (strlen(serverUrl) == 0) {
            // Si no hay una dirección del servidor en la EEPROM, usar la predeterminada
            strcpy(serverUrl, defaultServerUrl);
        }

        Serial.print("Dirección del servidor actual: ");
        Serial.println(serverUrl);
        Serial.print("Dirección MAC: ");
    Serial.println(WiFi.macAddress());
    }
}

void loop() {
    if (WiFi.status() == WL_CONNECTED) {
        turnOffYellowLED();
        if (mfrc522.PICC_IsNewCardPresent()) {
            if (mfrc522.PICC_ReadCardSerial()) {
                String uid = "";
                for (byte i = 0; i < mfrc522.uid.size; i++) {
                    uid += String(mfrc522.uid.uidByte[i], HEX);
                }

                tone(buzzerPin, 1000);
                delay(500);
                noTone(buzzerPin);

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
                    blinkLED(GREEN_LED, 3, 500);
                } else {
                    Serial.println("Error al enviar la solicitud");
                    Serial.println(http.errorToString(httpCode).c_str());
                    turnOnRedLED();
                    blinkLED(RED_LED, 3, 100);
                }

                http.end();
            }
        }

        if (Serial.available() > 0) {
            String command = Serial.readStringUntil('\n');

            if (command == "/srvrchange") {
                Serial.println("Introduzca la nueva dirección del servidor:");
                while (!Serial.available()) {
                }
                String newServerUrl = Serial.readStringUntil('\n');

                EEPROM.begin(512);
                strcpy(serverUrl, newServerUrl.c_str());
                EEPROM.put(eepromAddress, serverUrl);
                EEPROM.commit();
                EEPROM.end();

                Serial.print("Dirección del servidor actualizada a: ");
                Serial.println(serverUrl);

                Serial.println("Realice una prueba de conexión para verificar el correcto funcionamiento");
            } else if (command == "/wnetstate") {
                Serial.print("SSID de la red:");
                Serial.println(WiFi.SSID());
                Serial.print("Dirección IP asignada: ");
                Serial.println(WiFi.localIP());

                Serial.print("Dirección MAC: ");
    Serial.println(WiFi.macAddress());

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
                delay(1000);
                Serial.println("Desconexión completa.");
                delay(1000);
                Serial.println("Físicamente Reinicie el Sensor para volver a conectar el AP RPLAY");
            } else if (command == "/reboot") {
                Serial.println("Desconectando del WiFi...");
                Serial.println("Reiniciando NodeMCU...");
                delay(1000);
                Serial.println("Apagandose..."); // Espera un segundo antes de reiniciar
                ESP.restart();
            } else {
                Serial.println("Comando no reconocido");
                delay(1000);
                Serial.println("Use el auto completado del sensor R-PLAY ");
            }
        }
    } else {
        turnOffRedLED();
        for (int i = 0; i < 5; i++) {
            turnOnYellowLED();
            delay(200);
            turnOffYellowLED();
            delay(200);
        }
    }
}

void turnOnRedLED() {
    digitalWrite(RED_LED, HIGH);
}

void turnOffRedLED() {
    digitalWrite(RED_LED, LOW);
}

void turnOnYellowLED() {
    digitalWrite(YELLOW_LED, HIGH);
}

void turnOffYellowLED() {
    digitalWrite(YELLOW_LED, LOW);
}

void turnOnGreenLED() {
    digitalWrite(GREEN_LED, HIGH);
}

void turnOffGreenLED() {
    digitalWrite(GREEN_LED, LOW);
}

void blinkLED(int pin, int times, int duration) {
    for (int i = 0; i < times; i++) {
        digitalWrite(pin, HIGH);
        delay(duration);
        digitalWrite(pin, LOW);
        delay(duration);
    }
}

