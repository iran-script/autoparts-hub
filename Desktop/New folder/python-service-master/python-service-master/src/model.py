import math
import torch
from detecto.core import Model
from detecto.visualize import show_labeled_image
import matplotlib.pyplot as plt
from detecto.utils import read_image

import cv2
import numpy as np
from detecto.utils import reverse_normalize, normalize_transform, _is_iterable
from torchvision import transforms

from src.gis_utils import extract_lat_lng, extract_normal_coords
from urllib.request import urlopen

def url_to_image(url, readFlag=cv2.IMREAD_COLOR):
    # download the image, convert it to a NumPy array, and then read
    # it into OpenCV format
    resp = urlopen(url)
    image = np.asarray(bytearray(resp.read()), dtype="uint8")
    image = cv2.imdecode(image, readFlag)

    # return the image
    return image


# load model
labels = ['anomaly']   #your labels
SCORE_FILTER = 0.9
model = Model.load("src/termal1_weights.pth", labels)

# local test
def pmain(img_p):
  image = read_image(img_p)
  avg_color_per_row = np.average(image, axis=0)
  avg_color = np.average(avg_color_per_row, axis=0)
  print( -20 + int(avg_color[0] * 0.5))
  print(image.shape)
  labels, boxes, scores = model.predict(image)
  new_boxes = boxes.tolist()
  threshhold = 0.90
  lst = []
  for i in range(len(scores)):
    if scores[i] > threshhold:
      lst.append(i)

  new_boxes = [new_boxes[x] for x in lst]
  boxes = torch.tensor(new_boxes)
  labels = [labels[x] for x in lst]
  w1, h1, x1, y1 = extract_normal_coords(boxes[3], image.shape)
  print(new_boxes[3])
  print("w: ", w1, ", h: ", h1, ", x: ", x1, ", y: ", y1)
  show_labeled_image(image, boxes, labels)



def full_process(src:str, lat, lng , image_id, inspection_id, cmpx = 6.25 ):
  # { x: 0.444531, y: 0.286133, w: 0.023438, h: 0.037109, label: 'anomally1', id: 1 }
  img = url_to_image(src)
  returns_lst = []
  labels, boxes, scores = model.predict(img)
  new_boxes = boxes.tolist()
  threshhold = 0.90
  lst = []
  for i in range(len(scores)):
    if scores[i] > threshhold:
      lst.append(i)

  new_boxes = [new_boxes[x] for x in lst]
  labels = [labels[x] for x in lst]
  for e in new_boxes:
    w, h, x, y = extract_normal_coords(e, img.shape)
    local_lat, local_long = extract_lat_lng(e, img.shape, cmpx, lat, lng)
    croped = img[e[1]:e[0], e[3]:e[2]]
    avg_color_per_row = np.average(croped, axis=0)
    avg_color = np.average(avg_color_per_row, axis=0)
    temp = -20 + int(avg_color[0] * 0.5)
    obj = { "x": x, "y": y, "w": w, "h": h, "temprather": temp, "lat": local_lat, "lng": local_long, "type_id": 1 } 
    returns_lst.append(obj)
  
  return image_id, inspection_id, returns_lst



if __name__ == "__main__":
    _path = "../assets/DJI_0154.JPG"
    pmain(_path)
