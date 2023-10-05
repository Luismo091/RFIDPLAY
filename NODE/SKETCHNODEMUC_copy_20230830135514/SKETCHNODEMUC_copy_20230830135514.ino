#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>

// Configuración de conexión WiFi
const char* ssid = "WS-estudiantes";
const char* password = "Sangil$$2023";
const char* nodeMCUId = "RCAIR2023"; 
// Configuración de pines para el lector RFID
#define SS_PIN D2
#define RST_PIN D1

// Crear instancia del lector RFID
MFRC522 mfrc522(SS_PIN, RST_PIN);

// URL del servidor PHP
const char* serverUrl = "http://192.168.1.2/rfidplay/main/demo55/dist/account/search.php";

void setup()
{
  Serial.begin(9600);
  while (!Serial)
    ;

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(1000);
    Serial.println("Conectando...");
  }
  Serial.println("Red Wifi Conectado");

  SPI.begin();
  mfrc522.PCD_Init();
  delay(4);

  Serial.println("Listo conexion exitosa!");
}

void loop()
{
  if (WiFi.status() != WL_CONNECTED)
  {
    Serial.println("WiFi No disponible");
    return;
  }

  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial())
  {
    delay(50);
    return;
  }

  String uid = "";
  for (byte i = 0; i < mfrc522.uid.size; i++)
  {
    uid += String(mfrc522.uid.uidByte[i], HEX);
  }

  Serial.print("UID: ");
  Serial.println(uid);

  if (WiFi.status() == WL_CONNECTED)
  {
    HTTPClient http;
    WiFiClient client;
    String url = String(serverUrl) + "?serial=" + uid + "&nodeMCUId=" + nodeMCUId;
    http.begin(client, url);
    int httpCode = http.GET();

    if (httpCode > 0)
    {
      String payload = http.getString();
      Serial.println("Server response: ");
      Serial.println(payload);
    }
    else
    {
      Serial.println("Error on sending request");
      Serial.println(http.errorToString(httpCode).c_str());
    }

    http.end();
  }

  delay(1000);
}
