#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

/**
 * JANGAN GANTI DISINI, GANTI DI ARDUINO EDITOR   
 */

#define SS_PIN D4
#define RST_PIN D3

// Ganti dengan kredensial WiFi dan URL server Anda
#define WIFI_SSID "NAMA_WIFI_ANDA"
#define WIFI_PASSWORD "PASSWORD_WIFI_ANDA"
#define SERVER_URL "http://192.168.1.10:8080" // Ganti dengan IP Localhost masing-masing

const char* server_tap_url = SERVER_URL "/api/tap";
const char* server_scan_url = SERVER_URL "/api/store-scan";

MFRC522 rfid(SS_PIN, RST_PIN);
LiquidCrystal_I2C lcd(0x27, 16, 2);

void setup() {
  Serial.begin(115200);
  SPI.begin();
  rfid.PCD_Init();
  lcd.init();
  lcd.backlight();

  lcd.setCursor(0, 0);
  lcd.print("Connecting WiFi");
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    lcd.print(".");
  }
  lcd.clear();
  lcd.print("WiFi Connected!");
  delay(1000);
}

void loop() {
  lcd.clear();
  lcd.print("Tempelkan Kartu");

  if (!rfid.PICC_IsNewCardPresent() || !rfid.PICC_ReadCardSerial()) {
    delay(100);
    return;
  }

  String uid = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    uid += String(rfid.uid.uidByte[i] < 0x10 ? "0" : "");
    uid += String(rfid.uid.uidByte[i], HEX);
  }
  uid.toUpperCase();

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("UID: " + uid);

  if (WiFi.status() == WL_CONNECTED) {
    // Kirim UID ke endpoint untuk registrasi form
    httpPost(server_scan_url, "uid=" + uid);

    // Kirim UID ke endpoint untuk proses presensi/kegiatan
    String response = httpPost(server_tap_url, "uid=" + uid);
    
    // Tampilkan pesan dari server presensi
    // (Anda bisa parse JSON di sini jika ingin lebih canggih,
    // tapi untuk simpelnya kita cari kata kunci saja)
    lcd.setCursor(0, 1);
    if (response.indexOf("Siswa Tdk Ditemukan") != -1) {
      lcd.print("Kartu Belum Terdaftar");
    } else if (response.indexOf("success") != -1) {
        // Cari message diantara quote
        int msgStart = response.indexOf("\"message\":\"") + 11;
        int msgEnd = response.indexOf("\"", msgStart);
        String message = response.substring(msgStart, msgEnd);
        lcd.print("Succes: ".response.indexOf("success"));
    } else {
      lcd.print("Error Koneksi");
    }

  } else {
    lcd.setCursor(0, 1);
    lcd.print("WiFi Terputus");
  }

  rfid.PICC_HaltA();
  delay(3000);
}

// Fungsi helper untuk melakukan HTTP POST
String httpPost(const char* url, String postData) {
  HTTPClient http;
  WiFiClient client;
  http.begin(client, url);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  int httpCode = http.POST(postData);
  String payload = "{}";

  if (httpCode > 0) {
    payload = http.getString();
    Serial.println("URL: " + String(url));
    Serial.println("HTTP Code: " + String(httpCode));
    Serial.println("Response: " + payload);
  } else {
    Serial.println("URL: " + String(url));
    Serial.println("HTTP Error: " + http.errorToString(httpCode));
  }

  http.end();
  return payload;
}