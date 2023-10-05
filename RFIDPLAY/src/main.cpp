#include <SPI.h>
#include <MFRC522.h>

/*
In the ESP8266, D3 pin is RST_PIN and
D4 pin is SS_PIN
*/
#define RST_PIN D3
#define SS_PIN D4

MFRC522 reader(SS_PIN, RST_PIN);
MFRC522::MIFARE_Key key;
void setup()
{
  Serial.begin(9600); // Initialize serial communications and wait until it is ready
  while (!Serial)
  {
    // Nothing here. Just wait for serial to be present
  }

  SPI.begin();

  reader.PCD_Init();
  // Just wait some seconds...
  delay(4);
  // Prepare the security key for the read and write functions.
  // Normally it is 0xFFFFFFFFFFFF
  // Note: 6 comes from MF_KEY_SIZE in MFRC522.h
  for (byte i = 0; i < 6; i++)
  {
    key.keyByte[i] = 0xFF; // keyByte is defined in the "MIFARE_Key" 'struct' definition in the .h file of the library
  }
  Serial.println("Ready!");
}

void loop()
{
  // Reset the loop if no new card present on the sensor/reader. This saves the entire process when idle.
  if (!reader.PICC_IsNewCardPresent())
  {
    return;
  }

  // Select one of the cards. This returns false if read is not successful; and if that happens, we stop the code
  if (!reader.PICC_ReadCardSerial())
  {
    return;
  }

  // At this point, the serial can be read. We transform from byte to hex

  String serial = "";
  for (int x = 0; x < reader.uid.size; x++)
  {
    // If it is less than 10, we add zero
    if (reader.uid.uidByte[x] < 0x10)
    {
      serial += "0";
    }
    // Transform the byte to hex
    serial += String(reader.uid.uidByte[x], HEX);
    // Add a hypen
    if (x + 1 != reader.uid.size)
    {
      serial += "-";
    }
  }
  // Transform to uppercase
  serial.toUpperCase();

  Serial.println("Read serial is: " + serial);

  // Halt PICC
  reader.PICC_HaltA();
  // Stop encryption on PCD
  reader.PCD_StopCrypto1();
}