
#include <EnvironmentCalculations.h>
#include <BME280I2C.h>
#include <Wire.h>
#include <string>

#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>

#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>

#include <LiquidCrystal_I2C.h>

#define SERIAL_BAUD 115200

const char* serverName = "http://192.168.240.52/api/post_meteo.php";

const char* server = "192.168.240.52";
const char* ssid     = "123soleil";
const char* password = "soleil123";

int count=0;
float temperature=0;
float humidite=0;
int id_capteur=01;

BME280I2C::Settings settings(
  BME280::OSR_X1,
  BME280::OSR_X1,
  BME280::OSR_X1,
  BME280::Mode_Forced,
  BME280::StandbyTime_1000ms,
  BME280::Filter_16,
  BME280::SpiEnable_False,
  BME280I2C::I2CAddr_0x76
);

BME280I2C bme(settings);
LiquidCrystal_I2C lcd(0x27, 16, 2);

void connexionWifi()
{
  WiFi.enableSTA(true); 
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
  Serial.println("WiFi connected");  
}





void setup()
{
  Serial.begin(SERIAL_BAUD);
  while(!Serial) {} // Wait
  Wire.begin(0,2);
  while(!bme.begin())
  {
    Serial.println("Could not find BME280 sensor!");
    delay(1000);
  }

  switch(bme.chipModel())
  {
     case BME280::ChipModel_BME280:
       Serial.println("Found BME280 sensor! Success.");
       break;
     case BME280::ChipModel_BMP280:
       Serial.println("Found BMP280 sensor! No Humidity available.");
       break;
     default:
       Serial.println("Found UNKNOWN sensor! Error!");
  }
  connexionWifi();

  lcd.init();
  lcd.cursor_on();
  lcd.backlight();
  lcd.setCursor(0, 0);
}









void printBME280Data
(
   Stream* client
)
{
  float temp(NAN), hum(NAN), pres(NAN);

  BME280::TempUnit tempUnit(BME280::TempUnit_Celsius);
  BME280::PresUnit presUnit(BME280::PresUnit_hPa);

   bme.read(pres, temp, hum, tempUnit, presUnit);

  //affiche les infos que récupère la sonde 
  lcd.setCursor(0, 0); 
  lcd.print("Temp:");
  lcd.setCursor(5, 0); 
  lcd.print(temp); 
  lcd.setCursor(10, 0); 
  lcd.print((char)223);
  lcd.setCursor(11, 0); 
  lcd.print("C");

  lcd.setCursor(0, 1); 
  lcd.print("Humidite:");
  lcd.setCursor(9, 1); 
  lcd.print(hum); 
  lcd.setCursor(14, 1); 
  lcd.print("%");

  lcd.init();
  lcd.setCursor(0, 1); 
  lcd.print(server);

  delay(1000);

  count++;
  temperature += temp;
  humidite += hum;






  if(count==10)
  {S
  temperature = temperature/10;
  humidite = humidite/10;   

  WiFiClient client;
  HTTPClient http;
  http.begin(client, serverName);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpCode = http.POST("id_capteur="+ String(id_capteur) + "&temp=" + String(temperature) + "&hum=" + String(humidite));
  String payload = http.getString();
  Serial.println(httpCode);
  Serial.println(payload);
  http.end();
  delay(1000);

  count=0;
  temperature = 0;
  humidite = 0;
  }
  delay(1000);
}


void loop()
{
  printBME280Data(&Serial);
  delay(500);
}




