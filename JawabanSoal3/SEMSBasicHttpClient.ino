#include <ArduinoJson.h>
#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
ESP8266WiFiMulti WiFiMulti;
#include <PZEM004Tv30.h>
PZEM004Tv30 pzem(5, 4);
StaticJsonDocument<384> doc;

// Variable triac
const int r1 = D0;
const int r2 = D3;
const int r3 = D5;
String data = "";

// Variable logika PZEM
float dataL1 = 0;
float dataL2 = 0;
float dataL3 = 0;
int statusL1 = 0;
int statusL2 = 0;
int statusL3 = 0;
int cekL1 = 0;
int cekL2 = 0;
int cekL3 = 0;
float autoclose = 0.00;

// variable PZEM
float voltage = 0;
float current = 0;
float power = 0;
float energy = 0;
float frequency = 0;
float pf = 0;
static uint32_t prev_ms;

WiFiClient client;

HTTPClient http;
void setup()
{

    Serial.begin(115200);

    pinMode(r1, OUTPUT);
    pinMode(r2, OUTPUT);
    pinMode(r3, OUTPUT);
    digitalWrite(r1, 0);
    digitalWrite(r2, 0);
    digitalWrite(r3, 0);

    // Serial.setDebugOutput(true);

    Serial.println();
    Serial.println();
    Serial.println();

    for (uint8_t t = 4; t > 0; t--)
    {
        Serial.printf("[SETUP] WAIT %d...\n", t);
        Serial.flush();
        delay(1000);
    }

    WiFi.mode(WIFI_STA);
    WiFiMulti.addAP("devcampoffice", "n3wd3vc4mp2");
    prev_ms = millis();
}

void loop()
{
    // wait for WiFi connection
    if ((WiFiMulti.run() == WL_CONNECTED))
    {

        getPower();

        //        Serial.print("[HTTP] begin...\n");
        if (http.begin(client, "http://sems.api88.link/getHardware.php"))
        { // HTTP

            //            Serial.print("[HTTP] GET...\n");
            // start connection and send HTTP header
            int httpCode = http.GET();

            // httpCode will be negative on error
            if (httpCode > 0)
            {
                // HTTP header has been send and Server response header has been handled
                //                Serial.printf("[HTTP] GET... code: %d\n", httpCode);

                // file found at server
                if (httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY)
                {
                    String payload = http.getString();

                    DeserializationError error = deserializeJson(doc, payload);

                    if (error)
                    {
                        Serial.print(F("deserializeJson() failed: "));
                        Serial.println(error.f_str());
                        return;
                    }

                    for (JsonObject data_item : doc["data"].as<JsonArray>())
                    {

                        const char *data_item_ID = data_item["ID"];         // "dev000088", "dev000089", "dev000090"
                        const char *data_item_STACK = data_item["STACK"];   // "S100", "S100", "S100"
                        const char *data_item_STATUS = data_item["STATUS"]; // "off", "off", "off"
                        String data_ID = data_item_ID;
                        String data_Status = data_item_STATUS;

                        if (data_ID == "smp000088")
                        {
                            //                            Serial.println("smp000088");
                            if (data_Status == "off")
                            {
                                digitalWrite(r1, 0);
                                Serial.println("1 OFF");
                                dataL1 = 0;
                                statusL1 = 0;
                            }
                            if (data_Status == "on")
                            {
                                digitalWrite(r1, 1);
                                Serial.println("1 ON");
                                statusL1 = 1;
                            }
                        }
                        if (data_ID == "smp000089")
                        {
                            //                            Serial.println("smp000089");
                            if (data_Status == "off")
                            {
                                digitalWrite(r2, 0);
                                Serial.println("2 OFF");
                                statusL2 = 0;
                                dataL2 = 0;
                                cekL1 = 0;
                            }
                            if (data_Status == "on")
                            {
                                digitalWrite(r2, 1);
                                Serial.println("2 ON");
                                statusL2 = 1;
                            }
                        }
                        if (data_ID == "smp000090")
                        {
                            //                            Serial.println("smp000090");
                            if (data_Status == "off")
                            {
                                digitalWrite(r3, 0);
                                Serial.println("3 OFF");
                                statusL3 = 0;
                                dataL3 = 0;
                                cekL2 = 0;
                            }
                            if (data_Status == "on")
                            {
                                digitalWrite(r3, 1);
                                Serial.println("3 ON");
                                statusL3 = 1;
                            }
                        }
                    }

                    //          Serial.println(payload);

                    cekStatus();
                }
            }
            else
            {
                Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
            }

            http.end();
        }
        else
        {
            Serial.printf("[HTTP} Unable to connect\n");
        }

        // data += voltage;
        // data += "/";
        // data += dataL1;
        // data += "/";
        // data += dataL2;
        // data += "/";
        // data += dataL3;
        // data += "/";
        // data += power;
        // data += "/";
        // data += energy;
        // data += "/";
        // data += frequency;
        // data += "/";
        // data += pf;

        // Serial.print("Prev_ms = ");
        // Serial.println(prev_ms);
        // Serial.print("milis = ");
        // Serial.println(millis());

        if (millis() > prev_ms + 60000)
        {
            Serial.println("30000");
            if (autoclose < 0.05)
            {
                dataL1 = 0;
                dataL2 = 0;
                dataL3 = 0;
                // kirim data triac mati disini
                kirimData("null",0.0,0.0,0.0,0.0,0.0,0);
                Serial.println("CLOSE");
            }
            if (autoclose < 0.5)
            {
                dataL3 = 0;
                // kirim data triac mati disini
                kirimData("null",0.0,0.0,0.0,0.0,0.0,3);
                Serial.println("CLOSE s3");
            }
            prev_ms = millis();
        }
    }

    delay(5000);
}

void kirimData(String id, float l1, float l2, float l3, float volt, float power, int codes)
{
    data += "http://sems.api88.link/";

    if (codes == 0)
    {
        data += "setHardware.php?id=1";
    }
    if (codes == 3)
    {
        data += "setHardware.php?id=3";
    }
    if (codes == 1)
    {
        data += "setData.php?";
        data += "idHardware=";
        data += id;
        data += "&stack=0809100";
        data += "&l1=";
        data += l1;
        data += "&l2=";
        data += l2;
        data += "&l3=";
        data += l3;
        data += "&volt=";
        data += volt;
        data += "&power=";
        data += power;
    }
    Serial.println(data);
    if (http.begin(client, data))
    {
        int httpCodes = http.GET();
        Serial.print(httpCodes);
    }
    data = "";
    delay(2000);
}

void getPower()
{
    voltage = pzem.voltage();
    if (!isnan(voltage))
    {
        Serial.print("Voltage: ");
        Serial.print(voltage);
        Serial.println("V");
    }
    else
    {
        Serial.println("Error reading voltage");
    }
    current = pzem.current();
    if (!isnan(current))
    {
        Serial.print("Current: ");
        Serial.print(current);
        Serial.println("A");
    }
    else
    {
        Serial.println("Error reading current");
    }
    power = pzem.power();
    if (!isnan(power))
    {
        Serial.print("Power: ");
        Serial.print(power);
        Serial.println("W");
    }
    else
    {
        Serial.println("Error reading power");
    }
    energy = pzem.energy();
    if (!isnan(energy))
    {
        Serial.print("Energy: ");
        Serial.print(energy, 3);
        Serial.println("kWh");
    }
    else
    {
        Serial.println("Error reading energy");
    }
    frequency = pzem.frequency();
    if (!isnan(frequency))
    {
        Serial.print("Frequency: ");
        Serial.print(frequency, 1);
        Serial.println("Hz");
    }
    else
    {
        Serial.println("Error reading frequency");
    }
    pf = pzem.pf();
    if (!isnan(pf))
    {
        Serial.print("PF: ");
        Serial.println(pf);
    }
    else
    {
        Serial.println("Error reading power factor");
    }
    autoclose = current;
}

void cekStatus()
{
    if (statusL1 == 1 && cekL1 == 0)
    {
        Serial.println("L1");
        dataL1 = current - dataL2 - dataL3;
        Serial.println(dataL1);
        float power = dataL1 * voltage;
        kirimData("smp000088", dataL1,dataL2,dataL3, voltage, power, 1);
    }
    if (statusL2 == 1 && cekL2 == 0)
    {
        Serial.println("L2");
        dataL2 = current - dataL1 - dataL3;
        Serial.println(dataL2);
        float power = dataL2 * voltage;
        kirimData("smp000089", dataL1,dataL2,dataL3, voltage, power, 1);
        cekL1 = 1;
    }
    if (statusL3 == 1 && cekL2 == 0)
    {
        Serial.println("L3");
        dataL3 = current - dataL1 - dataL2;
        Serial.println(dataL3);
        float power = dataL3 * voltage;
        kirimData("smp000090", dataL1,dataL2,dataL3, voltage, power, 1);
        cekL2 = 1;
    }
    delay(5000);
}
