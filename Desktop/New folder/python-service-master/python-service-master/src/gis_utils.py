
import math


def translate_latlong(lat,long,lat_translation_meters,long_translation_meters):
    ''' method to move any lat,long point by provided meters in lat and long direction.
    params :
        lat,long: lattitude and longitude in degrees as decimal values, e.g. 37.43609517497065, -122.17226450150885
        lat_translation_meters: movement of point in meters in lattitude direction.
                                positive value: up move, negative value: down move
        long_translation_meters: movement of point in meters in longitude direction.
                                positive value: left move, negative value: right move
        '''
    earth_radius = 6378.137

    #Calculate top, which is lat_translation_meters above
    m_lat = (1 / ((2 * math.pi / 360) * earth_radius)) / 1000;  
    lat_new = lat + (lat_translation_meters * m_lat)

    #Calculate right, which is long_translation_meters right
    m_long = (1 / ((2 * math.pi / 360) * earth_radius)) / 1000;  # 1 meter in degree
    long_new = long + (long_translation_meters * m_long) / math.cos(lat * (math.pi / 180));
    
    return lat_new,long_new

def extract_normal_coords(a, imgS):
  w = int(a[2] - a[0]) / imgS[1]
  h = int(a[3] - a[1]) / imgS[0]
  x = float(int(a[2] + a[0]) /2) / imgS[1]
  y = float(int(a[3] + a[1]) /2 ) / imgS[0]
  return w, h, x, y

def extract_lat_lng(a, imgS, cmpx, latitude, longitude):
  x = float(int(a[2] + a[0]) /2) - (imgS[1] / 2)
  y = float(int(a[3] + a[1]) /2 ) - (imgS[0] / 2)
  mx = x * cmpx / 100
  my = y * cmpx / 100
  return translate_latlong(latitude, longitude, my, mx) # lat is Y axis and long is xaxis
