#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ESP8266WebServer.h>
#include <SPI.h>
#include <MFRC522.h>

const char* selectedSsid = "";
const char* password = "";
const char* nodeMCUId = "RCAIR2023";
const char* serverUrl = "";

#define SS_PIN D2
#define RST_PIN D1

MFRC522 mfrc522(SS_PIN, RST_PIN);

ESP8266WebServer server(80);

void setup() {
  Serial.begin(9600);
  WiFi.mode(WIFI_AP);
  WiFi.softAP("ConfigAP", "");

  server.on("/", HTTP_GET, [](){
    String page = "<html><body>\
                  <form action='/save' method='POST'>\
                    WiFi SSID: <select name='ssid'>";
    
    int n = WiFi.scanNetworks();
    for (int i = 0; i < n; ++i) {
      page += "<option value='" + WiFi.SSID(i) + "'>" + WiFi.SSID(i) + "</option>";
    }

    page += "</select><br>\
             WiFi Password: <input type='password' name='password'><br>\
             Server URL: <input type='text' name='serverUrl'><br>\
             <input type='submit' value='Save'>\
             </form>\
             </body></html>";

    server.send(200, "text/html", page);
  });

  server.on("/save", HTTP_POST, [](){
    selectedSsid = server.arg("ssid").c_str();
    password = server.arg("password").c_str();
    serverUrl = server.arg("serverUrl").c_str();
    server.send(200, "text/plain", "Config saved. Restart device.");
  });

  server.begin();

  SPI.begin();
  mfrc522.PCD_Init();
  delay(4);

  Serial.println("Ready");
}

void loop() {
  server.handleClient();

  if (selectedSsid && password && WiFi.status() != WL_CONNECTED) {
    WiFi.begin(selectedSsid, password);
    // Connect to WiFi
  }

  if (WiFi.status() == WL_CONNECTED) {
    if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {
      delay(50);
      return;
    }

    String uid = "";
    for (byte i = 0; i < mfrc522.uid.size; i++) {
      uid += String(mfrc522.uid.uidByte[i], HEX);
    }

    Serial.print("UID: ");
    Serial.println(uid);

    HTTPClient http;
    WiFiClient client;
    String url = String(serverUrl) + "?serial=" + uid + "&nodeMCUId=" + nodeMCUId;
    http.begin(client, url);
    int httpCode = http.GET();

    if (httpCode > 0) {
      String payload = http.getString();
      Serial.println("Server response: ");
      Serial.println(payload);
    } else {
      Serial.println("Error on sending request");
      Serial.println(http.errorToString(httpCode).c_str());
    }

    http.end();

    delay(1000);
  }
}
