#!/usr/bin/env python
# -*- coding: utf-8 -*-

import Adafruit_DHT
import os
import datetime
# Get the current time
current_time = datetime.datetime.now()

# Convert the current time to a string in the desired format
time_string = current_time.strftime("%Y-%m-%d %H:%M:%S")

# Set up the DHT22 sensor
sensor = Adafruit_DHT.DHT22
pin = 4

# Read the temperature and humidity from the DHT22 sensor
humidity, temperature_celsius = Adafruit_DHT.read_retry(sensor, pin)

# Check if the sensor reading was successful
if humidity is not None and temperature_celsius is not None:
  # Calculate the heat index
  temperature_fahrenheit = round(temperature_celsius * 9/5.0 + 32, 2)
  humidity = round(humidity, 2)
  heat_index_fahrenheit = round(
      -42.379 + 2.04901523 * temperature_fahrenheit + 10.14333127 * humidity -
      0.22475541 * temperature_fahrenheit * humidity -
      6.83783 * 10**-3 * temperature_fahrenheit**2 -
      5.481717 * 10**-2 * humidity**2 +
      1.22874 * 10**-3 * temperature_fahrenheit**2 * humidity +
      8.5282 * 10**-4 * temperature_fahrenheit * humidity**2 -
      1.99 * 10**-6 * temperature_fahrenheit**2 * humidity**2, 2
  )
  # Write the temperature, humidity, and heat index to a file
  with open('/var/www/html/test.txt', 'w') as f:
      f.write('{}, {}, {}, {}'.format(temperature_fahrenheit, humidity, heat_index_fahrenheit, time_string))
else:
  # Write the error message to a file
  with open('/var/www/html/test.txt', 'w') as f:
      f.write('Error: Failed to read sensor data')

